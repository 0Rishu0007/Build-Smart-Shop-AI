<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\UserBehaviorService;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        return view('shop.wishlist', [
            'products' => $request->user()->wishlist()->with(['brand', 'category'])->paginate(12),
        ]);
    }

    public function toggle(Request $request, Product $product, UserBehaviorService $behavior)
    {
        $request->user()->wishlist()->toggle($product->id);
        $behavior->trackProductView($request->user(), $product, 'wishlist');

        return back()->with('status', 'Wishlist updated.');
    }
}
