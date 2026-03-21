<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class OrderController extends Controller
{
    // List all orders
    public function index() {
        $orders = Order::with('details.product')->get();
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
        'data' => $orders->items(),
        'current_page' => $orders->currentPage(),
        'last_page' => $orders->lastPage(),
        'total' => $orders->total(),
    ]);
}
    
    // Show single order
    public function show($id) {
        $order = Order::with('details.product')->findOrFail($id);
        return response()->json($order);
    }

    // Create order
    public function store(Request $request) {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'status' => 'sometimes|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        // Calculate total amount
        $totalAmount = 0;
        foreach($request->products as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Create order with auto order_date
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'order_date' => now(),
            'total_amount' => $totalAmount,
            'status' => $request->status ?? 'pending',
        ]);

        // Attach order details
        foreach($request->products as $item) {
            $order->details()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return response()->json($order->load('details.product'), 201);
    }

    // Update order
public function update(Request $request, $id)
{
    // Find the order
    $order = Order::with('details')->findOrFail($id);


    // Update main order info
    if ($request->has('customer_id')) {
        $order->customer_id = $request->customer_id;
    }
    if ($request->has('status')) {
        $order->status = $request->status;
    }

    // If products are provided, update order details
    if ($request->has('products')) {
        // Remove old details
        $order->details()->delete();

        $totalAmount = 0;
        foreach($request->products as $item) {
            $order->details()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $order->total_amount = $totalAmount;
    }

    $order->save();

    // Return updated order with details
    return response()->json($order->load('details.product'));
}

    // Delete order
    public function destroy($id) {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(['message'=>'Order deleted successfully']);
    }
}