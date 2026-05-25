<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\RecommendationEngineService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, RecommendationEngineService $engine)
    {
        $user = $request->user();

        return view('dashboard', [
            'recommended' => $engine->recommendedFor($user, 8),
            'recentlyViewed' => $user->browsingHistory()->with('product.brand')->latest()->limit(8)->get()->pluck('product')->filter(),
            'wishlist' => $user->wishlist()->with('brand')->limit(6)->get(),
            'orders' => $user->orders()->with('items.product')->latest()->limit(5)->get(),
        ]);
    }
}
