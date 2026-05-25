<x-app-layout>
    <x-slot name="header"><h1 class="text-3xl font-black tracking-tight">Cart</h1></x-slot>
    <div class="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[1fr_360px] lg:px-8">
        <div class="space-y-4">
            @forelse($cart->items as $item)
                <div class="surface flex gap-4 p-4">
                    <img src="{{ $item->product->image() }}" alt="{{ $item->product->name }}" class="h-28 w-24 rounded-2xl object-cover">
                    <div class="flex flex-1 flex-col justify-between">
                        <div>
                            <h2 class="font-black">{{ $item->product->name }}</h2>
                            <p class="text-sm text-slate-500">{{ $item->product->brand->name }}</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="font-bold">{{ $item->quantity }} &times; ${{ number_format((float) $item->product->price, 2) }}</p>
                            <form method="POST" action="{{ route('cart.destroy', $item->product) }}">@csrf @method('DELETE') <button class="text-sm font-bold text-rose-600">Remove</button></form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="surface p-10 text-center">
                    <h2 class="text-2xl font-black">Your cart is empty</h2>
                    <a href="{{ route('products.index') }}" class="btn-primary mt-5">Start shopping</a>
                </div>
            @endforelse
        </div>
        <aside class="surface h-fit p-6">
            @php($subtotal = $cart->items->sum(fn($item) => $item->quantity * $item->product->price))
            @php($tax = $subtotal * .08)
            @php($shipping = $subtotal > 0 ? 9.99 : 0)
            <h2 class="text-xl font-black">Checkout summary</h2>
            <div class="mt-5 space-y-3 text-sm">
                <div class="flex justify-between"><span>Subtotal</span><strong>${{ number_format($subtotal, 2) }}</strong></div>
                <div class="flex justify-between"><span>Estimated tax</span><strong>${{ number_format($tax, 2) }}</strong></div>
                <div class="flex justify-between"><span>Shipping</span><strong>${{ number_format($shipping, 2) }}</strong></div>
                <div class="flex justify-between border-t border-slate-200 pt-3 text-lg dark:border-white/10"><span>Total</span><strong>${{ number_format($subtotal + $tax + $shipping, 2) }}</strong></div>
            </div>
            @auth
                <a href="{{ route('checkout.show') }}" class="btn-primary mt-6 w-full {{ $cart->items->isEmpty() ? 'pointer-events-none opacity-50' : '' }}">Proceed to checkout</a>
            @else
                <a href="{{ route('login') }}" class="btn-primary mt-6 w-full">Sign in to checkout</a>
            @endauth
        </aside>
    </div>
</x-app-layout>
