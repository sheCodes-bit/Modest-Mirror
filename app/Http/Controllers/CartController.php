<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', auth()->id())
            ->with('product.primaryImage')
            ->get();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->quantity * $item->product->price;
        }

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request, $productId)
    {
        $quantity = $request->input('quantity', 1);
        $product = Product::findOrFail($productId);
        $userId = auth()->id();

        $cartItem = CartItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->back()->with('success', $product->name . ' has been added to your cart.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return redirect()->back()->with('success', 'Cart updated successfully.');
    }

    public function remove($id)
    {
        $cartItem = CartItem::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $productName = $cartItem->product->name;
        $cartItem->delete();

        return redirect()->back()->with('success', $productName . ' has been removed from your cart.');
    }
}
