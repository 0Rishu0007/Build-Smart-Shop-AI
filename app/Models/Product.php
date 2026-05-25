<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'subcategory_id', 'brand_id', 'name', 'slug', 'description',
        'specifications', 'tags', 'gallery', 'price', 'compare_at_price', 'stock',
        'views_count', 'orders_count', 'rating_average', 'rating_count', 'is_active', 'is_trending',
    ];

    protected $casts = [
        'specifications' => 'array',
        'tags' => 'array',
        'gallery' => 'array',
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_trending' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function image(): string
    {
        return $this->gallery[0] ?? 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=900&q=80';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function discountPercent(): int
    {
        if (! $this->compare_at_price || $this->compare_at_price <= $this->price) {
            return 0;
        }

        return (int) round((($this->compare_at_price - $this->price) / $this->compare_at_price) * 100);
    }
}
