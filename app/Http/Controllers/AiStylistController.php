<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\AiRecommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiStylistController extends Controller
{
    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = strtolower(trim($request->message));
        $response = "";
        $recommendedProducts = collect();

        // 1. "Which hijab suits my face shape?" assistant
        if (str_contains($message, 'face shape') || str_contains($message, 'suits my face')) {
            $response = "Choosing the right hijab for your face shape enhances your natural elegance:<br><br>" .
                        "• <b>Oval Face:</b> You can wear almost any style! We recommend the <i>Casual Sand Cotton Hijab</i> for everyday look.<br>" .
                        "• <b>Round Face:</b> Avoid tight wraps; loose drapes work best. Try our <i>Lavender Silk Chiffon Hijab</i>.<br>" .
                        "• <b>Square Face:</b> Soften the jawline with rounded, loose folds. The <i>Mint Breeze Georgette Hijab</i> is perfect.<br>" .
                        "• <b>Heart Face:</b> Keep volume around the neck. The <i>Charcoal Soft Jersey Hijab</i> fits wonderfully.<br>" .
                        "• <b>Long Face:</b> Add volume to the sides. The <i>Warm Mocha Jersey Hijab</i> is highly recommended.";
            
            // Recommend some hijabs
            $recommendedProducts = Product::limit(3)->get();
        } 
        // 2. Recommend for casual outfit
        elseif (str_contains($message, 'casual') || str_contains($message, 'daily') || str_contains($message, 'everyday')) {
            $response = "For an effortless, comfortable, and sophisticated casual look, lightweight fabrics like Cotton and Jersey are ideal. " .
                        "They offer high breathability and stay secure all day without constant adjustments. Here are our top casual recommendations:";
            
            $recommendedProducts = Product::whereIn('fabric_type', ['Premium Cotton', 'Soft Jersey', 'Georgette'])->get();
        }
        // 3. Suggest premium/luxury silk hijabs
        elseif (str_contains($message, 'premium') || str_contains($message, 'silk') || str_contains($message, 'luxury') || str_contains($message, 'formal') || str_contains($message, 'wedding') || str_contains($message, 'bridal')) {
            $response = "Elevate your look with our premium modest wear! Crafted from luxurious Royal Satin, Silk Chiffon, and Satin Lace, " .
                        "these hijabs provide a radiant sheen and impeccable drape for weddings, formal galas, and evening events:";
            
            $recommendedProducts = Product::whereIn('fabric_type', ['Silk Chiffon', 'Royal Satin', 'Satin Lace', 'Premium Silk'])->get();
        }
        // 4. Color matching suggestion
        elseif (preg_match('/(color|match|wear with|outfit|dress)/', $message)) {
            $response = "When matching your outfit, contrast is key. If you are wearing a neutral dark dress, pair it with a soft pastel hijab like Lavender or Mint. " .
                        "For warm earth-toned clothing, our Warm Mocha or Sand Cotton hijabs create a perfect monochromatic harmony. View these styled options:";
            
            $recommendedProducts = Product::whereIn('color', ['Sand Beige', 'Soft Lavender', 'Warm Mocha', 'Mint Green'])->get();
        }
        // Default luxury fallback helper
        else {
            $response = "Hello! I am your ModestMirror AI Fashion Stylist. I can help you find the perfect hijab for your outfit, face shape, or any occasion. " .
                        "Try asking me:<br>" .
                        "• <i>'Which hijab suits my face shape?'</i><br>" .
                        "• <i>'Recommend something for a casual outfit'</i><br>" .
                        "• <i>'Suggest premium silk hijabs for a formal event'</i>";
            
            $recommendedProducts = Product::inRandomOrder()->limit(3)->get();
        }

        // Store in database
        $recommendedIds = $recommendedProducts->pluck('id')->implode(',');
        AiRecommendation::create([
            'user_id' => Auth::id(),
            'session_id' => session()->getId(),
            'user_message' => $request->message,
            'bot_response' => $response,
            'recommended_product_ids' => $recommendedIds
        ]);

        return response()->json([
            'success' => true,
            'response' => $response,
            'products' => $recommendedProducts->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => number_format($product->price),
                    'color' => $product->color,
                    'fabric_type' => $product->fabric_type,
                    'image' => $product->primaryImage ? asset($product->primaryImage->image_path) : asset('/images/placeholder.jpg'),
                ];
            })
        ]);
    }
}
