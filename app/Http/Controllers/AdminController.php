<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Cart;
use App\Models\Faq;
use App\Models\NotificationLog;
use App\Models\OpnameItem;
use App\Models\OpnameSesi;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductVariant;
use App\Models\Retur;
use App\Models\Setting;
use App\Models\StockMutation;
use App\Models\User;
use App\Models\Voucher;
use App\Services\MidtransService;
use App\Services\RajaOngkirService;
use App\Services\WaNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $totalProduk = Product::count();
        $stokMenipis = Product::where('stok', '<=', 10)->get();
        $orderBaru = Order::where('status', 'menunggu_bayar')->count();
        $omzet = Order::whereIn('status', ['diproses', 'dikirim', 'selesai'])->sum('total_bayar');
        $orders = Order::with('user')->latest()->take(5)->get();

        // Progres harian
        $today = now()->toDateString();
        $orderHariIni = Order::whereDate('created_at', $today)->count();
        $omzetHariIni = Order::whereDate('created_at', $today)->whereIn('status', ['diproses', 'dikirim', 'selesai'])->sum('total_bayar');
        $pcsHariIni = OrderItem::whereHas('order', fn ($q) => $q->whereDate('created_at', $today))->sum('qty');
        $targetHarian = (int) Setting::get('target_harian', 1000000);
        $progressPersen = $targetHarian > 0 ? min(100, round($omzetHariIni / $targetHarian * 100)) : 0;

        // 7 hari terakhir untuk grafik bar CSS
        $grafik = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i);
            $grafik[] = [
                'label' => $tgl->format('d/m'),
                'order' => Order::whereDate('created_at', $tgl->toDateString())->count(),
                'omzet' => Order::whereDate('created_at', $tgl->toDateString())->whereIn('status', ['diproses', 'dikirim', 'selesai'])->sum('total_bayar'),
            ];
        }
        $maxOmzet = max(1, collect($grafik)->max('omzet'));

        // Produk yang totalnya selisih dari jumlah varian
        $selisih = Product::withSum('variants', 'stok')->get()
            ->filter(fn ($p) => (int) $p->stok !== (int) $p->variants_sum_stok)->count();

        // Upaya login gagal 24 jam terakhir (pantau robot/brute force)
        $gagalLogin = ActivityLog::where('aksi', 'login_gagal')
            ->where('created_at', '>=', now()->subDay())->count();

        return web_view('admin-dashboard', compact('totalProduk', 'stokMenipis', 'orderBaru', 'omzet', 'orders', 'orderHariIni', 'omzetHariIni', 'pcsHariIni', 'targetHarian', 'progressPersen', 'grafik', 'maxOmzet', 'selisih', 'gagalLogin'));
    }

    public function products(Request $r)
    {
        $r->validate([
            'q' => 'nullable|string|max:50',
            'filter' => 'nullable|in:semua,kritis,habis,nonaktif',
            'sort' => 'nullable|in:menipis,terlaris,nama,stok',
            'view' => 'nullable|in:tabel,kartu',
        ]);
        $q = $r->get('q');
        $filter = $r->get('filter', 'semua');
        $sort = $r->get('sort', 'menipis');
        $view = $r->get('view', 'tabel');
        if (! in_array($view, ['tabel', 'kartu'])) {
            $view = 'tabel';
        }

        $products = Product::with(['variants', 'prices'])->latest()->get();
        $terjual = OrderItem::selectRaw('product_id, SUM(qty) t')
            ->whereHas('order', fn ($x) => $x->whereIn('status', ['diproses', 'dikirim', 'selesai'])->where('created_at', '>=', now()->subDays(30)))
            ->groupBy('product_id')->pluck('t', 'product_id');
        foreach ($products as $p) {
            $p->terjual30 = (int) ($terjual[$p->id] ?? 0);
            $p->laju = $p->terjual30 > 0 ? $p->terjual30 / 30 : 0;
            $p->hari_habis = $p->laju > 0 ? (int) floor($p->stok / $p->laju) : null;
            $p->sinyal = $p->stok <= 0 ? 'habis' : ($p->hari_habis !== null && $p->hari_habis < 14 ? 'segera' : ($p->stok <= 10 ? 'kritis' : ($p->terjual30 === 0 ? 'diam' : 'aman')));
        }
        $ringkas = [
            'sku' => $products->where('is_active', true)->count(),
            'pcs' => $products->where('is_active', true)->sum('stok'),
            'nilai' => $products->where('is_active', true)->sum(fn ($p) => $p->stok * $p->harga_reseller),
            'kritis' => $products->where('is_active', true)->filter(fn ($p) => $p->stok > 0 && $p->stok <= 10)->count(),
            'habis' => $products->where('is_active', true)->where('stok', '<=', 0)->count(),
        ];
        $tampil = $products
            ->when($q, fn ($c) => $c->filter(fn ($p) => stripos($p->nama.' '.($p->variants->pluck('sku')->join(' ')), $q) !== false))
            ->when($filter === 'kritis', fn ($c) => $c->filter(fn ($p) => $p->is_active && $p->stok > 0 && $p->stok <= 10))
            ->when($filter === 'habis', fn ($c) => $c->filter(fn ($p) => $p->is_active && $p->stok <= 0))
            ->when($filter === 'nonaktif', fn ($c) => $c->filter(fn ($p) => ! $p->is_active));
        $tampil = match ($sort) {
            'terlaris' => $tampil->sortByDesc('terjual30')->values(),
            'nama' => $tampil->sortBy('nama')->values(),
            'stok' => $tampil->sortByDesc('stok')->values(),
            default => $tampil->sortBy('stok')->values(),
        };

        $perPage = 10;
        $page = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $paginatedProducts = new \Illuminate\Pagination\LengthAwarePaginator(
            $tampil->forPage($page, $perPage)->values(),
            $tampil->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(), 'query' => $r->query()]
        );
        $products = $paginatedProducts;

        return web_view('admin-products', compact('products', 'ringkas', 'q', 'filter', 'sort', 'view'));
    }

    public function productStore(Request $r)
    {
        $r->validate([
            'nama' => 'required|max:100', 'deskripsi' => 'nullable',
            'berat_gram' => 'required|integer|min:1',
            'harga_enduser' => 'required|integer|min:0', 'harga_reseller' => 'required|integer|min:0',
            'min_reseller' => 'nullable|integer|min:1', 'foto' => 'nullable|image|max:2048',
            'ukuran_list' => 'nullable|max:100', 'warna' => 'nullable|max:30',
            'stok_awal' => 'required|integer|min:0', 'kategori' => 'nullable|max:50',
            'panjang_cm' => 'nullable|integer|min:0', 'lebar_cm' => 'nullable|integer|min:0', 'tinggi_cm' => 'nullable|integer|min:0',
        ]);

        return DB::transaction(function () use ($r) {
            $data = $r->only('nama', 'deskripsi', 'berat_gram', 'harga_enduser', 'harga_reseller');
            $data += ['panjang_cm' => $r->panjang_cm ?? 0, 'lebar_cm' => $r->lebar_cm ?? 0, 'tinggi_cm' => $r->tinggi_cm ?? 0];
            $data['kategori'] = trim($r->kategori ?: 'Fashion');
            $data['stok'] = 0;
            $data['min_reseller'] = $r->input('min_reseller', 6);
            $data['is_active'] = $r->has('is_active') ? (bool) $r->is_active : true;
            if ($r->hasFile('foto')) {
                $data['foto'] = $r->file('foto')->store('produk', 'public');
            }
            $p = Product::create($data);
            $ukurans = array_filter(array_map('trim', explode(',', $r->input('ukuran_list', 'All Size') ?: 'All Size')));
            $warna = trim($r->input('warna', '-') ?: '-');
            foreach ($ukurans as $i => $uk) {
                $v = $p->variants()->create([
                    'ukuran' => substr($uk, 0, 20), 'warna' => substr($warna, 0, 30),
                    'stok' => (int) $r->stok_awal, 'sku' => 'BB-'.$p->id.'-'.($i + 1),
                ]);
                StockMutation::create(['product_id' => $p->id, 'variant_id' => $v->id, 'tipe' => 'masuk', 'qty' => $v->stok, 'stok_sebelum' => 0, 'stok_sesudah' => $v->stok, 'keterangan' => 'Stok awal: '.($r->keterangan ?? 'Tambah barang baru oleh admin')]);
            }
            $p->syncStok();
            ActivityLog::catat('produk_tambah', $p->nama, count($ukurans).' varian', 'produk', null, '/admin/produk');

            return back()->with('ok', 'Barang baru "'.$p->nama.'" + '.count($ukurans).' varian ditambahkan ✅');
        });
    }

    public function productUpdate(Request $r, Product $product)
    {
        $r->validate([
            'nama' => 'sometimes|max:100', 'deskripsi' => 'nullable',
            'harga_enduser' => 'required|integer|min:0', 'harga_reseller' => 'required|integer|min:0',
            'berat_gram' => 'nullable|integer|min:1', 'min_reseller' => 'nullable|integer|min:1',
            'foto' => 'nullable|image|max:2048', 'kategori' => 'nullable|max:50',
            'panjang_cm' => 'nullable|integer|min:0', 'lebar_cm' => 'nullable|integer|min:0', 'tinggi_cm' => 'nullable|integer|min:0',
        ]);
        $data = $r->only('nama', 'deskripsi', 'harga_enduser', 'harga_reseller', 'berat_gram', 'min_reseller', 'kategori', 'panjang_cm', 'lebar_cm', 'tinggi_cm');
        $data['is_active'] = $r->has('is_active') ? (bool) $r->input('is_active') : $product->is_active;
        if ($r->hasFile('foto')) {
            $data['foto'] = $r->file('foto')->store('produk', 'public');
        }
        $product->update(array_filter($data, fn ($v) => ! is_null($v)));
        ActivityLog::catat('produk_ubah', $product->nama, null, 'produk', null, '/admin/produk');

        return back()->with('ok', 'Barang "'.$product->nama.'" diperbarui ✅');
    }

    public function variantStore(Request $r, Product $product)
    {
        $r->validate(['ukuran' => 'required|max:20', 'warna' => 'nullable|max:30', 'stok' => 'required|integer|min:0', 'keterangan' => 'nullable|max:200']);

        return DB::transaction(function () use ($r, $product) {
            $v = $product->variants()->create([
                'ukuran' => $r->ukuran, 'warna' => $r->warna ?: '-',
                'stok' => (int) $r->stok, 'sku' => 'BB-'.$product->id.'-'.($product->variants()->count() + 1),
            ]);
            StockMutation::create(['product_id' => $product->id, 'variant_id' => $v->id, 'tipe' => 'masuk', 'qty' => $v->stok, 'stok_sebelum' => 0, 'stok_sesudah' => $v->stok, 'keterangan' => $r->keterangan ?: 'Varian baru oleh admin']);
            $product->syncStok();
            ActivityLog::catat('varian_tambah', $product->nama, $v->label(), 'stok', null, '/admin/produk');

            return back()->with('ok', 'Varian '.$v->label().' ditambahkan ✅');
        });
    }

    public function variantUpdate(Request $r, ProductVariant $variant)
    {
        $r->validate(['stok' => 'required|integer|min:0', 'keterangan' => 'nullable|max:200']);

        return DB::transaction(function () use ($r, $variant) {
            $sebelum = $variant->stok;
            $variant->update(['stok' => (int) $r->stok]);
            if ((int) $variant->stok !== (int) $sebelum) {
                StockMutation::create([
                    'product_id' => $variant->product_id, 'variant_id' => $variant->id,
                    'tipe' => $variant->stok >= $sebelum ? 'masuk' : 'keluar',
                    'qty' => abs($variant->stok - $sebelum), 'stok_sebelum' => $sebelum,
                    'stok_sesudah' => $variant->stok, 'keterangan' => $r->keterangan ?: 'Update stok varian oleh admin',
                ]);
                $variant->product->syncStok();
                ActivityLog::catat('varian_ubah', $variant->product->nama ?? '', $variant->label().': '.$sebelum.' → '.$variant->stok, 'stok', null, '/admin/produk');
            }

            return back()->with('ok', 'Stok '.$variant->label().': '.$sebelum.' → '.$variant->stok.' ✅');
        });
    }

    public function variantDestroy(ProductVariant $variant)
    {
        if (OrderItem::where('variant_id', $variant->id)->exists()) {
            return back()->withErrors(['varian' => 'Varian '.$variant->label().' sudah pernah terjual, tidak bisa dihapus. Nolkan stoknya saja.']);
        }
        $label = $variant->label();
        $product = $variant->product;
        $variant->delete();
        $product->syncStok();
        ActivityLog::catat('varian_hapus', $product->nama ?? '', $label, 'stok', null, '/admin/produk');

        return back()->with('ok', 'Varian '.$label.' dihapus.');
    }

    public function productDestroy(Product $product)
    {
        Cart::where('product_id', $product->id)->delete();
        if (OrderItem::where('product_id', $product->id)->exists()) {
            $product->update(['is_active' => false]);
            ActivityLog::catat('produk_nonaktif', $product->nama, 'Pernah terjual', 'produk', null, '/admin/produk');

            return back()->with('ok', '"'.$product->nama.'" dinonaktifkan (sudah pernah terjual, tidak dihapus).');
        }
        $nama = $product->nama;
        $product->delete();
        ActivityLog::catat('produk_hapus', $nama, null, 'produk', null, '/admin/produk');

        return back()->with('ok', 'Barang dihapus.');
    }

    public function productToggleActive(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);
        if (! $product->is_active) {
            Cart::where('product_id', $product->id)->delete();
        }
        $status = $product->is_active ? 'diaktifkan (ON)' : 'dinonaktifkan (OFF)';
        ActivityLog::catat('produk_status', $product->nama, $status, 'produk', null, '/admin/produk');

        return back()->with('ok', 'Barang "'.$product->nama.'" '.$status.' ✅');
    }

    public function productDuplicate(Product $product)
    {
        return DB::transaction(function () use ($product) {
            $product->load('variants', 'prices');
            $baru = $product->replicate(['stok']);
            $baru->nama = $product->nama.' (Copy)';
            $baru->stok = 0;
            $baru->is_active = false;
            $baru->foto = null;
            $baru->push();
            foreach ($product->variants as $i => $v) {
                $nv = $v->replicate();
                $nv->product_id = $baru->id;
                $nv->stok = 0;
                $nv->sku = 'BB-'.$baru->id.'-C'.($i + 1);
                $nv->save();
            }
            foreach ($product->prices as $t) {
                $nt = $t->replicate();
                $nt->product_id = $baru->id;
                $nt->save();
            }

            return back()->with('ok', 'Diduplikat sebagai "'.$baru->nama.'" (nonaktif, stok 0). Aktifkan setelah dicek ✅');
        });
    }

    public function productArsipMassal(Request $r)
    {
        $r->validate(['ids' => 'required|array|min:1', 'ids.*' => 'exists:products,id']);
        Cart::whereIn('product_id', $r->ids)->delete();
        $n = Product::whereIn('id', $r->ids)->update(['is_active' => false]);

        return back()->with('ok', "$n barang diarsipkan (nonaktif) ✅");
    }

    public function orders(Request $r)
    {
        $q = $r->get('q');
        $status = $r->get('status');
        $orders = Order::with('user', 'items.product', 'items.variant')
            ->when($q, fn ($query) => $query->where(fn ($w) => $w->where('kode', 'like', "%$q%")->orWhere('resi', 'like', "%$q%")->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%$q%")->orWhere('phone', 'like', "%$q%"))))
            ->when($status && $status !== 'semua', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return web_view('admin-orders', compact('orders', 'q', 'status'));
    }

    public function orderBersihkanKedaluwarsa(Request $r)
    {
        $jam = max(1, (int) $r->input('jam', 24));
        $n = Order::batalkanKedaluwarsa($jam);

        return back()->with('ok', $n > 0 ? "$n pesanan kedaluwarsa dibatalkan, stok dikembalikan ✅" : 'Tidak ada pesanan kedaluwarsa ✅');
    }

    public function orderUpdate(Request $r, Order $order)
    {
        $r->validate(['status' => 'required|in:menunggu_bayar,diproses,dikirim,selesai,batal', 'resi' => 'nullable|max:50']);
        // No resi manual (tanpa API kurir): wajib isi saat status dikirim
        if ($r->status === 'dikirim' && ! trim((string) $r->resi)) {
            return back()->withErrors(['resi' => 'No resi wajib diisi manual saat status DIKIRIM (salin dari JNE/J&T/SPX).'])->withInput();
        }
        if ($r->status === 'batal' && ! in_array($order->status, ['menunggu_bayar', 'diproses'], true)) {
            return back()->withErrors(['status' => 'Order yang sudah dikirim/selesai tidak bisa dibatalkan.']);
        }
        $oldStatus = $order->status;
        DB::transaction(function () use ($r, $order) {
            if ($r->status === 'batal' && $order->status !== 'batal') {
                $order->load('items');
                foreach ($order->items as $it) {
                    $p = Product::find($it->product_id);
                    if (! $p) {
                        continue;
                    }
                    StockMutation::create(['product_id' => $p->id, 'variant_id' => $it->variant_id, 'tipe' => 'masuk', 'qty' => $it->qty, 'stok_sebelum' => $p->stok, 'stok_sesudah' => $p->stok + $it->qty, 'keterangan' => 'Batal '.$order->kode]);
                    $p->increment('stok', $it->qty);
                    if ($it->variant_id) {
                        ProductVariant::where('id', $it->variant_id)->increment('stok', $it->qty);
                    }
                }
                if ($order->voucher_id) {
                    Voucher::where('id', $order->voucher_id)->where('terpakai', '>', 0)->decrement('terpakai');
                }
            }
            $data = ['status' => $r->status];
            if ($r->filled('resi')) {
                $data['resi'] = $r->resi;
            }
            $order->update($data);
        });
        if ($r->status !== $oldStatus) {
            WaNotifier::statusOrder($order);
            ActivityLog::catat('order_status', $order->kode, $oldStatus.' -> '.$r->status, 'order', null, '/admin/order/'.$order->id);
        }

        return back()->with('ok', 'Order '.$order->kode.' -> '.$r->status);
    }

    public function orderDetail(Order $order)
    {
        $order->load('items.product', 'items.variant', 'user', 'voucher');

        return web_view('admin-order-detail', compact('order'));
    }

    public function label(Order $order)
    {
        $order->load('items.product', 'items.variant');

        return view('admin-label', compact('order'));
    }

    public function suratJalan(Order $order)
    {
        $order->load('items.product', 'items.variant', 'user');
        $toko = [
            'nama' => Setting::get('nama_toko', 'Bubee'),
            'wa' => Setting::get('wa_admin', ''),
        ];

        return view('admin-surat-jalan', compact('order', 'toko'));
    }

    public function struk(Order $order)
    {
        $order->load('items.product', 'items.variant', 'user', 'voucher');

        return view('admin-struk', compact('order'));
    }

    public function laporan(Request $r)
    {
        $r->validate([
            'dari' => 'nullable|date|before_or_equal:today',
            'sampai' => 'nullable|date|before_or_equal:today',
            'tab' => 'nullable|in:periode,harian',
            'tanggal' => 'nullable|date|before_or_equal:today',
            'periode' => 'nullable|in:harian,mingguan,bulanan,kustom',
            'minggu' => 'nullable|date|before_or_equal:today',
            'bulan' => 'nullable|string',
        ]);

        $periode = $r->get('periode');
        if (! $periode) {
            if ($r->get('tab') === 'harian') {
                $periode = 'harian';
            } elseif ($r->filled('dari') && $r->filled('sampai')) {
                $periode = 'kustom';
            } else {
                $periode = 'bulanan';
            }
        }

        $today = now()->toDateString();
        $tanggal = $r->get('tanggal', $today);
        if ($tanggal > $today) {
            $tanggal = $today;
        }

        $minggu = $r->get('minggu', $today);
        if ($minggu > $today) {
            $minggu = $today;
        }

        $bulan = $r->get('bulan', now()->format('Y-m'));
        if (! preg_match('/^\d{4}-\d{2}$/', (string) $bulan)) {
            $bulan = now()->format('Y-m');
        }

        $labelPeriode = '';
        $lunas = ['diproses', 'dikirim', 'selesai'];

        if ($periode === 'harian') {
            $dari = $tanggal;
            $sampai = $tanggal;
            $labelPeriode = 'Harian: '.\Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y');
        } elseif ($periode === 'mingguan') {
            $ref = \Carbon\Carbon::parse($minggu);
            $dari = $ref->copy()->startOfWeek()->toDateString();
            $sampai = $ref->copy()->endOfWeek()->toDateString();
            $labelPeriode = 'Mingguan: '.\Carbon\Carbon::parse($dari)->translatedFormat('d M').' - '.\Carbon\Carbon::parse($sampai)->translatedFormat('d M Y');
        } elseif ($periode === 'bulanan') {
            $ref = \Carbon\Carbon::parse($bulan.'-01');
            $dari = $ref->copy()->startOfMonth()->toDateString();
            $sampai = $ref->copy()->endOfMonth()->toDateString();
            $labelPeriode = 'Bulanan: '.\Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y');
        } else {
            // kustom
            $sampai = $r->get('sampai', $today);
            $dari = $r->get('dari', now()->subDays(29)->toDateString());
            if ($dari > $sampai) {
                [$dari, $sampai] = [$sampai, $dari];
            }
            // Batasi 62 hari agar ringan
            if (now()->parse($dari)->diffInDays(now()->parse($sampai)) > 61) {
                $dari = now()->parse($sampai)->subDays(61)->toDateString();
            }
            $labelPeriode = 'Periode: '.$dari.' s/d '.$sampai;
        }

        $dalam = fn ($q) => $q->whereDate('orders.created_at', '>=', $dari)->whereDate('orders.created_at', '<=', $sampai);

        $base = (clone $dalam)(Order::query());
        $ringkas = [
            'omzet' => (clone $base)->whereIn('status', $lunas)->sum('total_bayar'),
            'jml_order' => (clone $base)->whereIn('status', $lunas)->count(),
            'diskon' => (clone $base)->whereIn('status', $lunas)->sum('diskon'),
            'batal_jml' => (clone $base)->where('status', 'batal')->count(),
            'batal_nominal' => (clone $base)->where('status', 'batal')->sum('total_bayar'),
            'tunggu_jml' => (clone $base)->where('status', 'menunggu_bayar')->count(),
            'tunggu_nominal' => (clone $base)->where('status', 'menunggu_bayar')->sum('total_bayar'),
        ];
        $ringkas['pcs'] = OrderItem::whereHas('order', fn ($q) => $dalam($q)->whereIn('status', $lunas))->sum('qty');
        $ringkas['aov'] = $ringkas['jml_order'] > 0 ? (int) round($ringkas['omzet'] / $ringkas['jml_order']) : 0;
        $ringkas['retur_aktif'] = Retur::whereHas('order', fn ($q) => $dalam($q))->whereIn('status', ['diajukan', 'disetujui'])->count();

        $perStatus = (clone $base)->selectRaw('status, COUNT(*) jml, SUM(total_bayar) total')->groupBy('status')->get();
        $perKurir = (clone $base)->whereIn('status', $lunas)
            ->selectRaw('kurir, COUNT(*) jml, SUM(total_bayar) total')->groupBy('kurir')->orderByDesc('total')->get();
        $terlaris = OrderItem::selectRaw('order_items.product_id, SUM(order_items.qty) terjual, SUM(order_items.subtotal) omzet')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where(fn ($q) => $dalam($q))->whereIn('orders.status', $lunas)
            ->with('product')->groupBy('order_items.product_id')->orderByDesc('terjual')->take(10)->get();
        $topPembeli = (clone $base)->whereIn('status', $lunas)
            ->selectRaw('user_id, COUNT(*) jml, SUM(total_bayar) total')->with('user')
            ->groupBy('user_id')->orderByDesc('total')->take(5)->get();
        $topVoucher = (clone $base)->whereIn('status', $lunas)->whereNotNull('voucher_id')
            ->selectRaw('voucher_id, COUNT(*) dipakai, SUM(diskon) hemat')->with('voucher')
            ->groupBy('voucher_id')->orderByDesc('dipakai')->take(5)->get();

        $perHari = (clone $base)
            ->selectRaw('DATE(created_at) tgl, COUNT(*) jml, SUM(CASE WHEN status IN (\'diproses\',\'dikirim\',\'selesai\') THEN total_bayar ELSE 0 END) omzet')
            ->groupBy('tgl')->pluck('omzet', 'tgl');
        $pcsHari = OrderItem::selectRaw('DATE(orders.created_at) tgl, SUM(order_items.qty) pcs')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where(fn ($q) => $dalam($q))->whereIn('orders.status', $lunas)
            ->groupBy('tgl')->pluck('pcs', 'tgl');
        $jmlHari = (clone $base)->selectRaw('DATE(created_at) tgl, COUNT(*) jml')->groupBy('tgl')->pluck('jml', 'tgl');

        $harian = [];
        $d = now()->parse($dari);
        $s = now()->parse($sampai);
        while ($d->lte($s)) {
            $key = $d->toDateString();
            $harian[] = [
                'tgl' => $d->format('d M'),
                'hari' => $d->translatedFormat('l'),
                'full_date' => $key,
                'order' => (int) ($jmlHari[$key] ?? 0),
                'pcs' => (int) ($pcsHari[$key] ?? 0),
                'omzet' => (int) ($perHari[$key] ?? 0),
            ];
            $d->addDay();
        }
        $maxOmzet = max(1, collect($harian)->max('omzet'));

        $mutasi = StockMutation::with('product', 'variant')->latest()->take(10)->get();
        $generatedAt = now();
        $tab = ($periode === 'harian' || $r->get('tab') === 'harian') ? 'harian' : 'periode';

        // Rekap orders list
        $rekOrdersQuery = Order::with('items.product', 'user')
            ->whereDate('created_at', '>=', $dari)
            ->whereDate('created_at', '<=', $sampai)
            ->latest();
        if ($periode === 'harian') {
            $rekOrders = $rekOrdersQuery->get();
        } else {
            $rekOrders = $rekOrdersQuery->take(50)->get();
        }

        $bayarH = $rekOrders->whereIn('status', $lunas);
        $rek = [
            'tanggal' => $tanggal,
            'jml_order' => $ringkas['jml_order'],
            'omzet' => $ringkas['omzet'],
            'pcs' => $ringkas['pcs'],
            'per_kurir' => $rekOrders->groupBy('kurir')->map(fn ($g) => ['jml' => $g->count(), 'total' => $g->whereIn('status', $lunas)->sum('total_bayar')]),
            'per_status' => $rekOrders->groupBy('status')->map->count(),
        ];

        return web_view('admin-laporan', compact(
            'periode', 'tanggal', 'minggu', 'bulan', 'dari', 'sampai', 'labelPeriode',
            'ringkas', 'perStatus', 'perKurir', 'terlaris', 'topPembeli', 'topVoucher',
            'harian', 'maxOmzet', 'mutasi', 'generatedAt', 'tab', 'rek', 'rekOrders'
        ));
    }

    public function resellers(Request $r)
    {
        $r->validate(['status' => 'nullable|in:semua,pending,approved,rejected']);
        $status = $r->get('status', 'pending');
        $resellers = User::where('role', 'reseller')
            ->when($status !== 'semua', fn ($q) => $q->where('status_reseller', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return web_view('admin-resellers', compact('resellers', 'status'));
    }
    /** Tampilkan foto KTP privat. Hanya admin (route sudah middleware auth+admin). */
    public function ktpFile(User $user)
    {
        $rel = (string) ($user->ktp_foto ?? '');
        if ($rel === '' || ! str_starts_with($rel, 'ktp/') || str_contains($rel, '..')) {
            abort(404);
        }
        $path = Storage::disk('local')->path($rel);
        if (! is_file($path)) {
            $path = storage_path('app/'.$rel);
            if (! is_file($path)) {
                abort(404);
            }
        }
        $mime = (string) mime_content_type($path);
        if (! str_starts_with($mime, 'image/')) {
            abort(403);
        }

        return response()->file($path);
    }

    public function resellerApprove(User $user)
    {
        if ($user->role !== 'reseller' || $user->status_reseller !== 'pending') {
            return back()->withErrors(['reseller' => 'Hanya pengajuan pending yang bisa disetujui.']);
        }
        $kurang = $user->kekuranganReseller();
        if ($kurang !== []) {
            ActivityLog::catat('reseller_approve_gagal', $user->email, 'Data kurang: '.implode(', ', $kurang), 'user', null, '/admin/reseller?status=pending');

            return back()->withErrors(['reseller' => $user->name.' belum bisa disetujui, kurang: '.implode(', ', $kurang).'.']);
        }
        $user->update(['status_reseller' => 'approved']);
        WaNotifier::reseller($user->fresh(), true);
        ActivityLog::catat('reseller_approve', $user->email, $user->name.' disetujui'.($user->ktp_foto ? '' : ' (tanpa foto KTP)'), 'user', null, '/admin/reseller?status=approved');

        return back()->with('ok', $user->name.' disetujui jadi reseller');
    }

    public function resellerReject(Request $r, User $user)
    {
        $r->validate(['alasan' => 'required|string|min:10|max:200']);
        if ($user->role !== 'reseller' || $user->status_reseller !== 'pending') {
            return back()->withErrors(['reseller' => 'Hanya pengajuan pending yang bisa ditolak.']);
        }
        $user->update(['status_reseller' => 'rejected']);
        WaNotifier::reseller($user->fresh(), false, $r->alasan);
        ActivityLog::catat('reseller_reject', $user->email, $user->name.' ditolak: '.$r->alasan, 'user', null, '/admin/reseller?status=rejected');

        return back()->with('ok', $user->name.' ditolak');
    }

    public function resellerReviewUlang(User $user)
    {
        if ($user->role !== 'reseller' || $user->status_reseller !== 'rejected') {
            return back()->withErrors(['reseller' => 'Hanya yang ditolak yang bisa dikembalikan ke pending.']);
        }
        $user->update(['status_reseller' => 'pending']);
        ActivityLog::catat('reseller_review_ulang', $user->email, $user->name.' dikembalikan ke antrean review', 'user', null, '/admin/reseller?status=pending');

        return back()->with('ok', $user->name.' dikembalikan ke antrean review');
    }

    // ===== KHUSUS ADMIN: Voucher =====
    public function vouchers()
    {
        $vouchers = Voucher::latest()->get();

        return web_view('admin-vouchers', compact('vouchers'));
    }

    public function voucherStore(Request $r)
    {
        $r->validate([
            'kode' => 'required|max:20|unique:vouchers,kode', 'nama' => 'nullable|max:100',
            'tipe' => 'required|in:persen,nominal', 'nilai' => 'required|integer|min:1',
            'maks_diskon' => 'nullable|integer|min:0', 'min_belanja' => 'nullable|integer|min:0',
            'kuota' => 'required|integer|min:1', 'khusus' => 'required|in:semua,reseller,enduser',
            'berlaku_sampai' => 'nullable|date',
        ]);
        Voucher::create([
            'kode' => strtoupper(trim($r->kode)), 'nama' => $r->nama, 'tipe' => $r->tipe,
            'nilai' => $r->nilai, 'maks_diskon' => $r->maks_diskon ?? 0, 'min_belanja' => $r->min_belanja ?? 0,
            'kuota' => $r->kuota, 'khusus' => $r->khusus, 'is_active' => $r->has('is_active'),
            'berlaku_sampai' => $r->berlaku_sampai,
        ]);
        ActivityLog::catat('voucher_buat', strtoupper(trim($r->kode)), null, 'sistem', null, '/admin/voucher');

        return back()->with('ok', 'Voucher '.strtoupper($r->kode).' dibuat 🎟️');
    }

    public function voucherUpdate(Request $r, Voucher $voucher)
    {
        $r->validate([
            'nama' => 'nullable|max:100', 'nilai' => 'required|integer|min:1',
            'maks_diskon' => 'nullable|integer|min:0', 'min_belanja' => 'nullable|integer|min:0',
            'kuota' => 'required|integer|min:0', 'berlaku_sampai' => 'nullable|date',
        ]);
        $voucher->update([
            'nama' => $r->nama, 'nilai' => $r->nilai,
            'maks_diskon' => $r->maks_diskon ?? 0, 'min_belanja' => $r->min_belanja ?? 0,
            'kuota' => $r->kuota, 'is_active' => $r->has('is_active'),
            'berlaku_sampai' => $r->berlaku_sampai,
        ]);
        ActivityLog::catat('voucher_ubah', $voucher->kode, null, 'sistem', null, '/admin/voucher');

        return back()->with('ok', 'Voucher '.$voucher->kode.' diupdate');
    }

    public function voucherDestroy(Voucher $voucher)
    {
        if (Order::where('voucher_id', $voucher->id)->exists()) {
            $voucher->update(['is_active' => false]);
            ActivityLog::catat('voucher_nonaktif', $voucher->kode, 'Pernah dipakai', 'sistem', null, '/admin/voucher');

            return back()->with('ok', 'Voucher sudah pernah dipakai → dinonaktifkan.');
        }
        $kode = $voucher->kode;
        $voucher->delete();
        ActivityLog::catat('voucher_hapus', $kode, null, 'sistem', null, '/admin/voucher');

        return back()->with('ok', 'Voucher dihapus.');
    }

    // ===== KHUSUS ADMIN: Verifikasi Pembayaran =====
    public function verifikasi()
    {
        $orders = Order::with('user', 'items.product', 'items.variant')
            ->whereNotNull('bukti_bayar')
            ->where('status', 'diproses')
            ->latest()
            ->paginate(15)
            ->withQueryString();
        $menunggu = Order::where('status', 'menunggu_bayar')->count();

        return web_view('admin-verifikasi', compact('orders', 'menunggu'));
    }

    public function verifikasiSetuju(Request $r, Order $order)
    {
        abort_unless($order->status === 'diproses' && $order->bukti_bayar, 400);
        $r->validate(['resi' => 'required|max:50']);
        $order->update(['status' => 'dikirim', 'resi' => $r->resi]);
        WaNotifier::statusOrder($order);
        ActivityLog::catat('verifikasi_setuju', $order->kode, 'Resi '.$r->resi, 'order', null, '/admin/order/'.$order->id);

        return back()->with('ok', $order->kode.' terverifikasi → status DIKIRIM (resi '.$r->resi.').');
    }

    public function verifikasiTolak(Order $order)
    {
        abort_unless($order->status === 'diproses' && $order->bukti_bayar, 400);
        DB::transaction(function () use ($order) {
            $order->load('items');
            foreach ($order->items as $it) {
                $p = Product::find($it->product_id);
                if ($p) {
                    StockMutation::create(['product_id' => $p->id, 'variant_id' => $it->variant_id, 'tipe' => 'masuk', 'qty' => $it->qty, 'stok_sebelum' => $p->stok, 'stok_sesudah' => $p->stok + $it->qty, 'keterangan' => 'Bukti ditolak '.$order->kode]);
                    $p->increment('stok', $it->qty);
                    if ($it->variant_id) {
                        ProductVariant::where('id', $it->variant_id)->increment('stok', $it->qty);
                    }
                }
            }
            if ($order->voucher_id) {
                Voucher::where('id', $order->voucher_id)->where('terpakai', '>', 0)->decrement('terpakai');
            }
            $order->update(['status' => 'batal']);
        });
        WaNotifier::statusOrder($order->fresh());
        ActivityLog::catat('verifikasi_tolak', $order->kode, 'Bukti ditolak, stok dikembalikan', 'order', null, '/admin/order/'.$order->id);

        return back()->with('ok', $order->kode.' ditolak, stok dikembalikan.');
    }

    // ===== KHUSUS ADMIN: Kelola Semua Pengguna =====
    public function users(Request $r)
    {
        $r->validate(['q' => 'nullable|string|max:50']);
        $q = $r->get('q');
        $users = User::when($q, fn ($x) => $x->where(function ($y) use ($q) {
            $y->where('name', 'like', "%$q%")->orWhere('email', 'like', "%$q%");
        }))
            ->latest()->paginate(20)->withQueryString();

        return web_view('admin-users', compact('users', 'q'));
    }

    public function userRole(Request $r, User $user)
    {
        $r->validate(['role' => 'required|in:admin,reseller,enduser']);
        if ($user->id === auth()->id() && $r->role !== 'admin') {
            return back()->withErrors(['role' => 'Tidak bisa menurunkan role diri sendiri!']);
        }
        $lama = $user->role;
        $user->update([
            'role' => $r->role,
            'status_reseller' => $r->role === 'reseller' ? ($user->status_reseller === 'none' ? 'pending' : $user->status_reseller) : 'none',
        ]);
        ActivityLog::catat('user_role', $user->email, $lama.' -> '.$r->role, 'user', null, '/admin/users');

        return back()->with('ok', $user->name.' → '.$r->role);
    }

    public function userReset(User $user)
    {
        $user->update(['password' => Hash::make('bubee123')]);
        ActivityLog::catat('user_reset', $user->email, 'Password direset admin', 'user', null, '/admin/users');

        return back()->with('ok', 'Password '.$user->name.' direset → bubee123');
    }

    public function userDestroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'Tidak bisa hapus diri sendiri!']);
        }
        if (Order::where('user_id', $user->id)->exists()) {
            return back()->withErrors(['user' => 'User sudah punya order, tidak bisa dihapus.']);
        }
        $email = $user->email;
        $user->delete();
        ActivityLog::catat('user_hapus', $email, null, 'user', null, '/admin/users');

        return back()->with('ok', 'User dihapus.');
    }

    // ===== KHUSUS ADMIN: Barang Keluar Manual (rusak/hilang/sample) =====
    public function stokKeluarForm()
    {
        $products = Product::with('variants')->where('is_active', true)->orderBy('nama')->get();

        return web_view('admin-stok-keluar', compact('products'));
    }

    public function stokKeluarStore(Request $r)
    {
        $r->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'qty' => 'required|integer|min:1|max:100000',
            'alasan' => 'required|in:rusak,hilang,sample,lainnya',
            'catatan' => 'nullable|max:200',
        ]);

        return DB::transaction(function () use ($r) {
            $v = ProductVariant::lockForUpdate()->find($r->variant_id);
            if (! $v || $v->stok < (int) $r->qty) {
                return back()->withErrors(['qty' => 'Stok '.($v?->label() ?? '').' tidak cukup (sisa '.($v->stok ?? 0).').'])->withInput();
            }
            $sebelum = $v->stok;
            $v->decrement('stok', (int) $r->qty);
            StockMutation::create([
                'product_id' => $v->product_id, 'variant_id' => $v->id,
                'tipe' => 'keluar', 'qty' => (int) $r->qty,
                'stok_sebelum' => $sebelum, 'stok_sesudah' => $sebelum - (int) $r->qty,
                'keterangan' => 'Keluar manual ['.$r->alasan.']'.($r->catatan ? ' '.$r->catatan : ''),
            ]);
            $v->product->syncStok();
            ActivityLog::catat('stok_keluar', $v->product->nama ?? '', $v->label().' −'.$r->qty.' ['.$r->alasan.']', 'stok', null, '/admin/mutasi');

            return redirect('/admin/mutasi')->with('ok', 'Keluar '.$r->qty.' pcs ('.$v->label().') tercatat ✅');
        });
    }

    // ===== KHUSUS ADMIN: Riwayat Mutasi Stok =====
    public function mutasi(Request $r)
    {
        $r->validate(['q' => 'nullable|string|max:50']);
        $q = $r->get('q');
        $mutasi = StockMutation::with('product', 'variant')
            ->when($q, fn ($x) => $x->where('keterangan', 'like', "%$q%"))
            ->latest()->paginate(25)->withQueryString();

        return web_view('admin-mutasi', compact('mutasi', 'q'));
    }

    // ===== KHUSUS ADMIN: Kelola FAQ Bantuan =====
    public function faqs()
    {
        $faqs = Faq::orderBy('urutan')->orderBy('id')->get();

        return web_view('admin-faq', compact('faqs'));
    }

    public function faqStore(Request $r)
    {
        $r->validate([
            'topik' => 'required|max:30', 'pertanyaan' => 'required|max:200',
            'jawaban' => 'required', 'urutan' => 'nullable|integer|min:0',
        ]);
        Faq::create([
            'topik' => $r->topik, 'pertanyaan' => $r->pertanyaan, 'jawaban' => $r->jawaban,
            'urutan' => $r->urutan ?? 0, 'is_active' => $r->has('is_active'),
        ]);

        return back()->with('ok', 'FAQ ditambahkan ✅');
    }

    public function faqUpdate(Request $r, Faq $faq)
    {
        $r->validate([
            'topik' => 'required|max:30', 'pertanyaan' => 'required|max:200',
            'jawaban' => 'required', 'urutan' => 'nullable|integer|min:0',
        ]);
        $faq->update([
            'topik' => $r->topik, 'pertanyaan' => $r->pertanyaan, 'jawaban' => $r->jawaban,
            'urutan' => $r->urutan ?? 0, 'is_active' => $r->has('is_active'),
        ]);

        return back()->with('ok', 'FAQ diperbarui ✅');
    }

    public function faqDestroy(Faq $faq)
    {
        $faq->delete();

        return back()->with('ok', 'FAQ dihapus.');
    }

    // ===== KHUSUS ADMIN: Tier Harga =====
    public function tierStore(Request $r, Product $product)
    {
        $r->validate([
            'level' => 'required|in:user,reseller', 'min_qty' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
        ]);
        ProductPrice::updateOrCreate(
            ['product_id' => $product->id, 'level' => $r->level, 'min_qty' => $r->min_qty],
            ['price' => $r->price]
        );
        ActivityLog::catat('tier_tambah', $product->nama, "{$r->level} min {$r->min_qty} = Rp ".number_format($r->price), 'produk', null, '/admin/produk');

        return back()->with('ok', "Tier {$r->level} min {$r->min_qty} pcs = Rp ".number_format($r->price).' ✅');
    }

    public function tierDestroy(ProductPrice $tier)
    {
        $sisa = ProductPrice::where('product_id', $tier->product_id)->where('level', $tier->level)->count();
        if ($sisa <= 1) {
            return back()->withErrors(['tier' => 'Tiap level wajib punya minimal 1 tier (fallback).']);
        }
        $tier->delete();
        ActivityLog::catat('tier_hapus', null, "level {$tier->level} min {$tier->min_qty}", 'produk', null, '/admin/produk');

        return back()->with('ok', 'Tier dihapus.');
    }

    // ===== KHUSUS ADMIN: Stok Opname =====
    public function rekonsiliasi()
    {
        return DB::transaction(function () {
            $n = 0;
            foreach (Product::with('variants')->get() as $p) {
                if ($p->variants->isEmpty()) {
                    continue;
                }
                $sum = (int) $p->variants->sum('stok');
                if ((int) $p->stok !== $sum) {
                    StockMutation::create([
                        'product_id' => $p->id, 'tipe' => $sum >= $p->stok ? 'masuk' : 'keluar',
                        'qty' => abs($sum - $p->stok), 'stok_sebelum' => $p->stok,
                        'stok_sesudah' => $sum, 'keterangan' => 'Rekonsiliasi otomatis',
                    ]);
                    $p->update(['stok' => $sum]);
                    $n++;
                }
            }

            ActivityLog::catat('rekonsiliasi', null, "$n produk diselaraskan", 'stok', null, '/admin/produk');

            return back()->with('ok', $n > 0 ? "$n produk diselaraskan ✅" : 'Semua stok sudah cocok ✅');
        });
    }

    public function opname()
    {
        $products = Product::with(['variants' => fn ($q) => $q->orderBy('ukuran')])->orderBy('nama')->get();
        $kategoris = Product::where('is_active', true)->distinct()->pluck('kategori');
        $sesiJalan = OpnameSesi::withCount('items')->where('status', 'jalan')->latest()->first();
        $riwayat = OpnameSesi::with('user')->where('status', '!=', 'jalan')->latest()->take(10)->get();

        return web_view('admin-opname', compact('products', 'kategoris', 'sesiJalan', 'riwayat'));
    }

    public function opnameMulai(Request $r)
    {
        $r->validate(['cakupan' => 'nullable|string|max:50']);
        $cakupan = $r->get('cakupan', 'semua');
        if ($jalan = OpnameSesi::where('status', 'jalan')->latest()->first()) {
            return redirect('/admin/opname/'.$jalan->id)->with('ok', 'Sesi #'.$jalan->id.' masih jalan, lanjutkan.');
        }
        $varian = ProductVariant::whereHas('product', fn ($q) => $q
            ->when($cakupan !== 'semua', fn ($x) => $x->where('kategori', $cakupan)))
            ->orderBy('product_id')->get();
        if ($varian->isEmpty()) {
            return back()->withErrors(['cakupan' => 'Tidak ada varian pada cakupan ini.']);
        }
        $sesi = OpnameSesi::create(['user_id' => auth()->id(), 'cakupan' => $cakupan, 'status' => 'jalan']);
        foreach ($varian as $v) {
            OpnameItem::create(['sesi_id' => $sesi->id, 'variant_id' => $v->id, 'snapshot' => $v->stok]);
        }
        ActivityLog::catat('opname_mulai', 'Sesi #'.$sesi->id, 'Cakupan: '.$cakupan, 'stok', null, '/admin/opname/'.$sesi->id);

        return redirect('/admin/opname/'.$sesi->id)->with('ok', 'Sesi opname #'.$sesi->id.' dimulai. Hitung satu per satu.');
    }

    protected function sesiJalan(OpnameSesi $sesi): OpnameSesi
    {
        abort_unless($sesi->status === 'jalan', 404);

        return $sesi->load(['items.variant.product']);
    }

    public function opnameHitung(Request $r, OpnameSesi $sesi)
    {
        $sesi = $this->sesiJalan($sesi);
        $ids = $sesi->items->pluck('variant.product_id')->unique()->values();
        $products = Product::with('variants')->whereIn('id', $ids)->get()->sortBy(fn ($p) => array_search($p->id, $ids->all()));
        $pos = max(0, min((int) $r->get('p', 0), count($products) - 1));
        if ($r->get('belum')) {
            foreach ($products as $i => $p) {
                $idVarian = $p->variants->pluck('id')->all();
                $kosong = $sesi->items->whereIn('variant_id', $idVarian)->whereNull('fisik')->count();
                if ($kosong > 0) {
                    $pos = $i;
                    break;
                }
            }
        }
        $product = $products->values()->get($pos);
        $itemMap = $sesi->items->keyBy('variant_id');

        return web_view('opname-hitung', compact('sesi', 'products', 'product', 'pos', 'itemMap'));
    }

    public function opnameHitungSimpan(Request $r, OpnameSesi $sesi)
    {
        $sesi = $this->sesiJalan($sesi);
        $r->validate(['fisik' => 'nullable|array', 'fisik.*' => 'nullable|integer|min:0']);
        $boleh = $sesi->items->pluck('variant_id')->all();
        foreach ((array) $r->fisik as $vid => $fisik) {
            if (! in_array((int) $vid, array_map('intval', $boleh), true) || $fisik === null || $fisik === '') {
                continue;
            }
            OpnameItem::where('sesi_id', $sesi->id)->where('variant_id', $vid)->update(['fisik' => (int) $fisik]);
        }
        $sisa = OpnameItem::where('sesi_id', $sesi->id)->whereNull('fisik')->count();
        if ($sisa === 0) {
            return redirect('/admin/opname/'.$sesi->id.'/review')->with('ok', 'Semua terhitung. Cek selisih lalu sahkan.');
        }

        return redirect('/admin/opname/'.$sesi->id.'?p='.(int) $r->get('next', 0).'&belum=1')->with('ok', "Tersimpan. Sisa $sisa varian.");
    }

    public function opnameReview(OpnameSesi $sesi)
    {
        $sesi = $this->sesiJalan($sesi);
        $items = $sesi->items()->with('variant.product')->whereNotNull('fisik')->get();
        $beda = $items->filter(fn ($it) => $it->selisih() !== 0)->values();
        $kosong = $sesi->items()->whereNull('fisik')->count();

        return web_view('opname-review', compact('sesi', 'items', 'beda', 'kosong'));
    }

    public function opnameSahkan(Request $r, OpnameSesi $sesi)
    {
        $sesi = $this->sesiJalan($sesi);
        $items = $sesi->items()->with('variant')->whereNotNull('fisik')->get();
        if (OpnameItem::where('sesi_id', $sesi->id)->whereNull('fisik')->exists()) {
            return back()->withErrors(['fisik' => 'Masih ada yang belum dihitung. Lengkapi dulu / batalkan sesi.']);
        }
        $besar = $items->filter(fn ($it) => $it->besar())->values();
        $r->validate(['foto' => 'nullable|array', 'foto.*' => 'nullable|image|max:2048']);
        foreach ($besar as $it) {
            $ada = $it->foto || ($r->file('foto') && isset($r->file('foto')[$it->variant_id]));
            if (! $ada) {
                return back()->withErrors(['foto' => 'Selisih besar wajib foto bukti ('.$it->variant->label().').']);
            }
        }

        return DB::transaction(function () use ($r, $sesi, $items) {
            $diubah = 0;
            $files = $r->file('foto', []);
            foreach ($items as $it) {
                $v = ProductVariant::find($it->variant_id);
                if (! $v) {
                    continue;
                }
                if (isset($files[$it->variant_id])) {
                    $it->update(['foto' => $files[$it->variant_id]->store('opname', 'public')]);
                }
                // Stok bisa berubah saat sesi jalan (ada order masuk): tetap set ke
                // hasil hitung fisik, selisih vs snapshot tercatat di keterangan.
                if ((int) $v->stok === (int) $it->fisik) {
                    continue;
                }
                $sebelum = $v->stok;
                $v->update(['stok' => (int) $it->fisik]);
                StockMutation::create([
                    'product_id' => $v->product_id, 'variant_id' => $v->id,
                    'tipe' => $it->fisik >= $sebelum ? 'masuk' : 'keluar',
                    'qty' => abs($it->fisik - $sebelum), 'stok_sebelum' => $sebelum,
                    'stok_sesudah' => (int) $it->fisik,
                    'keterangan' => 'Opname sesi #'.$sesi->id.' (snapshot '.$it->snapshot.')',
                ]);
                $v->product->syncStok();
                $diubah++;
            }
            $sesi->update(['status' => 'selesai']);
            ActivityLog::catat('opname_sahkan', 'Sesi #'.$sesi->id, "$diubah varian disesuaikan", 'stok', null, '/admin/opname');

            return redirect('/admin/opname')->with('ok', "Opname sesi #{$sesi->id} disahkan: $diubah varian disesuaikan ✅");
        });
    }

    public function opnameBatal(OpnameSesi $sesi)
    {
        abort_unless($sesi->status === 'jalan', 404);
        $sesi->items()->delete();
        $sesi->delete();
        ActivityLog::catat('opname_batal', 'Sesi #'.$sesi->id, null, 'stok', null, '/admin/opname');

        return redirect('/admin/opname')->with('ok', 'Sesi dibatalkan, tidak ada stok berubah.');
    }

    public function opnameStore(Request $r)
    {
        $r->validate(['fisik' => 'required|array', 'fisik.*' => 'nullable|integer|min:0']);

        return DB::transaction(function () use ($r) {
            $diubah = 0;
            foreach ($r->fisik as $variantId => $fisik) {
                if ($fisik === null || $fisik === '') {
                    continue;
                }
                $v = ProductVariant::find($variantId);
                if (! $v || (int) $v->stok === (int) $fisik) {
                    continue;
                }
                $sebelum = $v->stok;
                $v->update(['stok' => (int) $fisik]);
                StockMutation::create([
                    'product_id' => $v->product_id, 'variant_id' => $v->id,
                    'tipe' => $v->stok >= $sebelum ? 'masuk' : 'keluar',
                    'qty' => abs($v->stok - $sebelum), 'stok_sebelum' => $sebelum,
                    'stok_sesudah' => $v->stok, 'keterangan' => 'Opname '.now()->format('d M Y'),
                ]);
                $v->product->syncStok();
                $diubah++;
            }

            ActivityLog::catat('opname_simpan', null, "$diubah varian disesuaikan", 'stok', null, '/admin/opname');

            return back()->with('ok', "Opname selesai: $diubah varian disesuaikan ✅");
        });
    }

    // ===== KHUSUS ADMIN: Rekap Harian =====
    public function rekap(Request $r)
    {
        // Digabung ke tab Harian di /admin/laporan — redirect agar bookmark lama tetap jalan
        $url = '/admin/laporan?tab=harian';
        if ($r->get('tanggal')) {
            $url .= '&tanggal='.$r->get('tanggal');
        }

        return redirect($url);
    }

    // ===== KHUSUS ADMIN: Export CSV =====
    protected function csvDownload(string $nama, array $head, iterable $rows)
    {
        $out = fopen('php://temp', 'r+');
        fputcsv($out, $head, ';');
        foreach ($rows as $row) {
            fputcsv($out, $row, ';');
        }
        rewind($out);
        $csv = stream_get_contents($out);
        fclose($out);

        return response("\xEF\xBB\xBF".$csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$nama.'"',
        ]);
    }

    public function exportOrders(Request $r)
    {
        $r->validate(['status' => 'nullable|in:menunggu_bayar,diproses,dikirim,selesai,batal']);
        $orders = Order::with('user', 'items.product')->when($r->status, fn ($q) => $q->where('status', $r->status))->latest()->get();

        return $this->csvDownload('order-bubee-'.date('Ymd').'.csv',
            ['Kode', 'Tanggal', 'Pembeli', 'Kurir', 'Total', 'Status'],
            $orders->map(fn ($o) => [$o->kode, $o->created_at->format('d-m-Y H:i'), $o->user->name ?? '', $o->kurir.' '.$o->layanan, $o->total_bayar, $o->status]));
    }

    public function exportLaporan(Request $r)
    {
        $r->validate([
            'dari' => 'nullable|date|before_or_equal:today',
            'sampai' => 'nullable|date|before_or_equal:today',
            'periode' => 'nullable|in:harian,mingguan,bulanan,kustom',
            'tanggal' => 'nullable|date|before_or_equal:today',
            'minggu' => 'nullable|date|before_or_equal:today',
            'bulan' => 'nullable|string',
        ]);
        $today = now()->toDateString();
        $periode = $r->get('periode');

        if ($periode === 'harian') {
            $tgl = $r->get('tanggal', $today);
            if ($tgl > $today) {
                $tgl = $today;
            }
            $dari = $tgl;
            $sampai = $tgl;
        } elseif ($periode === 'mingguan') {
            $ref = \Carbon\Carbon::parse($r->get('minggu', $today));
            $dari = $ref->copy()->startOfWeek()->toDateString();
            $sampai = $ref->copy()->endOfWeek()->toDateString();
        } elseif ($periode === 'bulanan') {
            $b = $r->get('bulan', now()->format('Y-m'));
            if (! preg_match('/^\d{4}-\d{2}$/', (string) $b)) {
                $b = now()->format('Y-m');
            }
            $ref = \Carbon\Carbon::parse($b.'-01');
            $dari = $ref->copy()->startOfMonth()->toDateString();
            $sampai = $ref->copy()->endOfMonth()->toDateString();
        } else {
            $sampai = $r->get('sampai', $today);
            $dari = $r->get('dari', now()->subDays(29)->toDateString());
            if ($dari > $sampai) {
                [$dari, $sampai] = [$sampai, $dari];
            }
        }
        $lunas = ['diproses', 'dikirim', 'selesai'];
        $rows = [];
        $d = now()->parse($dari);
        $s = now()->parse($sampai);
        $n = 0;
        while ($d->lte($s) && $n < 62) {
            $tgl = $d->toDateString();
            $rows[] = [
                $d->format('d-m-Y'),
                Order::whereDate('created_at', $tgl)->count(),
                OrderItem::whereHas('order', fn ($q) => $q->whereDate('created_at', $tgl)->whereIn('status', $lunas))->sum('qty'),
                Order::whereDate('created_at', $tgl)->whereIn('status', $lunas)->sum('total_bayar'),
            ];
            $d->addDay();
            $n++;
        }

        return $this->csvDownload('laporan-bubee-'.$dari.'-sd-'.$sampai.'.csv', ['Tanggal', 'Order', 'Pcs', 'Omzet'], $rows);
    }

    // ===== KHUSUS ADMIN: Log Aktivitas =====
    public function aktivitas(Request $r)
    {
        $r->validate([
            'q' => 'nullable|string|max:50',
            'kategori' => 'nullable|in:semua,auth,order,stok,produk,user,sistem',
            'level' => 'nullable|in:semua,info,waspada,bahaya',
            'dari' => 'nullable|date|before_or_equal:today',
            'sampai' => 'nullable|date|before_or_equal:today',
            'preset' => 'nullable|in:hari_ini,tujuh_hari,tiga_puluh_hari',
        ]);
        $q = $r->get('q');
        $kategori = $r->get('kategori', 'semua');
        $level = $r->get('level', 'semua');
        $presetHari = ['hari_ini' => 0, 'tujuh_hari' => 6, 'tiga_puluh_hari' => 29][$r->get('preset', '')] ?? null;
        $dari = $r->get('dari', now()->subDays($presetHari ?? 6)->toDateString());
        $sampai = $r->get('sampai', now()->toDateString());
        $logs = ActivityLog::with('actor')
            ->when($q, fn ($x) => $x->where(function ($y) use ($q) {
                $y->where('keterangan', 'like', "%$q%")->orWhere('subjek', 'like', "%$q%")->orWhere('aksi', 'like', "%$q%");
            }))
            ->when($kategori !== 'semua', fn ($x) => $x->where('kategori', $kategori))
            ->when($level !== 'semua', fn ($x) => $x->where('level', $level))
            ->whereDate('created_at', '>=', $dari)->whereDate('created_at', '<=', $sampai)
            ->latest()->paginate(25)->withQueryString();
        $ringkas = [
            'gagal24' => ActivityLog::whereIn('aksi', ['login_gagal', 'login_honeypot'])->where('created_at', '>=', now()->subDay())->count(),
            'bahaya7' => ActivityLog::where('level', 'bahaya')->where('created_at', '>=', now()->subDays(7))->count(),
            'total' => ActivityLog::count(),
        ];

        return web_view('admin-aktivitas', compact('logs', 'q', 'kategori', 'level', 'dari', 'sampai', 'ringkas'));
    }

    public function exportAktivitas(Request $r)
    {
        $r->validate([
            'q' => 'nullable|string|max:50',
            'kategori' => 'nullable|in:semua,auth,order,stok,produk,user,sistem',
            'level' => 'nullable|in:semua,info,waspada,bahaya',
        ]);
        $logs = ActivityLog::with('actor')
            ->when($r->q, fn ($x) => $x->where(function ($y) use ($r) {
                $y->where('keterangan', 'like', "%{$r->q}%")->orWhere('subjek', 'like', "%{$r->q}%")->orWhere('aksi', 'like', "%{$r->q}%");
            }))
            ->when($r->kategori && $r->kategori !== 'semua', fn ($x) => $x->where('kategori', $r->kategori))
            ->when($r->level && $r->level !== 'semua', fn ($x) => $x->where('level', $r->level))
            ->latest()->take(2000)->get();

        return $this->csvDownload('aktivitas-bubee-'.date('Ymd').'.csv',
            ['Waktu', 'Kategori', 'Level', 'Aksi', 'Oleh', 'Subjek', 'Keterangan', 'IP'],
            $logs->map(fn ($l) => [$l->created_at->format('d-m-Y H:i'), $l->kategori, $l->level, $l->aksi, $l->actor->name ?? 'sistem', $l->subjek, $l->keterangan, $l->ip]));
    }

    public function pruneAktivitas()
    {
        $n = ActivityLog::prune(90);

        return back()->with('ok', "$n log lebih tua dari 90 hari dibersihkan 🧹");
    }

    // ===== KHUSUS ADMIN: Diagnostik Integrasi (Go-Live) =====
    public function integrasi()
    {
        $midtrans = [
            'nama' => 'Midtrans (Pembayaran)',
            'live' => MidtransService::configured(),
            'detail' => MidtransService::configured()
                ? (MidtransService::isProduction() ? 'Mode PRODUCTION' : 'Mode SANDBOX')
                : 'Isi MIDTRANS_SERVER_KEY + MIDTRANS_CLIENT_KEY di .env',
            'webhook' => url('/midtrans/notify'),
        ];
        $raja = [
            'nama' => 'RajaOngkir (Ongkir JNE)',
            'live' => RajaOngkirService::configured(),
            'detail' => RajaOngkirService::configured()
                ? 'Key OK • Kota asal: '.(Setting::get('rajaongkir_origin_name', '-') ?: RajaOngkirService::originCity())
                : 'Isi key di Pengaturan (Starter cukup). J&T/SPX tetap tarif toko.',
        ];
        $fonnte = [
            'nama' => 'Fonnte (WA Otomatis)',
            'live' => WaNotifier::isLive(),
            'detail' => WaNotifier::isLive() ? 'Token terisi' : 'Isi token di Pengaturan (mode dummy aktif)',
        ];
        $captchaOn = Setting::get('turnstile_wajib', '0') === '1';
        $captchaKey = (string) (Setting::get('turnstile_site_key', '') ?: env('TURNSTILE_SITE_KEY', ''));
        $captcha = [
            'nama' => 'Captcha Login (Turnstile)',
            'live' => $captchaOn && $captchaKey !== '',
            'detail' => ! $captchaOn ? 'Mati (aman untuk link tunnel)' : ($captchaKey !== '' ? 'Aktif — pastikan domain ini terdaftar di Cloudflare' : 'Saklar nyala tapi kunci kosong = tidak jalan'),
        ];

        return web_view('admin-integrasi', compact('midtrans', 'raja', 'fonnte', 'captcha'));
    }

    public function integrasiTestWa()
    {
        $log = WaNotifier::send(
            WaNotifier::adminNumber(),
            '🐝 Tes notifikasi Bubee! Kalau pesan ini masuk, WA otomatis sudah '.(WaNotifier::isLive() ? 'LIVE ✅' : 'DUMMY (cek log admin)'),
            'tes'
        );

        return back()->with('ok', 'Tes dikirim → status: '.$log->status.' ke '.$log->tujuan);
    }

    // ===== KHUSUS ADMIN: Log Notifikasi WA =====
    public function notifikasi(Request $r)
    {
        $r->validate(['q' => 'nullable|string|max:50']);
        $q = $r->get('q');
        $logs = NotificationLog::with('order')
            ->when($q, fn ($x) => $x->where(function ($y) use ($q) {
                $y->where('pesan', 'like', "%$q%")->orWhere('tujuan', 'like', "%$q%");
            }))
            ->latest()->paginate(25)->withQueryString();
        $live = WaNotifier::isLive();

        return web_view('admin-notifikasi', compact('logs', 'q', 'live'));
    }

    public function notifikasiResend(NotificationLog $log)
    {
        $baru = WaNotifier::send($log->tujuan, $log->pesan, $log->tipe, $log->order_id);

        return back()->with('ok', 'Dikirim ulang → status: '.$baru->status);
    }

    // ===== KHUSUS ADMIN: Pengaturan Toko =====
    public function settings()
    {
        $data = [
            'nama_toko' => Setting::get('nama_toko', 'Bubee'),
            'wa_admin' => Setting::get('wa_admin', '6281234567890'),
            'target_harian' => Setting::get('target_harian', 1000000),
            'pengumuman' => Setting::get('pengumuman', ''),
            'fonnte_token' => Setting::get('fonnte_token', ''),
            'turnstile_wajib' => Setting::get('turnstile_wajib', '0'),
            'turnstile_site_key' => Setting::get('turnstile_site_key', ''),
            'turnstile_secret' => Setting::get('turnstile_secret', ''),
            'rajaongkir_key' => Setting::get('rajaongkir_key', ''),
            'rajaongkir_origin_city' => Setting::get('rajaongkir_origin_city', ''),
            'rajaongkir_origin_name' => Setting::get('rajaongkir_origin_name', ''),
            'bank_nama' => Setting::get('bank_nama', 'BCA'),
            'bank_rekening' => Setting::get('bank_rekening', '1234567890'),
            'bank_atas_nama' => Setting::get('bank_atas_nama', 'Toko Bubee'),
        ];

        return web_view('admin-settings', compact('data'));
    }

    public function settingsUpdate(Request $r)
    {
        $r->validate(['nama_toko' => 'required|max:50', 'wa_admin' => 'required|max:20', 'target_harian' => 'required|integer|min:0']);
        Setting::set('nama_toko', $r->nama_toko);
        Setting::set('wa_admin', preg_replace('/[^0-9]/', '', $r->wa_admin));
        Setting::set('target_harian', $r->target_harian);
        Setting::set('pengumuman', $r->pengumuman ?? '');
        Setting::set('fonnte_token', trim($r->fonnte_token ?? ''));
        Setting::set('turnstile_wajib', $r->has('turnstile_wajib') ? '1' : '0');
        Setting::set('turnstile_site_key', trim($r->turnstile_site_key ?? ''));
        Setting::set('turnstile_secret', trim($r->turnstile_secret ?? ''));
        Setting::set('rajaongkir_key', trim($r->rajaongkir_key ?? ''));
        Setting::set('rajaongkir_origin_city', trim($r->rajaongkir_origin_city ?? ''));
        Setting::set('rajaongkir_origin_name', trim($r->rajaongkir_origin_name ?? ''));
        Setting::set('bank_nama', trim($r->bank_nama ?? 'BCA'));
        Setting::set('bank_rekening', trim($r->bank_rekening ?? ''));
        Setting::set('bank_atas_nama', trim($r->bank_atas_nama ?? ''));

        return back()->with('ok', 'Pengaturan toko disimpan ✅');
    }
}
