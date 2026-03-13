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
            'order_date' => now(),  // auto current datetime
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
    public function update(Request $request, $id) {
        $order = Order::findOrFail($id);

        $request->validate([
            'customer_id' => 'sometimes|exists:customers,id',
            'order_date' => 'sometimes|date',
            'total_amount' => 'sometimes|numeric',
            'status' => 'sometimes|string',
        ]);

        $order->update($request->only(['customer_id','order_date','total_amount','status']));
        return response()->json($order);
    }

    // Delete order
    public function destroy($id) {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(['message'=>'Order deleted successfully']);
    }
}