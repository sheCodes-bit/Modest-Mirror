<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ArController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::whereNotNull('ar_overlay_path')
            ->with('category')
            ->get();

        $selectedProduct = null;
        if ($request->filled('product')) {
            $selectedProduct = Product::find($request->product);
        }

        if (!$selectedProduct && !$products->isEmpty()) {
            $selectedProduct = $products->first();
        }

        return view('ar.tryon', compact('products', 'selectedProduct'));
    }

    public function saveScreenshot(Request $request)
    {
        $request->validate([
            'image' => 'required|string'
        ]);

        $userId = auth()->id();
        $imageData = $request->image;

        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $decodedData = base64_decode($imageData);

        $screenshotDir = public_path('images/screenshots');
        if (!File::exists($screenshotDir)) {
            File::makeDirectory($screenshotDir, 0777, true);
        }

        $fileName = 'screenshot_' . $userId . '_' . time() . '_' . rand(1000, 9999) . '.png';
        $filePath = $screenshotDir . '/' . $fileName;

        File::put($filePath, $decodedData);

        return response()->json([
            'success' => true,
            'message' => 'Try-on snapshot saved to your dashboard.',
            'url' => '/images/screenshots/' . $fileName
        ]);
    }
}
