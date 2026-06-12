<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $orders = Order::where('user_id', $user->id)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $wishlistItems = Wishlist::where('user_id', $user->id)
            ->with('product.primaryImage')
            ->get();

        $screenshots = [];
        $screenshotDir = public_path('images/screenshots');
        if (File::exists($screenshotDir)) {
            $files = File::glob($screenshotDir . '/screenshot_' . $user->id . '_*.png');
            foreach ($files as $file) {
                $screenshots[] = '/images/screenshots/' . basename($file);
            }
        }
        
        $screenshots = array_reverse($screenshots);

        return view('dashboard.index', compact('user', 'orders', 'wishlistItems', 'screenshots'));
    }

    public function deleteScreenshot(Request $request)
    {
        $filename = $request->input('filename');
        $basename = basename($filename);
        $user = auth()->user();
        
        if (str_starts_with($basename, 'screenshot_' . $user->id . '_')) {
            $filePath = public_path('images/screenshots/' . $basename);
            if (File::exists($filePath)) {
                File::delete($filePath);
                return back()->with('success', 'Screenshot deleted successfully.');
            }
        }
        
        return back()->withErrors('Unable to delete screenshot.');
    }
}
