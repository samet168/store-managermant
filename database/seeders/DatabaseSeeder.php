<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // 1️⃣ Users
DB::table('users')->insert([
    [
        'name' => 'Admin User',
        'email'=>'admin@inventory.com',
        'password'=>Hash::make('admin123'),
        'role'=>'admin',
        'created_at'=>now(),
        'updated_at'=>now()
    ],
    [
        'name' => 'Manager User',
        'email'=>'manager1@inventory.com',
        'password'=>Hash::make('manager123'),
        'role'=>'manager',
        'created_at'=>now(),
        'updated_at'=>now()
    ],
    [
        'name' => 'Cashier User',
        'email'=>'cashier1@inventory.com',
        'password'=>Hash::make('cashier123'),
        'role'=>'cashier',
        'created_at'=>now(),
        'updated_at'=>now()
    ],
    [
        'name' => 'Supplier User',
        'email'=>'supplier1@inventory.com',
        'password'=>Hash::make('supplier123'),
        'role'=>'supplier',
        'created_at'=>now(),
        'updated_at'=>now()
    ],
    [
        'name' => 'Customer User',
        'email'=>'customer1@inventory.com',
        'password'=>Hash::make('customer123'),
        'role'=>'customer',
        'created_at'=>now(),
        'updated_at'=>now()
    ],
]);

        // 2️⃣ Categories
        $categories = [
            ['name'=>'Electronics','description'=>'Electronic devices and accessories'],
            ['name'=>'Stationery','description'=>'Office and school supplies'],
            ['name'=>'Clothing','description'=>'Apparel and fashion items'],
            ['name'=>'Food & Drink','description'=>'Consumable food and beverage products'],
            ['name'=>'Tools','description'=>'Hardware and repair tools'],
        ];
        DB::table('categories')->insert($categories);

        // 3️⃣ Products - 50 products
        $products = [];
        for ($i = 1; $i <= 50; $i++) {
            $products[] = [
                'name' => $faker->words(3, true),
                'category_id' => rand(1,5),
                'quantity' => rand(1, 200),
                'price' => $faker->randomFloat(2, 1, 500),
                'description' => $faker->sentence(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('products')->insert($products);

        // 4️⃣ Customers - 20 customers
        $customers = [];
        for ($i = 1; $i <= 20; $i++) {
            $customers[] = [
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('customers')->insert($customers);

        // 5️⃣ Suppliers - 10 suppliers
        $suppliers = [];
        for ($i = 1; $i <= 10; $i++) {
            $suppliers[] = [
                'name' => $faker->company(),
                'contact_info' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('suppliers')->insert($suppliers);

        // 6️⃣ Purchases + 7️⃣ Purchase_Details
        for ($i = 1; $i <= 20; $i++) {
            $purchaseId = DB::table('purchases')->insertGetId([
                'supplier_id' => rand(1,10),
                'purchase_date' => $faker->dateTimeThisYear(),
                'total_amount' => 0, // will update later
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $total = 0;
            $items = rand(1,5);
            for ($j = 1; $j <= $items; $j++) {
                $productId = rand(1,50);
                $qty = rand(1,20);
                $price = $faker->randomFloat(2,5,100);
                $total += $qty * $price;

                DB::table('purchase_details')->insert([
                    'purchase_id' => $purchaseId,
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'price' => $price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // update product quantity
                DB::table('products')->where('id', $productId)->increment('quantity', $qty);

                // log stock
                DB::table('stock_logs')->insert([
                    'product_id' => $productId,
                    'change' => $qty,
                    'reason' => 'purchase',
                    'date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // update total_amount
            DB::table('purchases')->where('id', $purchaseId)->update(['total_amount'=>$total]);
        }

        // 8️⃣ Orders + 9️⃣ Order_Details
        for ($i = 1; $i <= 30; $i++) {
            $customerId = rand(1,20);
            $orderDate = $faker->dateTimeThisYear();
            $status = $faker->randomElement(['pending','completed','canceled']);

            $orderId = DB::table('orders')->insertGetId([
                'customer_id' => $customerId,
                'order_date' => $orderDate,
                'total_amount' => 0, // update later
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $total = 0;
            $items = rand(1,5);
            for ($j = 1; $j <= $items; $j++) {
                $productId = rand(1,50);
                $qty = rand(1,5);
                $price = DB::table('products')->where('id', $productId)->value('price');
                $total += $qty * $price;

                DB::table('order_details')->insert([
                    'order_id' => $orderId,
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'price' => $price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // decrease stock
                DB::table('products')->where('id', $productId)->decrement('quantity', $qty);

                // log stock
                DB::table('stock_logs')->insert([
                    'product_id' => $productId,
                    'change' => -$qty,
                    'reason' => 'sale',
                    'date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // update total_amount
            DB::table('orders')->where('id', $orderId)->update(['total_amount'=>$total]);
        }
    }
}