<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\PurchaseDetail;

class PurchaseController extends Controller
{
    // Show all purchases
    public function index()
    {
        $purchases = Purchase::with('details.product', 'supplier')->get();

        if ($purchases->count() > 0) {
            return response()->json([
                'message' => 'Purchase list',
                'data' => $purchases
            ]);
        }

        return response()->json([
            'message' => 'No purchases found'
        ]);
    }

    // Store new purchase
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        // Calculate total
        $totalAmount = 0;
        foreach($request->products as $item) {
            $totalAmount += $item['quantity'] * $item['price'];
        }

        // Create purchase
        $purchase = Purchase::create([
            'supplier_id' => $request->supplier_id,
            'purchase_date' => $request->purchase_date,
            'total_amount' => $totalAmount
        ]);

        // Create purchase details
        foreach($request->products as $item) {
            $purchase->details()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        return response()->json([
            'message' => 'Purchase created successfully',
            'data' => $purchase->load('details.product', 'supplier')
        ], 201);
    }

    // Show single purchase
    public function show($id)
    {
        $purchase = Purchase::with('details.product', 'supplier')->find($id);

        if (!$purchase) {
            return response()->json([
                'message' => 'Purchase not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Purchase detail',
            'data' => $purchase
        ]);
    }

    // Update purchase
    public function update(Request $request, $id)
    {
        $purchase = Purchase::find($id);

        if (!$purchase) {
            return response()->json([
                'message' => 'Purchase not found'
            ], 404);
        }

        $request->validate([
            'supplier_id' => 'sometimes|exists:suppliers,id',
            'purchase_date' => 'sometimes|date',
            'products' => 'sometimes|array',
            'products.*.product_id' => 'required_with:products|exists:products,id',
            'products.*.quantity' => 'required_with:products|integer|min:1',
            'products.*.price' => 'required_with:products|numeric|min:0',
        ]);

        if ($request->supplier_id) $purchase->supplier_id = $request->supplier_id;
        if ($request->purchase_date) $purchase->purchase_date = $request->purchase_date;

        // Update products if provided
        if ($request->products) {
            // Delete old details
            $purchase->details()->delete();

            $totalAmount = 0;
            foreach($request->products as $item) {
                $purchase->details()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
                $totalAmount += $item['quantity'] * $item['price'];
            }

            $purchase->total_amount = $totalAmount;
        }

        $purchase->save();

        return response()->json([
            'message' => 'Purchase updated successfully',
            'data' => $purchase->load('details.product', 'supplier')
        ]);
    }

    // Delete purchase
    public function destroy($id)
    {
        $purchase = Purchase::find($id);

        if (!$purchase) {
            return response()->json([
                'message' => 'Purchase not found'
            ], 404);
        }

        $purchase->delete();

        return response()->json([
            'message' => 'Purchase deleted successfully'
        ]);
    }
}