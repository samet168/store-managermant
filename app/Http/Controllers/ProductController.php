<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::all();
        if($products == null){
            return response()->json([
                'status' => 'error',
                'message' => 'No products found'
            ],204);
        }else{
            return response()->json([
                'status' => 'success',
                'data' => $products
            ],200);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id', 
            'status' => 'nullable|in:active,inactive', 
            'image' => 'nullable|string' 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id; // set category
        $product->status = $request->status ?? 'active';
        $product->image = $request->image ?? null;
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }
    
    public function edit($id)
    {
        $product = Product::find($id);
        if($product == null){
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ],404);
        }else{
            return response()->json([
                'status' => 'success',
                'data' => $product
            ],200);
        }
    }
    
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id', 
            'status' => 'nullable|in:active,inactive', 
            'image' => 'nullable|string' 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->status = $request->status ?? 'active';
        $product->image = $request->image ?? null;
        $product->save(); 

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
            'data' => $product
        ], 200);
    }

    public function destroy($id){
        $product = Product::find($id);
        if($product == null){
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ],404);
        }else{
            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully'
            ],200);
        }
    }
}

