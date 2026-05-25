<x-app-layout>
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(79,70,229,.14),_transparent_34%),linear-gradient(135deg,_#f8fafc,_#ffffff_45%,_#eef2ff)] dark:bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,.24),_transparent_34%),linear-gradient(135deg,_#020617,_#0f172a_55%,_#111827)]"></div>
        <div class="relative mx-auto grid max-w-7xl gap-12 px-4 py-16 sm:px-6 lg:grid-cols-[1.05fr_.95fr] lg:px-8 lg:py-24">
            <div class="flex flex-col justify-center">
                <span class="mb-5 w-fit rounded-full border border-indigo-200 bg-white/70 px-4 py-2 text-sm font-bold text-indigo-700 shadow-sm dark:border-indigo-400/20 dark:bg-white/10 dark:text-indigo-200">AI-powered shopping intelligence</span>
                <h1 class="max-w-4xl text-5xl font-black leading-[1.02] tracking-tight text-slate-950 dark:text-white sm:text-7xl">Shopping that understands you.</h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600 dark:text-slate-300">Discover products tailored just for you. SmartShop AI learns from browsing, search, wishlist, cart, ratings, and purchases to make every visit feel personal.</p>
                <div class="mt-8 max-w-2xl">
                    <x-shop.search-bar />
                </div>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" class="btn-primary">Explore catalog</a>
                    <a href="{{ route('dashboard') }}" class="btn-soft">View recommendations</a>
                </div>
            </div>
            <div class="surface p-3">
                <div class="rounded-[1.2rem] bg-slate-950 p-4 text-white">
                    <div class="flex items-center justify-between border-b border-white/10 pb-4">
                        <div>
                            <p class="text-xs uppercase text-indigo-200">Recommendation preview</p>
                            <h2 class="text-2xl font-black">Your next favorites</h2>
                        </div>
                        <span class="rounded-full bg-emerald-400/15 px-3 py-1 text-xs font-bold text-emerald-200">Live scoring</span>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        @foreach($recommended->take(4) as $product)
                            <a href="{{ route('products.show', $product) }}" class="group overflow-hidden rounded-2xl bg-white/8">
                                <img src="{{ $product->image() }}" alt="{{ $product->name }}" class="aspect-square w-full object-cover opacity-90 transition group-hover:scale-105">
                                <div class="p-3">
                                    <p class="truncate text-sm font-bold">{{ $product->name }}</p>
                                    <p class="text-xs text-slate-300">{{ $product->category->name }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-end justify-between gap-4">
            <div>
                <p class="text-sm font-bold uppercase text-indigo-600">Trending now</p>
                <h2 class="text-3xl font-black tracking-tight text-slate-950 dark:text-white">Popular across SmartShop</h2>
            </div>
            <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="btn-soft">View all</a>
        </div>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-5">
            @foreach($trending as $product)
                <x-shop.product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <section class="bg-white py-14 dark:bg-slate-900/50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <p class="text-sm font-bold uppercase text-indigo-600">Shop smarter</p>
                <h2 class="text-3xl font-black tracking-tight text-slate-950 dark:text-white">Browse by intent, not endless aisles</h2>
            </div>
            <div class="grid gap-5 md:grid-cols-4">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="group relative overflow-hidden rounded-2xl bg-slate-900 p-6 text-white shadow-lg">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="absolute inset-0 h-full w-full object-cover opacity-45 transition group-hover:scale-105">
                        <div class="relative min-h-40">
                            <span class="text-3xl">{{ $category->icon }}</span>
                            <h3 class="mt-12 text-2xl font-black">{{ $category->name }}</h3>
                            <p class="text-sm text-slate-200">{{ $category->products_count }} curated products</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto grid max-w-7xl gap-6 px-4 py-14 sm:px-6 lg:grid-cols-3 lg:px-8">
        @foreach([
            ['Personalized discovery', 'Weighted scoring blends content-based matching with collaborative signals from similar shoppers.'],
            ['Smart search', 'Live suggestions, recent search memory, typo-tolerant matching, and product intent filters.'],
            ['Commerce ready', 'Cart, wishlist, order history, ratings, admin analytics, and scalable Eloquent relationships.'],
        ] as $feature)
            <div class="surface p-7">
                <h3 class="text-xl font-black text-slate-950 dark:text-white">{{ $feature[0] }}</h3>
                <p class="mt-3 leading-7 text-slate-600 dark:text-slate-300">{{ $feature[1] }}</p>
            </div>
        @endforeach
    </section>

    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-slate-950 px-8 py-12 text-white shadow-2xl shadow-slate-900/20 md:px-12">
            <div class="grid gap-8 md:grid-cols-[1fr_auto] md:items-center">
                <div>
                    <p class="text-sm font-bold uppercase text-indigo-200">Ready when you are</p>
                    <h2 class="mt-2 text-4xl font-black tracking-tight">Build your personal shopping graph.</h2>
                    <p class="mt-4 max-w-2xl text-slate-300">Sign in, explore products, add favorites, and watch recommendations become sharper with every interaction.</p>
                </div>
                <a href="{{ route('register') }}" class="btn-primary !bg-white !text-slate-950">Start free</a>
            </div>
        </div>
    </section>

    <footer class="border-t border-slate-200 bg-white py-8 dark:border-white/10 dark:bg-slate-950">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <p>SmartShop AI. Discover products tailored just for you.</p>
            <div class="flex gap-4">
                <a href="{{ route('products.index') }}">Catalog</a>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('cart.index') }}">Cart</a>
            </div>
        </div>
    </footer>
</x-app-layout>
