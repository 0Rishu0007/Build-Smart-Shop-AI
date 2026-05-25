<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\RecommendationEngineService;
use App\Services\UserBehaviorService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request, UserBehaviorService $behavior)
    {
        $products = Product::query()
            ->active()
            ->with(['brand', 'category'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = str($request->string('q'))->lower()->squish();
                $query->where(fn ($inner) => $inner
                    ->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%"));
            })
            ->when($request->filled('category'), fn ($query) => $query->whereHas('category', fn ($category) => $category->where('slug', $request->category)))
            ->when($request->filled('brand'), fn ($query) => $query->whereHas('brand', fn ($brand) => $brand->where('slug', $request->brand)))
            ->when($request->filled('min_price'), fn ($query) => $query->where('price', '>=', $request->decimal('min_price')))
            ->when($request->filled('max_price'), fn ($query) => $query->where('price', '<=', $request->decimal('max_price')))
            ->when($request->filled('rating'), fn ($query) => $query->where('rating_average', '>=', $request->integer('rating')))
            ->tap(fn ($query) => match ($request->get('sort')) {
                'price_low' => $query->orderBy('price'),
                'price_high' => $query->orderByDesc('price'),
                'rated' => $query->orderByDesc('rating_average'),
                'popular' => $query->orderByDesc('views_count'),
                default => $query->latest(),
            });

        $count = (clone $products)->count();
        $behavior->trackSearch($request->user(), (string) $request->query('q', ''), $count);

        return view('shop.products.index', [
            'products' => $products->paginate(16)->withQueryString(),
            'categories' => Category::withCount('products')->get(),
            'brands' => Brand::withCount('products')->get(),
            'popularSearches' => ['wireless headphones', 'work sneakers', 'minimal backpack', 'smart watch'],
        ]);
    }

    public function show(Request $request, Product $product, UserBehaviorService $behavior, RecommendationEngineService $engine)
    {
        abort_unless($product->is_active, 404);
        $product->load(['brand', 'category', 'reviews.user']);
        $behavior->trackProductView($request->user(), $product);

        return view('shop.products.show', [
            'product' => $product,
            'similar' => $engine->similarTo($product),
            'alsoBought' => $engine->customersAlsoBought($product),
        ]);
    }
}
