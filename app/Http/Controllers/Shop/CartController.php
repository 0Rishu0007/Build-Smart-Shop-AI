<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Services\UserBehaviorService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->cart($request)->load('items.product.brand');

        return view('shop.cart', ['cart' => $cart]);
    }

    public function store(Request $request, Product $product, UserBehaviorService $behavior)
    {
        $cart = $this->cart($request);
        $item = $cart->items()->firstOrCreate(['product_id' => $product->id], ['quantity' => 0]);
        $item->increment('quantity', max(1, $request->integer('quantity', 1)));
        $behavior->trackProductView($request->user(), $product, 'cart');

        return back()->with('status', 'Added to cart.');
    }

    public function destroy(Request $request, Product $product)
    {
        $this->cart($request)->items()->where('product_id', $product->id)->delete();

        return back()->with('status', 'Removed from cart.');
    }

    private function cart(Request $request): Cart
    {
        return Cart::firstOrCreate(
            ['user_id' => $request->user()?->id, 'session_id' => $request->user() ? null : $request->session()->getId()]
        );
    }
}
