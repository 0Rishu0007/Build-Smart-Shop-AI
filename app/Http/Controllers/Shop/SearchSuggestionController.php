<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchSuggestionController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = str($request->string('q'))->lower()->squish();

        if ($query->length() < 2) {
            return response()->json([]);
        }

        return Product::active()
            ->where('name', 'like', "%{$query}%")
            ->orderByDesc('views_count')
            ->limit(6)
            ->get(['name', 'slug', 'price'])
            ->map(fn ($product) => [
                'name' => $product->name,
                'url' => route('products.show', $product),
                'price' => '$'.number_format((float) $product->price, 2),
            ]);
    }
}
