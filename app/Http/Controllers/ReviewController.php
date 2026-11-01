<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $r, Product $product)
    {
        $r->validate(['rating' => 'required|integer|min:1|max:5', 'ulasan' => 'nullable|max:500', 'foto' => 'nullable|image|max:2048']);
        $user = Auth::user();
        // Harus pernah beli & selesai
        $order = Order::where('user_id', $user->id)->where('status', 'selesai')
            ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))->latest()->first();
        if (! $order) {
            return back()->withErrors(['ulasan' => 'Hanya pembeli yang ordernya selesai yang bisa mengulas.']);
        }
        if (Review::where('order_id', $order->id)->where('product_id', $product->id)->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['ulasan' => 'Kamu sudah mengulas produk ini.']);
        }
        Review::create([
            'product_id' => $product->id, 'user_id' => $user->id, 'order_id' => $order->id,
            'rating' => $r->rating, 'ulasan' => $r->ulasan,
            'foto' => $r->hasFile('foto') ? $r->file('foto')->store('ulasan', 'public') : null,
        ]);

        return back()->with('ok', 'Makasih ulasannya! ⭐');
    }

    public function index()
    {
        $reviews = Review::with('product', 'user')->latest()->paginate(25);

        return web_view('admin-reviews', compact('reviews'));
    }

    public function destroy(Review $review)
    {
        $info = ($review->product->nama ?? '?').' oleh '.($review->user->name ?? '?');
        $review->delete();
        ActivityLog::catat('ulasan_hapus', $info, null, 'sistem', null, '/admin/ulasan');

        return back()->with('ok', 'Ulasan dihapus.');
    }
}
