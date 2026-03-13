<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'price'
    ];

    // Relation to product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relation to purchase
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}