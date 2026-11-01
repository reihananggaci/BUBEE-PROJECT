<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Penerimaan;
use App\Models\PenerimaanItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    public function index()
    {
        $list = Penerimaan::with('user')->withCount('items')->latest()->take(20)->get();

        return web_view('admin-penerimaan', compact('list'));
    }

    public function create()
    {
        $products = Product::with('variants')->where('is_active', true)->orderBy('nama')->get();

        return web_view('penerimaan-form', compact('products'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'no_surat_jalan' => 'required|max:50',
            'supplier' => 'nullable|max:100',
            'items' => 'required|array|min:1|max:50',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
            'items.*.qty_klaim' => 'required|integer|min:1|max:100000',
            'items.*.is_baru' => 'nullable|boolean',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.nama_baru' => 'nullable|max:100',
            'items.*.harga_enduser' => 'nullable|integer|min:0',
            'items.*.harga_reseller' => 'nullable|integer|min:0',
        ]);
        $bersih = [];
        foreach ($r->items as $idx => $it) {
            $isBaru = ! empty($it['is_baru']);
            if ($isBaru) {
                if (empty(trim($it['nama_baru'] ?? ''))) {
                    return back()->withErrors(['items' => 'Baris '.($idx + 1).': nama barang baru wajib diisi.'])->withInput();
                }
            } elseif (empty($it['product_id'])) {
                return back()->withErrors(['items' => 'Baris '.($idx + 1).': pilih barang yang sudah ada ATAU centang "barang baru".'])->withInput();
            }
            if (! empty($it['variant_id']) && empty($isBaru)) {
                $ok = ProductVariant::where('id', $it['variant_id'])->where('product_id', $it['product_id'])->exists();
                if (! $ok) {
                    return back()->withErrors(['items' => 'Baris '.($idx + 1).': varian tidak cocok dengan barangnya.'])->withInput();
                }
            }
            $bersih[] = $it + ['_is_baru' => $isBaru];
        }
        $p = DB::transaction(function () use ($r, $bersih) {
            $p = Penerimaan::create([
                'user_id' => Auth::id(), 'no_surat_jalan' => $r->no_surat_jalan,
                'supplier' => $r->supplier, 'status' => 'dicatat',
            ]);
            foreach ($bersih as $it) {
                $productId = $it['product_id'] ?? null;
                $variantId = $it['variant_id'] ?? null;
                if (! empty($it['_is_baru'])) {
                    // Barang baru: buat produk nonaktif-stok-0, stok bertambah saat verifikasi QC lolos
                    $produk = Product::create([
                        'nama' => trim($it['nama_baru']),
                        'deskripsi' => 'Dibuat dari catat kedatangan '.$r->no_surat_jalan,
                        'kategori' => 'Fashion',
                        'berat_gram' => 250,
                        'stok' => 0,
                        'harga_enduser' => (int) ($it['harga_enduser'] ?? 0),
                        'harga_reseller' => (int) ($it['harga_reseller'] ?? $it['harga_enduser'] ?? 0),
                        'min_reseller' => 6,
                        'is_active' => true,
                    ]);
                    $productId = $produk->id;
                    $variantId = $produk->variants()->create([
                        'ukuran' => 'All Size',
                        'warna' => '-',
                        'stok' => 0,
                        'sku' => 'BB-'.$produk->id.'-1',
                    ])->id;
                }
                PenerimaanItem::create([
                    'penerimaan_id' => $p->id, 'product_id' => $productId,
                    'variant_id' => $variantId, 'qty_klaim' => $it['qty_klaim'],
                ]);
            }

            return $p;
        });
        ActivityLog::catat('terima_catat', $p->no_surat_jalan, count($bersih).' item', 'stok', null, '/admin/penerimaan/'.$p->id);

        return redirect('/admin/penerimaan/'.$p->id)->with('ok', 'Kedatangan dicatat. Stok BELUM bertambah — verifikasi di gudang.');
    }

    public function show(Penerimaan $penerimaan)
    {
        $penerimaan->load('items.product', 'items.variant', 'user');

        return web_view('penerimaan-verifikasi', compact('penerimaan'));
    }

    public function verify(Request $r, Penerimaan $penerimaan)
    {
        abort_unless($penerimaan->status === 'dicatat', 400);
        $r->validate([
            'hasil' => 'required|array',
            'hasil.*.lolos' => 'required|integer|min:0|max:100000',
            'hasil.*.rusak' => 'required|integer|min:0|max:100000',
            'hasil.*.catatan' => 'nullable|max:200',
        ]);
        $items = $penerimaan->items;
        foreach ($items as $it) {
            $h = $r->hasil[$it->id] ?? null;
            if (! $h || (int) $h['lolos'] + (int) $h['rusak'] !== (int) $it->qty_klaim) {
                return back()->withErrors(['hasil' => 'Lolos + rusak harus pas = klaim ('.$it->qty_klaim.') untuk semua item.'])->withInput();
            }
        }

        return DB::transaction(function () use ($r, $penerimaan, $items) {
            foreach ($items as $it) {
                $h = $r->hasil[$it->id];
                $it->update(['qty_lolos' => (int) $h['lolos'], 'qty_rusak' => (int) $h['rusak'], 'catatan' => $h['catatan'] ?? null]);
                if ($it->qty_lolos > 0 && $it->variant_id) {
                    $v = ProductVariant::find($it->variant_id);
                    StockMutation::create([
                        'product_id' => $it->product_id, 'variant_id' => $v->id,
                        'tipe' => 'masuk', 'qty' => $it->qty_lolos,
                        'stok_sebelum' => $v->stok, 'stok_sesudah' => $v->stok + $it->qty_lolos,
                        'keterangan' => 'Terima '.$penerimaan->no_surat_jalan,
                    ]);
                    $v->increment('stok', $it->qty_lolos);
                    $v->product->syncStok();
                } elseif ($it->qty_lolos > 0) {
                    $p = Product::find($it->product_id);
                    StockMutation::create([
                        'product_id' => $p->id, 'tipe' => 'masuk', 'qty' => $it->qty_lolos,
                        'stok_sebelum' => $p->stok, 'stok_sesudah' => $p->stok + $it->qty_lolos,
                        'keterangan' => 'Terima '.$penerimaan->no_surat_jalan,
                    ]);
                    $p->increment('stok', $it->qty_lolos);
                }
            }
            $penerimaan->update(['status' => 'diverifikasi']);
            $lolos = $items->sum('qty_lolos');
            $rusak = $items->sum('qty_rusak');
            ActivityLog::catat('terima_verifikasi', $penerimaan->no_surat_jalan, "Lolos $lolos, rusak $rusak", 'stok', null, '/admin/penerimaan/'.$penerimaan->id);

            return redirect('/admin/penerimaan')->with('ok', "Terverifikasi: lolos $lolos, rusak $rusak. Stok bertambah sesuai lolos QC.");
        });
    }

    public function destroy(Penerimaan $penerimaan)
    {
        abort_unless($penerimaan->status === 'dicatat', 400);
        $penerimaan->items()->delete();
        $penerimaan->delete();

        return redirect('/admin/penerimaan')->with('ok', 'Catatan kedatangan dibatalkan (stok tak berubah).');
    }
}
