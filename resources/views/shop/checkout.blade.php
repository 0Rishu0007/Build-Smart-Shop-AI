<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-slate-950 dark:text-white">Checkout</h1>
                <p class="mt-1 text-slate-500">Choose delivery, payment, and review your final order.</p>
            </div>
            <a href="{{ route('cart.index') }}" class="btn-soft">Back to cart</a>
        </div>
    </x-slot>

    <div class="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[1fr_390px] lg:px-8">
        @if($cart->items->isEmpty())
            <div class="surface p-10 text-center lg:col-span-2">
                <h2 class="text-2xl font-black">Your cart is empty</h2>
                <p class="mt-2 text-slate-500">Add products before choosing checkout options.</p>
                <a href="{{ route('products.index') }}" class="btn-primary mt-5">Continue shopping</a>
            </div>
        @else
            <form method="POST" action="{{ route('checkout.store') }}" x-data="{ delivery: '{{ old('delivery_speed', 'standard') }}', shipping: {{ old('delivery_speed') === 'priority' ? '29.99' : (old('delivery_speed') === 'express' ? '19.99' : '9.99') }}, subtotal: {{ $totals['subtotal'] }}, tax: {{ $totals['tax'] }}, setDelivery(value){ this.delivery = value; this.shipping = value === 'priority' ? 29.99 : value === 'express' ? 19.99 : 9.99 }, total(){ return this.subtotal + this.tax + this.shipping } }" class="space-y-6">
                @csrf

                <section class="surface p-6">
                    <h2 class="text-xl font-black">Shipping details</h2>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-bold">Full name</label>
                            <input name="full_name" value="{{ old('full_name', auth()->user()->name) }}" class="mt-2 w-full rounded-xl border-slate-200 dark:border-white/10 dark:bg-slate-900" required>
                            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                        </div>
                        <div>
                            <label class="text-sm font-bold">Phone</label>
                            <input name="phone" value="{{ old('phone') }}" class="mt-2 w-full rounded-xl border-slate-200 dark:border-white/10 dark:bg-slate-900" required>
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-sm font-bold">Address</label>
                            <input name="address" value="{{ old('address') }}" class="mt-2 w-full rounded-xl border-slate-200 dark:border-white/10 dark:bg-slate-900" required>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                        <div>
                            <label class="text-sm font-bold">City</label>
                            <input name="city" value="{{ old('city') }}" class="mt-2 w-full rounded-xl border-slate-200 dark:border-white/10 dark:bg-slate-900" required>
                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                        </div>
                        <div>
                            <label class="text-sm font-bold">State</label>
                            <input name="state" value="{{ old('state') }}" class="mt-2 w-full rounded-xl border-slate-200 dark:border-white/10 dark:bg-slate-900" required>
                            <x-input-error :messages="$errors->get('state')" class="mt-2" />
                        </div>
                        <div>
                            <label class="text-sm font-bold">Postal code</label>
                            <input name="postal_code" value="{{ old('postal_code') }}" class="mt-2 w-full rounded-xl border-slate-200 dark:border-white/10 dark:bg-slate-900" required>
                            <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                        </div>
                    </div>
                </section>

                <section class="surface p-6">
                    <h2 class="text-xl font-black">Delivery options</h2>
                    <div class="mt-5 grid gap-3 md:grid-cols-3">
                        @foreach([
                            'standard' => ['Standard', '3-5 business days', '$9.99'],
                            'express' => ['Express', '2 business days', '$19.99'],
                            'priority' => ['Priority', 'Next business day', '$29.99'],
                        ] as $key => $option)
                            <label class="cursor-pointer rounded-2xl border border-slate-200 p-4 transition has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 dark:border-white/10 dark:has-[:checked]:bg-indigo-500/10">
                                <input type="radio" name="delivery_speed" value="{{ $key }}" class="sr-only" @checked(old('delivery_speed', 'standard') === $key) @change="setDelivery('{{ $key }}')">
                                <span class="block font-black">{{ $option[0] }}</span>
                                <span class="mt-1 block text-sm text-slate-500">{{ $option[1] }}</span>
                                <span class="mt-3 block text-sm font-bold">{{ $option[2] }}</span>
                            </label>
                        @endforeach
                    </div>
                </section>

                <section class="surface p-6">
                    <h2 class="text-xl font-black">Payment method</h2>
                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        @foreach([
                            'card' => ['Credit or debit card', 'Demo card payment'],
                            'upi' => ['UPI', 'Pay through UPI intent'],
                            'wallet' => ['SmartShop Wallet', 'Use saved wallet balance'],
                            'cod' => ['Cash on delivery', 'Pay when the order arrives'],
                        ] as $key => $option)
                            <label class="cursor-pointer rounded-2xl border border-slate-200 p-4 transition has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 dark:border-white/10 dark:has-[:checked]:bg-indigo-500/10">
                                <input type="radio" name="payment_method" value="{{ $key }}" class="sr-only" @checked(old('payment_method', 'card') === $key)>
                                <span class="block font-black">{{ $option[0] }}</span>
                                <span class="mt-1 block text-sm text-slate-500">{{ $option[1] }}</span>
                            </label>
                        @endforeach
                    </div>
                </section>

                <button class="btn-primary w-full sm:w-auto">Place order</button>
            </form>

            <aside class="surface h-fit p-6">
                <h2 class="text-xl font-black">Order review</h2>
                <div class="mt-5 space-y-4">
                    @foreach($cart->items as $item)
                        <div class="flex gap-3">
                            <img src="{{ $item->product->image() }}" alt="{{ $item->product->name }}" class="h-16 w-14 rounded-xl object-cover">
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-bold">{{ $item->product->name }}</p>
                                <p class="text-sm text-slate-500">{{ $item->quantity }} &times; ${{ number_format((float) $item->product->price, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 space-y-3 border-t border-slate-200 pt-5 text-sm dark:border-white/10">
                    <div class="flex justify-between"><span>Subtotal</span><strong>${{ number_format($totals['subtotal'], 2) }}</strong></div>
                    <div class="flex justify-between"><span>Estimated tax</span><strong>${{ number_format($totals['tax'], 2) }}</strong></div>
                    <div class="flex justify-between"><span>Shipping</span><strong x-text="'$' + shipping.toFixed(2)">${{ number_format($totals['shipping'], 2) }}</strong></div>
                    <div class="flex justify-between border-t border-slate-200 pt-3 text-lg dark:border-white/10">
                        <span>Total</span>
                        <strong x-text="'$' + total().toFixed(2)">${{ number_format($totals['total'], 2) }}</strong>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl bg-emerald-50 p-4 text-sm text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-200">
                    Demo checkout creates a real local order, clears the cart, and updates product purchase signals for recommendations.
                </div>
            </aside>
        @endif
    </div>
</x-app-layout>
