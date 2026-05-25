<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-2">
            <div x-data="{ active: '{{ $product->image() }}' }" class="space-y-4">
                <div class="surface overflow-hidden">
                    <img :src="active" alt="{{ $product->name }}" class="aspect-square w-full object-cover transition">
                </div>
                <div class="grid grid-cols-4 gap-3">
                    @foreach($product->gallery ?? [] as $image)
                        <button @click="active='{{ $image }}'" class="overflow-hidden rounded-2xl border border-slate-200 dark:border-white/10">
                            <img src="{{ $image }}" alt="" class="aspect-square w-full object-cover">
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="lg:pt-8">
                <p class="font-bold uppercase text-indigo-600">{{ $product->brand->name }} / {{ $product->category->name }}</p>
                <h1 class="mt-3 text-4xl font-black tracking-tight text-slate-950 dark:text-white">{{ $product->name }}</h1>
                <div class="mt-4"><x-shop.rating-stars :rating="$product->rating_average" :count="$product->rating_count" /></div>
                <p class="mt-6 text-lg leading-8 text-slate-600 dark:text-slate-300">{{ $product->description }}</p>
                <div class="mt-6 flex items-end gap-3">
                    <span class="text-4xl font-black">${{ number_format((float) $product->price, 2) }}</span>
                    @if($product->compare_at_price)
                        <span class="text-lg text-slate-400 line-through">${{ number_format((float) $product->compare_at_price, 2) }}</span>
                    @endif
                </div>
                <div class="mt-8 flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('cart.store', $product) }}">@csrf <button class="btn-primary">Add to cart</button></form>
                    @auth
                        <form method="POST" action="{{ route('wishlist.toggle', $product) }}">@csrf <button class="btn-soft">Save to wishlist</button></form>
                    @else
                        <a href="{{ route('login') }}" class="btn-soft">Log in to save</a>
                    @endauth
                </div>
                <div class="mt-8 grid gap-3 sm:grid-cols-2">
                    @foreach(($product->specifications ?? []) as $key => $value)
                        <div class="surface p-4">
                            <p class="text-xs font-bold uppercase text-slate-500">{{ $key }}</p>
                            <p class="mt-1 font-semibold">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <section class="mt-16">
            <h2 class="text-2xl font-black">Reviews and rating breakdown</h2>
            <div class="mt-5 grid gap-4 md:grid-cols-3">
                @forelse($product->reviews->take(6) as $review)
                    <div class="surface p-5">
                        <x-shop.rating-stars :rating="$review->rating" />
                        <h3 class="mt-3 font-bold">{{ $review->title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $review->body }}</p>
                        <p class="mt-3 text-xs font-semibold text-slate-500">{{ $review->user->name }}</p>
                    </div>
                @empty
                    <p class="text-slate-500">No reviews yet.</p>
                @endforelse
            </div>
        </section>

        <section class="mt-16">
            <h2 class="text-2xl font-black">Similar products</h2>
            <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($similar as $item)
                    <x-shop.product-card :product="$item" />
                @endforeach
            </div>
        </section>

        <section class="mt-16">
            <h2 class="text-2xl font-black">Customers also bought</h2>
            <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($alsoBought as $item)
                    <x-shop.product-card :product="$item" />
                @endforeach
            </div>
        </section>
    </div>
</x-app-layout>
