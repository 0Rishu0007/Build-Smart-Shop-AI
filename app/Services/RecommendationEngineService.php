<?php

namespace App\Services;

use App\Models\BrowsingHistory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RecommendationEngineService
{
    public function recommendedFor(?User $user, int $limit = 12): Collection
    {
        if (! $user) {
            return $this->trending($limit);
        }

        return Cache::remember("recommendations.user.{$user->id}.{$limit}", now()->addMinutes(10), function () use ($user, $limit) {
            $signals = BrowsingHistory::query()
                ->select('category_id', DB::raw('SUM(interaction_score) as weight'))
                ->where('user_id', $user->id)
                ->whereNotNull('category_id')
                ->groupBy('category_id')
                ->pluck('weight', 'category_id');

            $seen = BrowsingHistory::where('user_id', $user->id)->pluck('product_id');
            $wishlistBrands = $user->wishlist()->pluck('brand_id')->filter();

            return Product::query()
                ->active()
                ->with(['brand', 'category'])
                ->whereNotIn('id', $seen)
                ->select('products.*')
                ->selectRaw('((rating_average * 12) + (views_count * .025) + (orders_count * .4)) as base_score')
                ->when($signals->isNotEmpty(), fn ($query) => $query->whereIn('category_id', $signals->keys()))
                ->when($wishlistBrands->isNotEmpty(), fn ($query) => $query->orWhereIn('brand_id', $wishlistBrands))
                ->orderByDesc('base_score')
                ->limit($limit)
                ->get()
                ->whenEmpty(fn () => $this->trending($limit));
        });
    }

    public function trending(int $limit = 12): Collection
    {
        return Product::active()
            ->with(['brand', 'category'])
            ->orderByRaw('(views_count * .35) + (orders_count * 2) + (rating_average * 10) desc')
            ->limit($limit)
            ->get();
    }

    public function similarTo(Product $product, int $limit = 8): Collection
    {
        return Product::active()
            ->with(['brand', 'category'])
            ->whereKeyNot($product->id)
            ->where(function ($query) use ($product) {
                $query->where('category_id', $product->category_id)
                    ->orWhere('brand_id', $product->brand_id);
            })
            ->orderByDesc('rating_average')
            ->orderByDesc('views_count')
            ->limit($limit)
            ->get();
    }

    public function customersAlsoBought(Product $product, int $limit = 8): Collection
    {
        return Product::active()
            ->with(['brand', 'category'])
            ->whereKeyNot($product->id)
            ->where('category_id', $product->category_id)
            ->orderByDesc('orders_count')
            ->limit($limit)
            ->get();
    }
}
