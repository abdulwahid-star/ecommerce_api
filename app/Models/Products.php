<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'image',
        'is_trendy',
        'is_available',
        'price',
        'amount',
        'discount'
    ];

    public function category() {
        $this->belongsTo(Categories::class, 'category_id');
    }

    public function brand() {
        $this->belongsTo(Brands::class, 'brand_id');
    }
}
