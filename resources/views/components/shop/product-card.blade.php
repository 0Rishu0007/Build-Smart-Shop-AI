@props(['product'])

<article class="group surface overflow-hidden">
    <a href="{{ route('products.show', $product) }}" class="block">
        <div class="relative aspect-[4/5] overflow-hidden bg-slate-100 dark:bg-slate-800">
            <img src="{{ $product->image() }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
            @if($product->discountPercent())
                <span class="absolute left-3 top-3 rounded-full bg-rose-500 px-3 py-1 text-xs font-bold text-white">{{ $product->discountPercent() }}% off</span>
            @endif
        </div>
    </a>
    <div class="space-y-3 p-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $product->brand->name ?? 'SmartShop' }}</p>
            <a href="{{ route('products.show', $product) }}" class="mt-1 line-clamp-2 font-semibold text-slate-950 hover:text-indigo-600 dark:text-white">{{ $product->name }}</a>
        </div>
        <x-shop.rating-stars :rating="$product->rating_average" :count="$product->rating_count" />
        <div class="flex items-center justify-between gap-3">
            <div>
                <span class="font-black text-slate-950 dark:text-white">${{ number_format((float) $product->price, 2) }}</span>
                @if($product->compare_at_price)
                    <span class="ml-1 text-xs text-slate-400 line-through">${{ number_format((float) $product->compare_at_price, 2) }}</span>
                @endif
            </div>
            <form method="POST" action="{{ route('cart.store', $product) }}">
                @csrf
                <button class="rounded-full bg-slate-950 px-3 py-2 text-xs font-bold text-white transition hover:bg-indigo-600 dark:bg-white dark:text-slate-950">Add</button>
            </form>
        </div>
    </div>
</article>
