<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $cart = $this->cart($request)->load('items.product.brand');

        return view('shop.checkout', [
            'cart' => $cart,
            'totals' => $this->totals($cart, 'standard'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:80'],
            'state' => ['required', 'string', 'max:80'],
            'postal_code' => ['required', 'string', 'max:20'],
            'delivery_speed' => ['required', 'in:standard,express,priority'],
            'payment_method' => ['required', 'in:card,cod,upi,wallet'],
        ]);

        $cart = $this->cart($request)->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Add products before checkout.');
        }

        $totals = $this->totals($cart, $validated['delivery_speed']);

        DB::transaction(function () use ($request, $cart, $validated, $totals) {
            $order = $request->user()->orders()->create([
                'order_number' => 'SSAI-'.strtoupper(Str::random(8)),
                'status' => $validated['payment_method'] === 'cod' ? 'confirmed' : 'processing',
                'subtotal' => $totals['subtotal'],
                'tax' => $totals['tax'],
                'shipping' => $totals['shipping'],
                'total' => $totals['total'],
                'shipping_address' => [
                    'name' => $validated['full_name'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'city' => $validated['city'],
                    'state' => $validated['state'],
                    'postal_code' => $validated['postal_code'],
                    'delivery_speed' => $validated['delivery_speed'],
                    'payment_method' => $validated['payment_method'],
                ],
            ]);

            foreach ($cart->items as $item) {
                $lineTotal = $item->quantity * $item->product->price;

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price,
                    'total' => $lineTotal,
                ]);

                $item->product->increment('orders_count', $item->quantity);
                $item->product->decrement('stock', min($item->product->stock, $item->quantity));
            }

            $cart->items()->delete();
        });

        return redirect()->route('dashboard')->with('status', 'Order placed successfully. Your SmartShop recommendations will now get smarter.');
    }

    private function cart(Request $request): Cart
    {
        return Cart::firstOrCreate([
            'user_id' => $request->user()?->id,
            'session_id' => $request->user() ? null : $request->session()->getId(),
        ]);
    }

    private function totals(Cart $cart, string $deliverySpeed): array
    {
        $subtotal = (float) $cart->items->sum(fn ($item) => $item->quantity * $item->product->price);
        $shipping = match ($deliverySpeed) {
            'express' => $subtotal > 0 ? 19.99 : 0,
            'priority' => $subtotal > 0 ? 29.99 : 0,
            default => $subtotal > 0 ? 9.99 : 0,
        };
        $tax = $subtotal * .08;

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $subtotal + $tax + $shipping,
        ];
    }
}
