<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\RecommendationEngineService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request, RecommendationEngineService $engine)
    {
        return view('welcome', [
            'categories' => Category::withCount('products')->where('is_featured', true)->limit(8)->get(),
            'recommended' => $engine->recommendedFor($request->user(), 8),
            'trending' => $engine->trending(10),
        ]);
    }
}
