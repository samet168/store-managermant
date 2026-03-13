<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
        use HasFactory;

    // ✅ Add fillable fields to allow mass assignment
    protected $fillable = [
        'name',
        'description',
    ];
}
