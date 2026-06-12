<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\ArAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    public function dashboard()
    {
         if (!auth()->user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }

        $stats = [
            'users' => User::count(),
            'products' => Product::count(),
            'orders' => Order::count(),
            'revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
        ];

        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }

    // CATEGORIES CRUD
    public function categories()
    {
        $categories = Category::withCount('products')->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name|max:255',
            'description' => 'nullable|string'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string'
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }

    // PRODUCTS CRUD
    public function products()
    {
        $products = Product::with('category', 'primaryImage')->get();
        $categories = Category::all();
        return view('admin.products', compact('products', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'required|string',
            'price' => 'required|numeric|min:800|max:2500',
            'fabric_type' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'fabric_type' => $request->fabric_type,
            'color' => $request->color,
        ]);

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = 'product_' . $product->id . '_' . time() . '.' . $imageFile->getClientOriginalExtension();
            
            $prodDir = public_path('images/products');
            if (!File::exists($prodDir)) {
                File::makeDirectory($prodDir, 0777, true);
            }
            
            $imageFile->move($prodDir, $imageName);
            $imagePath = '/images/products/' . $imageName;

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $imagePath,
                'is_primary' => true
            ]);
        }

        return redirect()->back()->with('success', 'Product added successfully.');
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name,' . $id,
            'description' => 'required|string',
            'price' => 'required|numeric|min:800|max:2500',
            'fabric_type' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'fabric_type' => $request->fabric_type,
            'color' => $request->color,
        ]);

        if ($request->hasFile('image')) {
            $oldPrimary = ProductImage::where('product_id', $product->id)->where('is_primary', true)->first();
            if ($oldPrimary) {
                $oldPath = public_path($oldPrimary->image_path);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
                $oldPrimary->delete();
            }

            $imageFile = $request->file('image');
            $imageName = 'product_' . $product->id . '_' . time() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('images/products'), $imageName);
            $imagePath = '/images/products/' . $imageName;

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $imagePath,
                'is_primary' => true
            ]);
        }

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        
        $images = ProductImage::where('product_id', $product->id)->get();
        foreach ($images as $img) {
            $filePath = public_path($img->image_path);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        if ($product->ar_overlay_path) {
            $overlayPath = public_path($product->ar_overlay_path);
            if (File::exists($overlayPath)) {
                File::delete($overlayPath);
            }
        }

        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    // ORDERS MANAGEMENT
    public function orders()
    {
        $orders = Order::with('user', 'items.product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    // USERS MANAGEMENT
    public function users()
    {
        $users = User::withCount('orders')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users', compact('users'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return redirect()->back()->withErrors('You cannot delete your own admin account.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    // AR ASSETS MANAGEMENT
    public function arAssets()
    {
        $assets = ArAsset::with('product')->get();
        $products = Product::whereNull('ar_overlay_path')->get();
        $allProducts = Product::all();
        return view('admin.ar_assets', compact('assets', 'products', 'allProducts'));
    }

    public function uploadArAsset(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'overlay' => 'required|image|mimes:png|max:2048',
            'scale_factor' => 'required|numeric|min:0.5|max:2.0',
            'offset_y' => 'required|numeric|min:-1.0|max:1.0',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->hasFile('overlay')) {
            $overlayFile = $request->file('overlay');
            $overlayName = 'overlay_' . $product->id . '_' . time() . '.png';
            
            $overlayDir = public_path('images/overlays');
            if (!File::exists($overlayDir)) {
                File::makeDirectory($overlayDir, 0777, true);
            }

            $overlayFile->move($overlayDir, $overlayName);
            $overlayPath = '/images/overlays/' . $overlayName;

            if ($product->ar_overlay_path) {
                $oldPath = public_path($product->ar_overlay_path);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $product->update([
                'ar_overlay_path' => $overlayPath
            ]);

            ArAsset::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'name' => $request->name,
                    'overlay_image_path' => $overlayPath,
                    'scale_factor' => $request->scale_factor,
                    'offset_y' => $request->offset_y,
                ]
            );
        }

        return redirect()->back()->with('success', 'AR Asset uploaded and linked successfully.');
    }

    // REVIEWS MODERATION
    public function reviews()
    {
        $reviews = \App\Models\Review::with(['user', 'product'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.reviews', compact('reviews'));
    }

    public function deleteReview($id)
    {
        $review = \App\Models\Review::findOrFail($id);
        $review->delete();
        return redirect()->back()->with('success', 'Review deleted successfully.');
    }

    // NEWSLETTER SUBSCRIBERS
    public function subscribers()
    {
        $subscribers = \App\Models\NewsletterSubscriber::orderBy('created_at', 'desc')->get();
        return view('admin.subscribers', compact('subscribers'));
    }

    public function deleteSubscriber($id)
    {
        $subscriber = \App\Models\NewsletterSubscriber::findOrFail($id);
        $subscriber->delete();
        return redirect()->back()->with('success', 'Subscriber removed successfully.');
    }
}
