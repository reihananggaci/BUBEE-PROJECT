<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $r)
    {
        $r->validate([
            'q' => 'nullable|string|max:50',
            'filter' => 'nullable|in:semua,tersedia,menipis,habis,baru',
            'kategori' => 'nullable|string|max:50',
            'sort' => 'nullable|in:stok,murah,mahal',
        ]);
        $q = $r->get('q');
        $filter = $r->get('filter', 'semua'); // semua, tersedia, menipis, habis, reseller-murah
        $kategori = $r->get('kategori', 'semua');
        $sort = $r->get('sort', 'stok'); // stok, murah, mahal
        $daftarKategori = Product::where('is_active', true)->whereNotNull('kategori')->where('kategori', '!=', '')->distinct()->pluck('kategori');
        $products = Product::where('is_active', true)->with('variants')->withAvg('reviews', 'rating')->withCount('reviews')
            ->when($q, fn ($x) => $x->where('nama', 'like', "%$q%"))
            ->when($kategori !== 'semua', fn ($x) => $x->where('kategori', $kategori));
        $products = match ($sort) {
            'murah' => $products->orderBy('harga_enduser', 'asc'),
            'mahal' => $products->orderBy('harga_enduser', 'desc'),
            default => $products->orderBy('stok', 'desc'),
        };
        $products = $products->get();
        if ($filter === 'tersedia') {
            $products = $products->where('stok', '>', 10);
        }
        if ($filter === 'menipis') {
            $products = $products->where('stok', '>', 0)->where('stok', '<=', 10);
        }
        if ($filter === 'habis') {
            $products = $products->where('stok', '<=', 0);
        }
        if ($filter === 'baru') {
            $products = $products->filter(fn ($p) => $p->created_at && $p->created_at->gte(now()->subDays(7)));
        }

        return web_view('home', compact('products', 'q', 'filter', 'kategori', 'sort', 'daftarKategori'));
    }

    public function show(Product $product)
    {
        $mergedMap = [
            29 => 28,
            30 => 28,
            36 => 35,
            37 => 35,
            45 => 44,
            47 => 46,
            62 => 61,
        ];
        if (isset($mergedMap[$product->id])) {
            return redirect('/produk/'.$mergedMap[$product->id], 301);
        }

        if (! $product->is_active && ! (auth()->check() && auth()->user()->role === 'admin')) {
            abort(404);
        }
        $product->load(['images', 'variants' => fn ($q) => $q->orderByDesc('stok')->orderBy('ukuran')]);
        $ulasan = $product->reviews()->with('user')->latest()->take(10)->get();
        $rata = round((float) $product->reviews()->avg('rating'), 1);
        $jml = $product->reviews()->count();
        $bolehUlas = false;
        if (auth()->check()) {
            $bolehUlas = OrderItem::whereHas('order', fn ($q) => $q->where('user_id', auth()->id())->where('status', 'selesai'))
                ->where('product_id', $product->id)->exists()
                && ! Review::where('product_id', $product->id)->where('user_id', auth()->id())->exists();
        }
        $lainnya = Product::where('is_active', true)->where('id', '!=', $product->id)->take(4)->get();

        return web_view('product-detail', compact('product', 'lainnya', 'ulasan', 'rata', 'jml', 'bolehUlas'));
    }

    public function splash()
    {
        return view('splash');
    }
}
