<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Cache\RateLimiting\Limit;

class SupplierController extends Controller
{

    // Show all suppliers
    public function index()
    {
        $suppliers = Supplier::all();

        if ($suppliers->count() > 0) {
            return response()->json([
                'message' => 'Supplier list',
                'data' => $suppliers
            ]);
        }

        return response()->json([
            'message' => 'No suppliers found'
        ]);
    }

public function list(Request $request)
{
    $limit = 5; // items per page
    $search = $request->input('search'); // get search query from request

    // Build query
    $query = Supplier::orderBy('created_at', 'desc');

    // If search exists, filter by name
    if ($search) {
        $query->where('name', 'like', '%' . $search . '%');
    }

    // Paginate results
    $suppliers = $query->paginate($limit);

    // Return structured JSON
    return response()->json([
        'data' => $suppliers->items(),
        'current_page' => $suppliers->currentPage(),
        'last_page' => $suppliers->lastPage(),
        'total' => $suppliers->total(),
    ]);
}
    

    // Store new supplier
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'contact_info' => 'nullable|string',
            'address' => 'nullable|string'
        ]);

        $supplier = Supplier::create([
            'name' => $request->name,
            'contact_info' => $request->contact_info,
            'address' => $request->address
        ]);

        return response()->json([
            'message' => 'Supplier created successfully',
            'data' => $supplier
        ], 201);
    }


    // Show one supplier
    public function show($id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json([
                'message' => 'Supplier not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Supplier detail',
            'data' => $supplier
        ]);
    }


    // Update supplier
    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json([
                'message' => 'Supplier not found'
            ], 404);
        }

        $supplier->update([
            'name' => $request->name,
            'contact_info' => $request->contact_info,
            'address' => $request->address
        ]);

        return response()->json([
            'message' => 'Supplier updated successfully',
            'data' => $supplier
        ]);
    }


    // Delete supplier
    public function destroy($id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json([
                'message' => 'Supplier not found'
            ], 404);
        }

        $supplier->delete();

        return response()->json([
            'message' => 'Supplier deleted successfully'
        ]);
    }

}