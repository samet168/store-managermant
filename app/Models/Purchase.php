<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id',
        'purchase_date',
        'total_amount'
    ];

    // Relation to details
    public function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    // Relation to supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}