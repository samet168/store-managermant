<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::with('details.product', 'customer')->get();
        return response()->json($orders);
    }

    public function indexS(Request $request) {
    $orders = Order::with('details.product', 'customer')
        ->where('user_id', $request->user()->id) // ✅ 
        ->orderBy('created_at', 'desc')
        ->get();
    return response()->json($orders);
}
    public function list(Request $request) {
        $limit = 5;
        $search = $request->input('search');

        $query = Order::with('details.product', 'customer')
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate($limit);

        return response()->json([
            'data'         => $orders->items(),
            'current_page' => $orders->currentPage(),
            'last_page'    => $orders->lastPage(),
            'total'        => $orders->total(),
        ]);
    }
    public function listS(Request $request) {
        $limit = 5;
        $search = $request->input('search');

        $query = Order::with('details.product', 'customer')
            ->where('user_id', $request->user()->id) // ✅ Filter តាម user ដែល login
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('status', 'like', "%{$search}%");
                // ❌ លុប whereHas customer ព្រោះ filter user_id រួចហើយ
            });
        }

        $orders = $query->paginate($limit);

        return response()->json([
            'data'         => $orders->items(),
            'current_page' => $orders->currentPage(),
            'last_page'    => $orders->lastPage(),
            'total'        => $orders->total(),
        ]);
    }
    public function show($id) {
        $order = Order::with('details.product', 'customer')->findOrFail($id);
        return response()->json($order);
    }

    // public function store(Request $request) {
    //     $request->validate([
    //         'user_id'                  => 'required|exists:users,id',
    //         'status'                   => 'sometimes|string',
    //         'products'                 => 'required|array|min:1',
    //         'products.*.product_id'    => 'required|exists:products,id',
    //         'products.*.quantity'      => 'required|integer|min:1',
    //         'products.*.price'         => 'required|numeric|min:0',
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         $totalAmount = 0;
    //         foreach ($request->products as $item) {
    //             $totalAmount += $item['price'] * $item['quantity'];
    //         }

    //         $order = Order::create([
    //             'user_id'      => $request->user_id,
    //             'order_date'   => now(),
    //             'total_amount' => $totalAmount,
    //             'status'       => $request->status ?? 'pending',
    //         ]);

    //         foreach ($request->products as $item) {
    //             $order->details()->create([
    //                 'product_id' => $item['product_id'],
    //                 'quantity'   => $item['quantity'],
    //                 'price'      => $item['price'],
    //             ]);

    //             Product::where('id', $item['product_id'])
    //                 ->decrement('quantity', $item['quantity']);

    //             StockLog::create([
    //                 'product_id' => $item['product_id'],
    //                 'change'     => -$item['quantity'],
    //                 'reason'     => 'sale',
    //                 'date'       => now(),
    //             ]);
    //         }

    //         DB::commit();
    //         return response()->json($order->load('details.product', 'customer'), 201);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => 'Failed to create order: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


    
public function store(Request $request) {
    $request->validate([
        'status'                => 'sometimes|string',
        'products'              => 'required|array|min:1',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity'   => 'required|integer|min:1',
        'products.*.price'      => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {
        $totalAmount = 0;
        foreach ($request->products as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'user_id'      => $request->user()->id, // ✅ ពី token
            'order_date'   => now(),
            'total_amount' => $totalAmount,
            'status'       => $request->status ?? 'pending',
        ]);

        foreach ($request->products as $item) {
            $order->details()->create([
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
            ]);

            Product::where('id', $item['product_id'])
                ->decrement('quantity', $item['quantity']);

            StockLog::create([
                'product_id' => $item['product_id'],
                'change'     => -$item['quantity'],
                'reason'     => 'sale',
                'date'       => now(),
            ]);
        }

        DB::commit();
        return response()->json($order->load('details.product', 'customer'), 201);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Failed to create order: ' . $e->getMessage()
        ], 500);
    }
}

    public function destroy($id) {
        $order = Order::with('details')->findOrFail($id);

        foreach ($order->details as $item) {
            Product::where('id', $item->product_id)
                ->increment('quantity', $item->quantity);

            StockLog::create([
                'product_id' => $item->product_id,
                'change'     => $item->quantity,
                'reason'     => 'order deleted',
                'date'       => now(),
            ]);
        }

        $order->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }
}