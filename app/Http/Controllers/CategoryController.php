<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //
    public function index(){
        $categories = Category::all();
        if($categories == null){
            return response()->json([
                'status' => 'error',
                'message' => 'No categories found'
            ],204);
        }else{
            return response()->json([
                'status' => 'success',
                'data' => $categories
            ],200);
        }

    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }
    public function edit($id){
        $category = Category::find($id);
        if($category == null){
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ],404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $category
        ],200);
    }
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $category = Category::find($id);
        if($category == null){
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ],404);
        }
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully',
            'data' => $category
        ], 200);
    }
    public function destroy($id){
        $category = Category::find($id);
        if($category == null){
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ],404);
        }
        $category->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully'
        ],200);
    }
}
