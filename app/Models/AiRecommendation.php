<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'user_message',
        'bot_response',
        'recommended_product_ids'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRecommendedProductsAttribute()
    {
        if (!$this->recommended_product_ids) {
            return collect();
        }
        $ids = explode(',', $this->recommended_product_ids);
        return Product::whereIn('id', $ids)->get();
    }
}
