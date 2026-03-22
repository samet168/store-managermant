<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Authenticatable  // ← ត្រូវ Authenticatable មិនមែន Model
{
    use HasApiTokens, HasFactory;  // ← ត្រូវមាន HasApiTokens

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'image',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
    ];
}