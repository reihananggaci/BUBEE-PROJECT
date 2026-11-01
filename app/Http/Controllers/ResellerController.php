<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResellerController extends Controller
{
    private function hematQuery(int $userId)
    {
        return OrderItem::with('product')
            ->whereHas('order', fn ($q) => $q->where('user_id', $userId)->where('status', '!=', 'batal'))
            ->where('tipe_harga', 'reseller')->get();
    }

    public static function tier(int $belanja): string
    {
        if ($belanja >= 5000000) {
            return 'Gold';
        }
        if ($belanja >= 1000000) {
            return 'Silver';
        }

        return 'Bronze';
    }

    public function dashboard()
    {
        $user = Auth::user();
        $items = $this->hematQuery($user->id);
        $totalHemat = $items->sum(fn ($it) => max(0, ($it->product->harga_enduser ?? $it->harga_satuan) - $it->harga_satuan) * $it->qty);
        $totalPcs = OrderItem::whereHas('order', fn ($q) => $q->where('user_id', $user->id)->where('status', '!=', 'batal'))->sum('qty');
        $totalOrder = Order::where('user_id', $user->id)->where('status', '!=', 'batal')->count();
        $belanja = Order::where('user_id', $user->id)->whereIn('status', ['diproses', 'dikirim', 'selesai'])->sum('total_bayar');
        $menunggu = Order::where('user_id', $user->id)->where('status', 'menunggu_bayar')->count();
        $vouchers = Voucher::where('is_active', true)->whereIn('khusus', ['semua', 'reseller'])
            ->where(function ($q) {
                $q->whereNull('berlaku_sampai')->orWhere('berlaku_sampai', '>=', now()->toDateString());
            })
            ->whereColumn('terpakai', '<', 'kuota')->take(5)->get();
        $menipis = Product::where('is_active', true)->where('stok', '>', 0)->where('stok', '<=', 10)->take(5)->get();
        $tier = self::tier((int) $belanja);

        return web_view('reseller-dashboard', compact('user', 'totalHemat', 'totalPcs', 'totalOrder', 'belanja', 'menunggu', 'vouchers', 'menipis', 'tier'));
    }

    public function etalase(Request $r)
    {
        $sort = $r->get('sort', 'margin');
        $products = Product::where('is_active', true)->with('variants')->get()->map(function ($p) {
            $p->margin_rp = max(0, $p->harga_enduser - $p->harga_reseller);
            $p->margin_pct = $p->harga_enduser > 0 ? round($p->margin_rp / $p->harga_enduser * 100) : 0;

            return $p;
        });
        $products = $sort === 'stok' ? $products->sortByDesc('stok')->values() : $products->sortByDesc('margin_rp')->values();

        return web_view('reseller-etalase', compact('products', 'sort'));
    }

    public function hemat()
    {
        $user = Auth::user();
        $orders = Order::with(['items.product', 'voucher'])->where('user_id', $user->id)
            ->where('status', '!=', 'batal')->latest()->get()->map(function ($o) {
                $o->hemat = $o->items->where('tipe_harga', 'reseller')
                    ->sum(fn ($it) => max(0, ($it->product->harga_enduser ?? $it->harga_satuan) - $it->harga_satuan) * $it->qty);

                return $o;
            });
        $totalHemat = $orders->sum('hemat');

        return web_view('reseller-hemat', compact('orders', 'totalHemat'));
    }
}
