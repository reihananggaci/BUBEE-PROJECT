<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\StockMutation;
use App\Models\Voucher;
use App\Services\MidtransDummy;
use App\Services\MidtransService;
use App\Services\OngkirDummy;
use App\Services\PriceService;
use App\Services\RajaOngkirService;
use App\Services\WaNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function form()
    {
        $carts = Cart::with('product', 'variant')->where('user_id', Auth::id())->get();
        [$carts, $dibuang] = $carts->partition(fn ($c) => $c->product && $c->product->is_active && $c->variant);
        $dibuang->each(fn ($c) => $c->delete());
        if ($carts->isEmpty()) {
            return redirect('/')->withErrors(['cart' => 'Keranjang masih kosong']);
        }
        $kurir = OngkirDummy::listKurir();
        $provinces = RajaOngkirService::provinces();
        $ongkirLive = RajaOngkirService::configured();
        $vouchers = Voucher::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('berlaku_sampai')->orWhere('berlaku_sampai', '>=', now()->toDateString());
            })
            ->whereColumn('terpakai', '<', 'kuota')->get();

        return web_view('checkout', compact('carts', 'kurir', 'vouchers', 'provinces', 'ongkirLive'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'nama_penerima' => 'required|max:100', 'hp_penerima' => 'required|max:20',
            'alamat' => 'required|max:500', 'kurir' => 'required|in:JNE,JNT,SPX,SICEPAT,LION,SENTRAL',
            'layanan' => 'required|max:30', 'city_id' => 'nullable|max:10',
            'kota_tujuan' => 'nullable|max:100', 'voucher_kode' => 'nullable|max:20',
        ]);
        $user = Auth::user();
        $carts = Cart::with('product', 'variant')->where('user_id', $user->id)->get();
        [$carts, $dibuang] = $carts->partition(fn ($c) => $c->product && $c->product->is_active && $c->variant);
        $dibuang->each(fn ($c) => $c->delete());
        if ($carts->isEmpty()) {
            return back()->withErrors(['cart' => 'Keranjang kosong / item sudah tidak tersedia']);
        }

        try {
            // 1. Hitung total (tanpa tulis DB)
            $totalProduk = 0;
            $berat = 0;
            $dimensiItems = [];
            foreach ($carts as $c) {
                $variant = $c->variant;
                if (! $variant) {
                    throw new \Exception('Varian '.$c->product->nama.' tidak valid, hapus lalu tambah ulang dari katalog.');
                }
                if ($variant->stok < $c->qty) {
                    throw new \Exception('Stok '.$c->product->nama.' ('.$variant->label().') kurang (sisa '.$variant->stok.')');
                }
                $ph = PriceService::untuk($c->product, $user, $c->qty, $variant);
                $harga = $ph['harga'];
                $tipe = $ph['tipe'];
                $totalProduk += $harga * $c->qty;
                $beratItem = $variant->beratGram();
                $berat += $beratItem * $c->qty;
                $dimensiItems[] = [
                    'berat_gram' => $beratItem, 'qty' => $c->qty,
                    'p' => $c->product->panjang_cm, 'l' => $c->product->lebar_cm, 't' => $c->product->tinggi_cm,
                ];
                $c->harga_temp = $harga;
                $c->tipe_temp = $tipe;
            }
            // 2. Ongkir dari BERAT TAGIHAN = maks(aktual, volumetrik P×L×T÷pembagi)
            $tagih = OngkirDummy::beratTagih($dimensiItems, $r->kurir);
            $cost = RajaOngkirService::cost($r->kurir, $r->layanan, $tagih['tagih'], $r->city_id);
            $ongkir = $cost['ongkir'];
            // 3. Voucher: cek syarat dulu, klaim kuota atomik di dalam transaksi
            $voucher = null;
            $diskon = 0;
            if ($r->filled('voucher_kode')) {
                $voucher = Voucher::where('kode', strtoupper(trim($r->voucher_kode)))->first();
                if (! $voucher) {
                    throw new \Exception('Kode voucher tidak ditemukan.');
                }
                $cek = $voucher->cek($user, $totalProduk);
                if (! $cek['ok']) {
                    throw new \Exception($cek['pesan']);
                }
                $diskon = $cek['diskon'];
            }
            // 4. Tulis DB dalam transaksi singkat (tanpa I/O jaringan)
            $order = DB::transaction(function () use ($r, $user, $carts, $totalProduk, $ongkir, $cost, $tagih, &$voucher, &$diskon) {
                // Kunci baris varian agar 2 checkout bersamaan tidak menjual stok yang sama.
                // (Hanya MySQL; SQLite diabaikan karena tak mendukung FOR UPDATE.)
                $ids = $carts->pluck('variant_id')->filter()->unique()->values();
                if ($ids->isNotEmpty() && DB::getDriverName() === 'mysql') {
                    ProductVariant::whereIn('id', $ids)->lockForUpdate()->get();
                }
                // Cek ulang SETELAH kunci: stok bisa berubah sepersekian detik. Tolak jika negatif/kurang.
                $segar = ProductVariant::whereIn('id', $ids)->get()->keyBy('id');
                foreach ($carts as $c) {
                    $sv = $segar->get($c->variant_id);
                    if (! $sv || $sv->stok < $c->qty) {
                        throw new \Exception('Stok '.($c->product?->nama ?? 'produk').' baru saja berubah, ulangi checkout (sisa '.($sv?->stok ?? 0).').');
                    }
                }
                if ($voucher) {
                    $dapat = Voucher::where('id', $voucher->id)->whereColumn('terpakai', '<', 'kuota')->increment('terpakai');
                    if (! $dapat) {
                        $voucher = null;
                        $diskon = 0;
                    } // kuota habis sepersekian detik: lanjut tanpa voucher
                }
                $order = Order::create([
                    'user_id' => $user->id, 'kode' => 'BB-'.date('YmdHis').rand(10, 99),
                    'total_produk' => $totalProduk, 'ongkir' => $ongkir, 'ongkir_sumber' => $cost['sumber'],
                    'berat_tagih_gram' => $tagih['tagih'],
                    'voucher_id' => $voucher?->id, 'diskon' => $diskon,
                    'total_bayar' => $totalProduk + $ongkir - $diskon,
                    'kurir' => $r->kurir, 'layanan' => $r->layanan,
                    'status' => 'menunggu_bayar', 'alamat' => $r->alamat,
                    'kota_tujuan' => $r->kota_tujuan, 'city_id' => $r->city_id,
                    'nama_penerima' => $r->nama_penerima, 'hp_penerima' => $r->hp_penerima,
                ]);
                foreach ($carts as $c) {
                    OrderItem::create([
                        'order_id' => $order->id, 'product_id' => $c->product_id, 'variant_id' => $c->variant_id,
                        'qty' => $c->qty, 'harga_satuan' => $c->harga_temp,
                        'subtotal' => $c->harga_temp * $c->qty, 'tipe_harga' => $c->tipe_temp,
                    ]);
                    $p = $c->product;
                    $v = ProductVariant::find($c->variant_id);
                    StockMutation::create([
                        'product_id' => $p->id, 'variant_id' => $v->id, 'tipe' => 'keluar', 'qty' => $c->qty,
                        'stok_sebelum' => $v->stok, 'stok_sesudah' => $v->stok - $c->qty,
                        'keterangan' => 'Order '.$order->kode.' ('.$v->label().')',
                    ]);
                    $v->decrement('stok', $c->qty);
                    $p->decrement('stok', $c->qty);
                }
                Cart::where('user_id', $user->id)->delete();

                return $order;
            });
            // 5. Di luar transaksi: snap token + notifikasi (gagal pun order tetap aman)
            $real = MidtransService::createSnapToken($order);
            $order->snap_token = $real ?? MidtransDummy::createSnapToken($order);
            $order->save();
            WaNotifier::orderBaru($order);
            ActivityLog::catat('order_buat', $order->kode, 'Rp '.number_format($order->total_bayar), 'order', null, '/admin/order/'.$order->id);

            return redirect('/bayar/'.$order->id)->with('ok', 'Order dibuat! Kode: '.$order->kode);
        } catch (\Exception $e) {
            return back()->withErrors(['order' => $e->getMessage()])->withInput();
        }
    }

    public function bayar(Order $order)
    {
        abort_unless($order->user_id === Auth::id() || Auth::user()->role === 'admin', 403);
        $order->load('items.product', 'items.variant', 'voucher');
        $midtransLive = MidtransService::configured() && ! str_starts_with((string) $order->snap_token, 'DUMMY-');
        $midtrans = [
            'live' => $midtransLive,
            'clientKey' => MidtransService::clientKey(),
            'snapJs' => MidtransService::snapJsUrl(),
        ];
        $bank = [
            'nama' => Setting::get('bank_nama', 'BCA'),
            'rekening' => Setting::get('bank_rekening', '1234567890'),
            'atas_nama' => Setting::get('bank_atas_nama', 'Toko Bubee'),
        ];

        return web_view('bayar', compact('order', 'midtrans', 'bank'));
    }

    public function simulasiBayar(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        abort_unless($order->status === 'menunggu_bayar', 400);
        $midtransLive = MidtransService::configured() && ! str_starts_with((string) $order->snap_token, 'DUMMY-');
        abort_if($midtransLive, 403);
        $order->update(['status' => 'diproses']);
        WaNotifier::bayarMasuk($order, 'simulasi');

        return redirect('/pesanan/'.$order->id)->with('ok', 'Pembayaran dummy berhasil! Pesanan diproses.');
    }

    public function uploadBukti(Request $r, Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        abort_unless($order->status === 'menunggu_bayar', 400);
        $r->validate(['bukti' => 'required|image|max:2048']);
        $path = $r->file('bukti')->store('bukti', 'public');
        $order->update(['bukti_bayar' => $path, 'status' => 'diproses']);
        WaNotifier::bayarMasuk($order, 'bukti transfer');

        return back()->with('ok', 'Bukti terupload, menunggu verifikasi admin ✅');
    }

    public function pesananSaya()
    {
        $orders = Order::with('items.product', 'items.variant')->where('user_id', Auth::id())->latest()->get();

        return web_view('pesanan', compact('orders'));
    }

    public function detail(Order $order)
    {
        abort_unless($order->user_id === Auth::id() || Auth::user()->role === 'admin', 403);
        $order->load('items.product', 'items.variant', 'user', 'voucher');
        $steps = ['menunggu_bayar' => 1, 'diproses' => 2, 'dikirim' => 3, 'selesai' => 4];
        $current = $steps[$order->status] ?? 0;
        $lacak = null;
        $lacakUrl = null;
        if ($order->resi) {
            $lacakUrl = RajaOngkirService::lacakUrl($order->kurir, $order->resi);
            $lacak = RajaOngkirService::track($order->kurir, $order->resi);
        }
        $isReseller = Auth::user()->role === 'reseller';
        $alasanBatal = self::alasanBatal($isReseller);

        return web_view('pesanan-detail', compact('order', 'steps', 'current', 'lacak', 'lacakUrl', 'alasanBatal'));
    }

    public static function alasanBatal(bool $isReseller): array
    {
        $umum = ['Berubah pikiran', 'Salah alamat', 'Salah ukuran/warna', 'Lama diproses', 'Lainnya'];

        return $isReseller ? array_merge($umum, ['Pelanggan saya batal', 'Salah rekap', 'Mau ganti varian']) : $umum;
    }

    public function batal(Request $r, Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        abort_unless(in_array($order->status, ['menunggu_bayar', 'diproses']), 400);
        $r->validate(['alasan' => 'required|max:100']);
        DB::transaction(function () use ($order, $r) {
            $order->load('items');
            foreach ($order->items as $it) {
                $p = Product::find($it->product_id);
                if ($p) {
                    StockMutation::create(['product_id' => $p->id, 'variant_id' => $it->variant_id, 'tipe' => 'masuk', 'qty' => $it->qty, 'stok_sebelum' => $p->stok, 'stok_sesudah' => $p->stok + $it->qty, 'keterangan' => 'Batal user '.$order->kode]);
                    $p->increment('stok', $it->qty);
                    if ($it->variant_id) {
                        ProductVariant::where('id', $it->variant_id)->increment('stok', $it->qty);
                    }
                }
            }
            if ($order->voucher_id) {
                Voucher::where('id', $order->voucher_id)->where('terpakai', '>', 0)->decrement('terpakai');
            }
            $order->update(['status' => 'batal', 'alasan_batal' => $r->alasan]);
        });
        WaNotifier::batalMasuk($order->fresh());
        WaNotifier::statusOrder($order->fresh());
        ActivityLog::catat('order_batal', $order->kode, 'Alasan: '.$r->alasan, 'order', null, '/admin/order/'.$order->id);

        return redirect('/pesanan')->with('ok', 'Order '.$order->kode.' dibatalkan, stok dikembalikan.');
    }

    public function selesai(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        abort_unless($order->status === 'dikirim', 400);
        $order->update(['status' => 'selesai']);
        ActivityLog::catat('order_selesai', $order->kode, null, 'order', null, '/admin/order/'.$order->id);

        return back()->with('ok', 'Pesanan selesai. Terima kasih! ⭐');
    }
}
