<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        "category",
        'stock_quantity',
        'image',
    ];

    // Relación many-to-many con Order
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_product', "product_id", "order_id")
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }
}
