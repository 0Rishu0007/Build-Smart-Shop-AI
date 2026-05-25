<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $category = Category::query()->inRandomOrder()->first() ?? Category::create([
            'name' => 'Essentials',
            'slug' => 'essentials',
            'is_featured' => true,
        ]);
        $subcategory = Subcategory::query()->where('category_id', $category->id)->inRandomOrder()->first();
        $brandName = fake()->company();
        $brand = Brand::query()->inRandomOrder()->first() ?? Brand::create(['name' => $brandName, 'slug' => Str::slug($brandName)]);
        $name = $brand->name.' '.fake()->words(3, true);

        return [
            'category_id' => $category->id,
            'subcategory_id' => $subcategory?->id,
            'brand_id' => $brand->id,
            'name' => $name,
            'slug' => Str::slug($name.' '.Str::random(5)),
            'description' => fake()->paragraph(),
            'specifications' => ['Warranty' => '1 year'],
            'tags' => ['popular', 'recommended'],
            'gallery' => ['https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=900&q=80'],
            'price' => fake()->randomFloat(2, 20, 900),
            'stock' => fake()->numberBetween(0, 200),
            'views_count' => fake()->numberBetween(0, 2000),
            'orders_count' => fake()->numberBetween(0, 300),
            'rating_average' => fake()->randomFloat(1, 3, 5),
            'rating_count' => fake()->numberBetween(1, 500),
            'is_active' => true,
            'is_trending' => fake()->boolean(),
        ];
    }
}
