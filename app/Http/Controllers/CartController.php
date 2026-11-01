<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('product', 'variant')->where('user_id', Auth::id())->get();
        [$carts, $dibuang] = $carts->partition(fn ($c) => $c->product && $c->product->is_active && $c->variant);
        $dibuang->each(fn ($c) => $c->delete());

        return web_view('cart', ['carts' => $carts->values()]);
    }

    public function add(Request $r, Product $product)
    {
        abort_unless($product->is_active, 404);
        $r->validate(['variant_id' => 'required|exists:product_variants,id', 'qty' => 'nullable|integer|min:1|max:1000']);
        $variant = ProductVariant::where('id', $r->variant_id)->where('product_id', $product->id)->firstOrFail();
        $qty = max(1, (int) $r->input('qty', 1));
        if ($variant->stok < $qty) {
            return back()->withErrors(['stok' => 'Stok '.$variant->label().' tidak cukup (sisa '.$variant->stok.')']);
        }
        $c = Cart::firstOrNew(['user_id' => Auth::id(), 'product_id' => $product->id, 'variant_id' => $variant->id]);
        $c->qty = ($c->exists ? $c->qty : 0) + $qty;
        if ($c->qty > $variant->stok) {
            return back()->withErrors(['stok' => 'Total di keranjang melebihi stok '.$variant->label().' (sisa '.$variant->stok.')']);
        }
        $c->save();

        return redirect('/keranjang')->with('ok', $product->nama.' ('.$variant->label().') masuk keranjang');
    }

    public function update(Request $r, Cart $cart)
    {
        $this->authorizeCart($cart);
        $r->validate(['qty' => 'required|integer|min:1|max:1000']);
        $qty = max(1, (int) $r->qty);
        $cart->load('variant');
        if ($cart->variant && $qty > $cart->variant->stok) {
            return back()->withErrors(['stok' => 'Melebihi stok '.$cart->variant->label().' (sisa '.$cart->variant->stok.')']);
        }
        $cart->qty = $qty;
        $cart->save();

        return back()->with('ok', 'Qty diupdate');
    }

    public function destroy(Cart $cart)
    {
        $this->authorizeCart($cart);
        $cart->delete();

        return back()->with('ok', 'Item dihapus');
    }

    private function authorizeCart(Cart $cart): void
    {
        abort_unless($cart->user_id === Auth::id(), 403);
    }
}
