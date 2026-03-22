<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'order_date', 'total_amount', 'status'];

    public function details() {
        return $this->hasMany(OrderDetail::class);
    }

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ✅ បន្ថែម
    public function customer() {
        return $this->belongsTo(User::class, 'user_id');
    }
}