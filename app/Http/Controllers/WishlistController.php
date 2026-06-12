<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', auth()->id())
            ->with('product.primaryImage', 'product.category')
            ->get();

        return view('wishlist.index', compact('wishlistItems'));
    }

    public function add($productId)
    {
        $userId = auth()->id();
        $product = Product::findOrFail($productId);

        $exists = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

        if (!$exists) {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            return redirect()->back()->with('success', $product->name . ' has been added to your wishlist.');
        }

        return redirect()->back()->with('success', $product->name . ' is already in your wishlist.');
    }

    public function destroy($id)
    {
        $wishlistItem = Wishlist::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $productName = $wishlistItem->product->name;
        $wishlistItem->delete();

        return redirect()->back()->with('success', $productName . ' has been removed from your wishlist.');
    }

    public function moveToCart($id)
    {
        $wishlistItem = Wishlist::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $productId = $wishlistItem->product_id;
        $userId = auth()->id();

        $cartItem = CartItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => 1,
            ]);
        }

        $productName = $wishlistItem->product->name;
        $wishlistItem->delete();

        return redirect()->back()->with('success', $productName . ' has been moved to your cart.');
    }
}
