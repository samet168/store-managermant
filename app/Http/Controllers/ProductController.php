<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'price' => 'required|numeric',
    //         'quantity' => 'required|integer',
    //         'category_id' => 'required|exists:categories,id', 
    //         'status' => 'nullable|in:active,inactive', 
    //         'image' => 'nullable|string' 
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $validator->errors()
    //         ], 400);
    //     }

    //     $product = new Product();
    //     $product->name = $request->name;
    //     $product->description = $request->description;
    //     $product->price = $request->price;
    //     $product->quantity = $request->quantity;
    //     $product->category_id = $request->category_id; // set category
    //     $product->status = $request->status ?? 'active';
    //     // $product->image = $request->image ?? null;
    //     if($request->hasFile('image')) {
    //         $file = $request->file('image');
    //         //random name img
    //         $fileName = rand(0,999999999) . '.' . $file->getClientOriginalExtension();
    //         //move to folder
    //         $file -> move(public_path('images'),$fileName);
    //         //save to database
    //         $product->image = "http://127.0.0.1:8000/images/" . $fileName;

    //     }
    //     $product->save();

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Product created successfully',
    //         'data' => $product
    //     ], 201);
    // }
    
    public function store(Request $request)
{
    // Validate the incoming request
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:1', 
        'category_id' => 'required|exists:categories,id',
        'status' => 'nullable|in:active,inactive',
        'image' => 'nullable' 
    ]);

    // Handle validation failures
    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => $validator->errors()
        ], 400);
    }

    // Create a new product instance
    $product = new Product();
    $product->name = $request->name;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->quantity = $request->quantity;
    $product->category_id = $request->category_id; 
    $product->status = $request->status ?? 'active'; 

    
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $fileName = rand(0, 999999999) . '.' . $file->getClientOriginalExtension(); 
        $file->move(public_path('images'), $fileName); 
        $product->image = url('images/' . $fileName); 
    }

    // Save the product
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
    
    // public function update(Request $request, $id){
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'price' => 'required|numeric',
    //         'quantity' => 'required|integer',
    //         'category_id' => 'required|exists:categories,id', 
    //         'status' => 'nullable|in:active,inactive', 
    //         'image' => 'nullable|string' 
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $validator->errors()
    //         ], 400);
    //     }

    //     $product = Product::find($id);

    //     if (!$product) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Product not found'
    //         ], 404);
    //     }

    //     $product->name = $request->name;
    //     $product->description = $request->description;
    //     $product->price = $request->price;
    //     $product->quantity = $request->quantity;
    //     $product->category_id = $request->category_id;
    //     $product->status = $request->status ?? 'active';
    //     //ពិនិត្យថា request មាន file upload ឈ្មោះ image មកឬអត់
        
    //     if($request->hasFile('image')) {
    //                 //ករណីproductមានimg
    //         if(!$product->image){
    //                 //http://127.0.0.1:8000/api/product/6 
    //                 $image = $product->image;//យក Img នៅលើ database

    //                 //basename = get name img
    //                 $imageName = basename($image);

    //                 //យក Img នៅលើ folder images
    //                 $imagePath = public_path("images/$imageName");
    //                 if(File::exists($imagePath)){
    //                     File::delete($imagePath);
    //                     //delete and unlink =ស្មើរនិងការលុប
    //                 };
    //         }
    //         //ករណីមិនមានimg
            
    //         $file = $request->file('image');
    //         //random name img
    //         $fileName = rand(0,999999999) . '.' . $file->getClientOriginalExtension();
    //         //move to folder
    //         $file -> move(public_path('images'),$fileName);
    //         //save to database
    //         $product->image = "http://127.0.0.1:8000/images/" . $fileName;


    //     $product->save(); 

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Product updated successfully',
    //         'data' => $product
    //     ], 200);
    //     }
    // }

public function update(Request $request, $id)
{
    // Validate the incoming request
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0', // Ensure price is non-negative
        'quantity' => 'required|integer|min:1', // Ensure quantity is positive
        'category_id' => 'required|exists:categories,id', 
        'status' => 'nullable|in:active,inactive', 
        'image' => 'nullable' 
    ]);

    // Return validation errors if validation fails
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

    // Update product details
    $product->name = $request->name;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->quantity = $request->quantity;
    $product->category_id = $request->category_id;
    $product->status = $request->status ?? 'active';

    // Handle image upload if present
    if ($request->hasFile('image')) {
        // Delete the old image if it exists
        if ($product->image) {
            $imagePath = public_path('images/' . basename($product->image));
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        // Upload the new image
        $file = $request->file('image');
        $fileName = rand(0, 999999999) . '.' . $file->getClientOriginalExtension(); // Random file name
        $file->move(public_path('images'), $fileName); // Move to folder
        $product->image = url('images/' . $fileName); 
    }

    // Save updated product
    $product->save();

    // Return success response
    return response()->json([
        'status' => 'success',
        'message' => 'Product updated successfully',
        'data' => $product
    ], 200);
}
    public function destroy($id)
    {
        $product = Product::find($id);
        if($product == null){
            return response([
                'status' =>false,
                'message'=>"product not found",
            ],404);
        }
        //delete img
        $image = $product->image;

        $imageName = basename($image);//basename = get name img

        $imagePath = public_path("images/$imageName");//public_path = បង្កើត path ពេញ ទៅកាន់ file នៅក្នុង public/image/។

        if(File::exists($imagePath)){
            File::delete($imagePath);
        };

        $product->delete();//delete product on db

        return response([
            'status' => true,
            'message'=>"product deleted successfully",
        ],200);
    }
}

