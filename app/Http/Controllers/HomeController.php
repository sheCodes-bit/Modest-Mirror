<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $sliderDir = public_path('images/slider');
        if (!file_exists($sliderDir)) {
            mkdir($sliderDir, 0777, true);
        }

        $slides = [
            'slide1.png' => [214, 199, 199], // Soft Lavender Pink
            'slide2.png' => [225, 211, 193], // Soft Sand Beige
            'slide3.png' => [227, 213, 186], // Soft Gold Champagne
        ];

        foreach ($slides as $fileName => $color) {
            $filePath = $sliderDir . '/' . $fileName;
            if (!file_exists($filePath) && extension_loaded('gd')) {
                $im = imagecreatetruecolor(800, 600);
                $bg = imagecolorallocate($im, $color[0], $color[1], $color[2]);
                imagefill($im, 0, 0, $bg);
                
                $overlayCol = imagecolorallocate($im, 59, 47, 47);
                $goldCol = imagecolorallocate($im, 200, 169, 106);
                
                imagefilledellipse($im, 700, 300, 500, 500, $goldCol);
                imagefilledellipse($im, 750, 300, 400, 400, $overlayCol);

                imagepng($im, $filePath);
                imagedestroy($im);
            }
        }

        $categories = Category::all();

        $featuredProducts = Product::with('primaryImage', 'category')
            ->take(4)
            ->get();

        return view('home', compact('categories', 'featuredProducts'));
    }
}
