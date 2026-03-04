<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
public function run(): void
    {
        // Users
        DB::table('users')->insert([
            ['name'=>'Admin User', 'email'=>'admin@inventory.com', 'password'=>Hash::make('admin123'), 'role'=>'admin'],
            ['name'=>'Staff One', 'email'=>'staff@inventory.com', 'password'=>Hash::make('staff123'), 'role'=>'staff']
        ]);

        // Categories
        DB::table('categories')->insert([
            ['name'=>'Electronics','description'=>'Electronic devices and accessories'],
            ['name'=>'Stationery','description'=>'Office and school supplies'],
            ['name'=>'Clothing','description'=>'Apparel and fashion items'],
            ['name'=>'Food & Drink','description'=>'Consumable food and beverage products'],
            ['name'=>'Tools','description'=>'Hardware and repair tools'],
        ]);

        // Products
DB::table('products')->insert([
    [
        'name' => 'Wireless Mouse',
        'category_id' => 1,
        'quantity' => 50,
        'price' => 25.99,
        'description' => 'Bluetooth wireless mouse',
        'status' => 'active',
        'image' => null
    ],
    [
        'name' => 'USB-C Cable',
        'category_id' => 1,
        'quantity' => 120,
        'price' => 9.99,
        'description' => '1m USB-C cable',
        'status' => 'active',
        'image' => null
    ],
    [
        'name' => 'Ballpoint Pen (Box)',
        'category_id' => 2,
        'quantity' => 30,
        'price' => 4.50,
        'description' => 'Box of 12 pens',
        'status' => 'active',
        'image' => null
    ],
    [
        'name' => 'A4 Notebook',
        'category_id' => 2,
        'quantity' => 3,
        'price' => 2.99,
        'description' => '200-page notebook',
        'status' => 'active',
        'image' => null
    ],
    [
        'name' => 'T-Shirt (M)',
        'category_id' => 3,
        'quantity' => 25,
        'price' => 15.00,
        'description' => 'Plain cotton t-shirt size M',
        'status' => 'active',
        'image' => null
    ],
    [
        'name' => 'Screwdriver Set',
        'category_id' => 5,
        'quantity' => 2,
        'price' => 18.75,
        'description' => '6-piece screwdriver set',
        'status' => 'active',
        'image' => null
    ],
]);
    }
}
