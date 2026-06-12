<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = trim(strtolower($request->email));

        $exists = NewsletterSubscriber::where('email', $email)->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'You are already subscribed to our newsletter!'
            ], 422);
        }

        NewsletterSubscriber::create([
            'email' => $email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for subscribing to our newsletter!'
        ]);
    }
}
