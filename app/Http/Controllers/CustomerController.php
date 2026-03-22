<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{


// CustomerController.php
public function profile(Request $request) {
    $customer = $request->user(); // ← Sanctum auth user
    return response()->json([
        'data' => $customer
    ]);
}
    // Show all customers
    public function index()
    {
        $customers = Customer::all();

        if ($customers->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No customers found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $customers
        ], 200);
    }
    public function list(Request $request) {
        $limit = 5;
        $search = $request->input('search');

        $query = Customer::orderBy('created_at', 'desc');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $customers = $query->paginate($limit);

        return response()->json([
            'data' => $customers->items(),
            'current_page' => $customers->currentPage(),
            'last_page' => $customers->lastPage(),
            'total' => $customers->total(),
        ]);
    }

    // Create customer
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:customers,email',
    //         'phone' => 'required|string',
    //         'address' => 'nullable|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $validator->errors()
    //         ], 400);
    //     }

    //     $customer = Customer::create($request->all());

    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $customer
    //     ], 201);
    // }
    public function store(Request $request) {
    $validator = Validator::make($request->all(), [
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:customers,email',
        'password' => 'required|string|min:8',  // ← បន្ថែម
        'phone'    => 'required|string',
        'address'  => 'nullable|string',
        'image'    => 'nullable|image|max:2048', // ← បន្ថែម
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => 'error',
            'message' => $validator->errors()
        ], 400);
    }

    // Handle image upload
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images', 'public');
        $imagePath = asset('images/' . $imagePath);
    }

    $customer = Customer::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => $request->password, // auto hashed via $casts
        'phone'    => $request->phone,
        'address'  => $request->address,
        'image'    => $imagePath,
    ]);

    return response()->json([
        'status' => 'success',
        'data'   => $customer
    ], 201);
}

    // Update customer
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found'
            ], 404);
        }

        $customer->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $customer
        ], 200);
    }

    // Delete customer
    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found'
            ], 404);
        }

        $customer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Customer deleted successfully'
        ], 200);
    }
}