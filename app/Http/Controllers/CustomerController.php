<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
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

    // Create customer
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $customer = Customer::create($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $customer
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