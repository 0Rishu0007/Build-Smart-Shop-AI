<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-slate-950 dark:text-white">Product discovery</h1>
                <p class="mt-1 text-slate-500">Search, filter, and sort a personalized catalog.</p>
            </div>
            <div class="w-full max-w-xl"><x-shop.search-bar /></div>
        </div>
    </x-slot>

    <div class="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[280px_1fr] lg:px-8">
        <aside class="surface h-fit p-5">
            <form class="space-y-5">
                <input type="hidden" name="q" value="{{ request('q') }}">
                <div>
                    <label class="text-sm font-bold">Category</label>
                    <select name="category" class="mt-2 w-full rounded-xl border-slate-200 dark:border-white/10 dark:bg-slate-900">
                        <option value="">All categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-bold">Brand</label>
                    <select name="brand" class="mt-2 w-full rounded-xl border-slate-200 dark:border-white/10 dark:bg-slate-900">
                        <option value="">All brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->slug }}" @selected(request('brand') === $brand->slug)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <input name="min_price" value="{{ request('min_price') }}" placeholder="Min $" class="rounded-xl border-slate-200 dark:border-white/10 dark:bg-slate-900">
                    <input name="max_price" value="{{ request('max_price') }}" placeholder="Max $" class="rounded-xl border-slate-200 dark:border-white/10 dark:bg-slate-900">
                </div>
                <select name="rating" class="w-full rounded-xl border-slate-200 dark:border-white/10 dark:bg-slate-900">
                    <option value="">Any rating</option>
                    @for($i = 4; $i >= 1; $i--)
                        <option value="{{ $i }}" @selected(request('rating') == $i)>{{ $i }} stars and up</option>
                    @endfor
                </select>
                <select name="sort" class="w-full rounded-xl border-slate-200 dark:border-white/10 dark:bg-slate-900">
                    @foreach(['latest' => 'Latest', 'popular' => 'Popular', 'rated' => 'Highest Rated', 'price_low' => 'Price Low to High', 'price_high' => 'Price High to Low'] as $key => $label)
                        <option value="{{ $key }}" @selected(request('sort', 'latest') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="btn-primary w-full">Apply filters</button>
            </form>
            <div class="mt-6">
                <p class="text-sm font-bold">Popular searches</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($popularSearches as $term)
                        <a class="chip" href="{{ route('products.index', ['q' => $term]) }}">{{ $term }}</a>
                    @endforeach
                </div>
            </div>
        </aside>

        <main>
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                @forelse($products as $product)
                    <x-shop.product-card :product="$product" />
                @empty
                    <div class="surface col-span-full p-10 text-center">
                        <h2 class="text-2xl font-black">No products found</h2>
                        <p class="mt-2 text-slate-500">Try a broader search or reset your filters.</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-8">{{ $products->links() }}</div>
        </main>
    </div>
</x-app-layout>
