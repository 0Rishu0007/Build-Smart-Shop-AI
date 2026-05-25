<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\BrowsingHistory;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'SmartShop Admin',
            'email' => 'admin@smartshop.test',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $users = User::factory(24)->create(['email_verified_at' => now()]);

        $brands = collect(['Apple', 'Nike', 'Samsung', 'Sony', 'Adidas', 'Zara', 'H&M', 'Dyson', 'Logitech', 'Boat', 'Urbanic', 'Shopify Labs'])
            ->map(fn ($name) => Brand::create(['name' => $name, 'slug' => Str::slug($name)]));

        $categoryData = [
            ['Electronics', '⚡', 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80', ['Audio', 'Wearables', 'Laptops']],
            ['Fashion', '◆', 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&q=80', ['Sneakers', 'Outerwear', 'Accessories']],
            ['Beauty', '✦', 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=900&q=80', ['Skincare', 'Fragrance', 'Makeup']],
            ['Home', '⌂', 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=900&q=80', ['Kitchen', 'Decor', 'Cleaning']],
            ['Fitness', '◉', 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=900&q=80', ['Training', 'Recovery', 'Athleisure']],
            ['Work Essentials', '▣', 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=900&q=80', ['Bags', 'Desk Gear', 'Productivity']],
        ];

        $categories = collect($categoryData)->map(function ($data) {
            $category = Category::create([
                'name' => $data[0],
                'slug' => Str::slug($data[0]),
                'icon' => $data[1],
                'image_url' => $data[2],
                'is_featured' => true,
            ]);

            collect($data[3])->each(fn ($name) => Subcategory::create([
                'category_id' => $category->id,
                'name' => $name,
                'slug' => Str::slug($category->name.' '.$name),
            ]));

            return $category->load('subcategories');
        });

        $images = [
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1585386959984-a41552231658?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=900&q=80',
        ];

        foreach (range(1, 120) as $i) {
            $category = $categories->random();
            $subcategory = $category->subcategories->random();
            $brand = $brands->random();
            $name = $brand->name.' '.fake()->randomElement(['Pro', 'Air', 'Studio', 'Flex', 'Nova', 'Luxe', 'Core', 'Ultra']).' '.fake()->randomElement(['Headphones', 'Sneakers', 'Backpack', 'Serum', 'Watch', 'Jacket', 'Desk Lamp', 'Keyboard', 'Bottle', 'Speaker'])." {$i}";
            $price = fake()->randomFloat(2, 19, 1299);

            Product::create([
                'category_id' => $category->id,
                'subcategory_id' => $subcategory->id,
                'brand_id' => $brand->id,
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => fake()->paragraph(3),
                'specifications' => [
                    'Material' => fake()->randomElement(['Aluminum', 'Organic cotton', 'Vegan leather', 'Recycled polymer']),
                    'Warranty' => fake()->randomElement(['1 year', '2 years', '6 months']),
                    'Fit' => fake()->randomElement(['Compact', 'Regular', 'Oversized', 'Travel ready']),
                    'AI Match' => fake()->randomElement(['High intent', 'Style aligned', 'Budget friendly', 'Trending']),
                ],
                'tags' => fake()->randomElements(['premium', 'minimal', 'wireless', 'eco', 'popular', 'gift', 'new'], 3),
                'gallery' => fake()->randomElements($images, 4),
                'price' => $price,
                'compare_at_price' => fake()->boolean(55) ? $price * fake()->randomFloat(2, 1.08, 1.45) : null,
                'stock' => fake()->numberBetween(0, 180),
                'views_count' => fake()->numberBetween(20, 5200),
                'orders_count' => fake()->numberBetween(0, 680),
                'rating_average' => fake()->randomFloat(1, 3.5, 5),
                'rating_count' => fake()->numberBetween(5, 900),
                'is_trending' => fake()->boolean(35),
            ]);
        }

        $products = Product::all();

        $users->each(function (User $user) use ($products) {
            $products->random(8)->each(fn ($product) => BrowsingHistory::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'category_id' => $product->category_id,
                'interaction_type' => fake()->randomElement(['view', 'search_click', 'wishlist', 'cart', 'purchase']),
                'interaction_score' => fake()->numberBetween(2, 15),
            ]));

            $user->wishlist()->sync($products->random(4)->pluck('id'));

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'SSAI-'.strtoupper(Str::random(8)),
                'status' => fake()->randomElement(['processing', 'shipped', 'delivered']),
                'subtotal' => 0,
                'tax' => 0,
                'shipping' => 9.99,
                'total' => 0,
                'shipping_address' => ['city' => fake()->city(), 'country' => 'United States'],
            ]);

            $subtotal = 0;
            $products->random(3)->each(function ($product) use ($order, &$subtotal) {
                $quantity = fake()->numberBetween(1, 2);
                $lineTotal = $quantity * $product->price;
                $subtotal += $lineTotal;
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'total' => $lineTotal,
                ]);
            });

            $order->update(['subtotal' => $subtotal, 'tax' => $subtotal * .08, 'total' => $subtotal * 1.08 + 9.99]);
        });

        $products->random(70)->each(fn ($product) => Review::create([
            'user_id' => $users->random()->id,
            'product_id' => $product->id,
            'rating' => fake()->numberBetween(3, 5),
            'title' => fake()->randomElement(['Exactly what I needed', 'Premium feel', 'Great everyday pick', 'Worth the price']),
            'body' => fake()->sentence(18),
            'is_verified_purchase' => true,
        ]));

        $admin->wishlist()->sync($products->random(5)->pluck('id'));
    }
}
