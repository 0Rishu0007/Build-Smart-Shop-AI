<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-950 dark:text-white">Your SmartShop dashboard</h1>
            <p class="mt-1 text-slate-500">Recommendations, activity, wishlist, and orders in one calm workspace.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-10 px-4 py-8 sm:px-6 lg:px-8">
        <section class="grid gap-5 md:grid-cols-4">
            @foreach([
                ['Recommended', $recommended->count(), 'Products matched to your signals'],
                ['Viewed', $recentlyViewed->count(), 'Recent browsing events'],
                ['Wishlist', $wishlist->count(), 'Saved products'],
                ['Orders', $orders->count(), 'Latest purchases'],
            ] as $stat)
                <div class="surface p-5">
                    <p class="text-sm font-bold text-slate-500">{{ $stat[0] }}</p>
                    <p class="mt-2 text-3xl font-black">{{ $stat[1] }}</p>
                    <p class="mt-1 text-sm text-slate-500">{{ $stat[2] }}</p>
                </div>
            @endforeach
        </section>

        <section>
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-2xl font-black">Recommended for you</h2>
                <a href="{{ route('products.index') }}" class="btn-soft">Explore more</a>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($recommended as $product)
                    <x-shop.product-card :product="$product" />
                @endforeach
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <div class="surface p-6">
                <h2 class="text-xl font-black">Recently viewed</h2>
                <div class="mt-5 space-y-4">
                    @forelse($recentlyViewed as $product)
                        <a href="{{ route('products.show', $product) }}" class="flex items-center gap-4">
                            <img src="{{ $product->image() }}" alt="{{ $product->name }}" class="h-16 w-16 rounded-xl object-cover">
                            <div>
                                <p class="font-bold">{{ $product->name }}</p>
                                <p class="text-sm text-slate-500">{{ $product->brand->name ?? '' }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="text-slate-500">Start browsing to build your activity trail.</p>
                    @endforelse
                </div>
            </div>
            <div class="surface p-6">
                <h2 class="text-xl font-black">Order history</h2>
                <div class="mt-5 space-y-4">
                    @forelse($orders as $order)
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 p-4 dark:bg-white/5">
                            <div>
                                <p class="font-bold">{{ $order->order_number }}</p>
                                <p class="text-sm text-slate-500">{{ ucfirst($order->status) }} · {{ $order->items->count() }} items</p>
                            </div>
                            <strong>${{ number_format((float) $order->total, 2) }}</strong>
                        </div>
                    @empty
                        <p class="text-slate-500">Your future purchases will appear here.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
