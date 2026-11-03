<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Retur;
use App\Models\ReturItem;
use App\Models\StockMutation;
use App\Services\WaNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{
    public function form(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        abort_unless($order->status === 'selesai', 400);
        $order->load('items.product', 'items.variant');

        return web_view('retur-form', compact('order'));
    }

    public function store(Request $r, Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        abort_unless($order->status === 'selesai', 400);
        $r->validate([
            'alasan' => 'required|max:500', 'foto' => 'nullable|image|max:2048',
            'kategori' => 'nullable|in:retur,rusak,salah_kirim,telat,komplain_lain',
            'items' => 'required|array|min:1', 'items.*' => 'integer|min:0',
        ]);
        try {
            return DB::transaction(function () use ($r, $order) {
                $order->load('items');
                $retur = Retur::create([
                    'order_id' => $order->id, 'user_id' => Auth::id(),
                    'alasan' => $r->alasan,
                    'kategori' => $r->input('kategori', 'retur'),
                    'foto' => $r->hasFile('foto') ? $r->file('foto')->store('retur', 'public') : null,
                    'status' => 'diajukan',
                ]);
                foreach ($r->items as $itemId => $qty) {
                    $oi = $order->items->firstWhere('id', (int) $itemId);
                    if (! $oi) {
                        continue;
                    }
                    // qty retur tidak boleh melebihi yg dibeli dikurangi yg sudah diretur
                    $sudah = ReturItem::whereHas('retur', fn ($q) => $q->whereIn('status', ['diajukan', 'disetujui', 'selesai'])->where('order_id', $order->id))
                        ->where('order_item_id', $oi->id)->sum('qty');
                    $qty = min((int) $qty, $oi->qty - $sudah);
                    if ($qty < 1) {
                        continue;
                    }
                    $retur->items()->create(['order_item_id' => $oi->id, 'qty' => $qty]);
                }
                if ($retur->items()->count() === 0) {
                    $retur->delete();
                    throw new \Exception('Qty retur tidak valid (melebihi pembelian / sudah diretur).');
                }
                WaNotifier::returMasuk($retur);

                return redirect('/retur/'.$retur->id)->with('ok', 'Pengajuan retur dikirim, menunggu admin ✅');
            });
        } catch (\Exception $e) {
            return back()->withErrors(['retur' => $e->getMessage()])->withInput();
        }
    }

    public function saya()
    {
        $returs = Retur::with('order', 'items.orderItem.product')->where('user_id', Auth::id())->latest()->get();

        return web_view('retur-saya', compact('returs'));
    }

    public function detail(Retur $retur)
    {
        abort_unless($retur->user_id === Auth::id() || Auth::user()->role === 'admin', 403);
        $retur->load('order', 'user', 'items.orderItem.product', 'items.orderItem.variant');

        return web_view('retur-detail', compact('retur'));
    }

    // ===== ADMIN =====
    public function index()
    {
        $returs = Retur::with('order', 'user', 'items.orderItem.product')->latest()->paginate(15)->withQueryString();

        return web_view('admin-retur', compact('returs'));
    }

    public function update(Request $r, Retur $retur)
    {
        $r->validate(['status' => 'required|in:diajukan,disetujui,ditolak,selesai', 'catatan_admin' => 'nullable|max:500']);

        if ($retur->status === 'selesai' && $r->status !== 'selesai') {
            return back()->withErrors(['status' => 'Retur yang sudah selesai tidak bisa diubah lagi.']);
        }
        if ($r->status === 'selesai' && $retur->status !== 'disetujui') {
            return back()->withErrors(['status' => 'Setujui retur terlebih dahulu sebelum menandai selesai.']);
        }

        return DB::transaction(function () use ($r, $retur) {
            // Selesai = barang diterima kembali -> stok varian bertambah
            if ($r->status === 'selesai' && $retur->status !== 'selesai') {
                $retur->load('items.orderItem');
                foreach ($retur->items as $ri) {
                    $oi = $ri->orderItem;
                    if (! $oi) {
                        continue;
                    }
                    $p = Product::find($oi->product_id);
                    if ($p) {
                        StockMutation::create([
                            'product_id' => $p->id, 'variant_id' => $oi->variant_id,
                            'tipe' => 'masuk', 'qty' => $ri->qty,
                            'stok_sebelum' => $p->stok, 'stok_sesudah' => $p->stok + $ri->qty,
                            'keterangan' => 'Retur selesai '.$retur->order->kode,
                        ]);
                        $p->increment('stok', $ri->qty);
                        if ($oi->variant_id) {
                            ProductVariant::where('id', $oi->variant_id)->increment('stok', $ri->qty);
                        }
                    }
                }
            }
            $retur->update(['status' => $r->status, 'catatan_admin' => $r->catatan_admin]);
            WaNotifier::returUpdate($retur);
            ActivityLog::catat('retur_update', 'Retur #'.$retur->id, '→ '.$r->status, 'order', null, '/admin/retur/'.$retur->id);

            return back()->with('ok', 'Retur #'.$retur->id.' → '.$r->status);
        });
    }
}
