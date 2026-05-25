<x-app-layout>
    <x-slot name="header"><h1 class="text-3xl font-black tracking-tight">Wishlist</h1></x-slot>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($products as $product)
                <x-shop.product-card :product="$product" />
            @empty
                <div class="surface col-span-full p-10 text-center">
                    <h2 class="text-2xl font-black">No saved products yet</h2>
                    <a href="{{ route('products.index') }}" class="btn-primary mt-5">Find favorites</a>
                </div>
            @endforelse
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
    </div>
</x-app-layout>
