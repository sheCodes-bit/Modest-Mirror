<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArAsset extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'name', 'overlay_image_path', 'scale_factor', 'offset_y'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
