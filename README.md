# SmartShop AI

SmartShop AI is a Laravel 11 personalized e-commerce recommendation platform built for a major project, internship showcase, or MVP demo.

Tagline: **Discover products tailored just for you.**

## Features

- Laravel Breeze authentication: register, login, logout, forgot password, remember me, email verification, and profile management.
- Customer and admin roles.
- Product catalog with categories, subcategories, brands, tags, search, filters, sorting, pagination, lazy-loaded imagery, cart, wishlist, checkout UI, reviews, and ratings.
- Recommendation engine service using browsing history, searches, categories, wishlist, cart, purchase behavior, reviews, popularity, and rating scores.
- Sections for Recommended For You, Trending Products, Similar Products, Recently Viewed, Based On Your Interests, and Customers Also Bought.
- Smart search suggestions with Alpine.js.
- Admin analytics for users, sales, most viewed products, category popularity, activity signals, and recommendation performance indicators.
- Tailwind CSS interface with responsive navigation, dark mode support, reusable product/search/rating components, premium cards, smooth hover states, and mobile-first layouts.
- Seeded demo catalog with 120 products, categories, brands, users, wishlists, orders, reviews, and behavior events.

## Tech Stack

- Laravel 11
- PHP 8.2+ locally, PHP 8.3+ recommended for production
- MySQL for production
- Blade, Tailwind CSS, Alpine.js
- Breeze Auth
- Eloquent ORM, migrations, seeders, factories

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Create a MySQL database named `smartshop_ai`, then update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smartshop_ai
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and demo data:

```bash
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Demo admin login:

```text
Email: admin@smartshop.test
Password: password
```

## Important Paths

- Recommendation logic: `app/Services/RecommendationEngineService.php`
- Behavior tracking: `app/Services/UserBehaviorService.php`
- Storefront routes: `routes/web.php`
- Product catalog UI: `resources/views/shop/products`
- Admin analytics: `resources/views/admin/dashboard.blade.php`
- SmartShop schema: `database/migrations/2026_05_17_000001_create_smartshop_tables.php`

## Screenshots

Add screenshots here after running the app:

- Homepage
- Product catalog
- Product detail
- Customer dashboard
- Admin analytics

## Future Improvements

- Queue recommendation recalculation jobs.
- Add Laravel Scout with Meilisearch or Algolia.
- Add Livewire infinite scroll.
- Add personalized email campaigns.
- Add product comparison and chatbot assistant persistence.
- Add payment gateway integration.
