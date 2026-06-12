<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', auth()->id())
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->withErrors('Your cart is empty. Add products before checking out.');
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->quantity * $item->product->price;
        }

        return view('checkout.index', compact('cartItems', 'subtotal'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'min:3', 'max:100'],
            'shipping_phone' => ['required', 'regex:/^03[0-9]{9}$/'],
            'shipping_city' => 'required|string|max:100',
            'shipping_address' => 'required|string|min:10|max:255'],
            [ 
            'shipping_name.regex' => 'Name can only contain letters and spaces.',
            'shipping_phone.regex' => 'Enter a valid Pakistani mobile number (03XXXXXXXXX).',
        ]);

        $userId = auth()->id();
        $cartItems = CartItem::where('user_id', $userId)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->withErrors('Your cart is empty.');
        }

        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item->quantity * $item->product->price;
        }

        $orderNumber = 'MM-' . strtoupper(Str::random(10));

        $order = Order::create([
            'user_id' => $userId,
            'order_number' => $orderNumber,
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'shipping_name' => $request->shipping_name,
            'shipping_email' => $request->shipping_email,
            'shipping_phone' => $request->shipping_phone,
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        CartItem::where('user_id', $userId)->delete();

        return redirect()->route('checkout.success', $orderNumber)->with('success', 'Thank you! Your luxury order has been placed.');
    }

    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->with('items.product')
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }
}
