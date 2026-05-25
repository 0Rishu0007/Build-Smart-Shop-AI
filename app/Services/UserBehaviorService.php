<?php

namespace App\Services;

use App\Models\BrowsingHistory;
use App\Models\Product;
use App\Models\Search;
use App\Models\User;

class UserBehaviorService
{
    private const WEIGHTS = [
        'view' => 3,
        'search_click' => 4,
        'wishlist' => 7,
        'cart' => 9,
        'purchase' => 15,
        'review' => 11,
    ];

    public function trackProductView(?User $user, Product $product, string $type = 'view'): void
    {
        $score = self::WEIGHTS[$type] ?? 1;

        BrowsingHistory::create([
            'user_id' => $user?->id,
            'product_id' => $product->id,
            'category_id' => $product->category_id,
            'interaction_type' => $type,
            'interaction_score' => $score,
        ]);

        $product->increment('views_count');
    }

    public function trackSearch(?User $user, string $query, int $resultsCount): void
    {
        if (trim($query) === '') {
            return;
        }

        Search::create([
            'user_id' => $user?->id,
            'query' => str($query)->lower()->squish(),
            'results_count' => $resultsCount,
        ]);
    }
}
