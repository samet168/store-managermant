<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $users = User::all();
        if($users == null){
            return response()->json([
                'status' => 'error',
                'message' => 'No users found'
            ],204);
        }else{
            return response()->json([
                'status' => 'success',
                'data' => $users
            ],200);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


//     public function store(Request $request)
// {
    
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'email' => 'required|string|email|unique:users,email|max:255',
//         'password' => 'required|string',
//     ]);

    
//     $user = User::create([
//         'name' => $request->name,
//         'email' => $request->email,
//         'password' => Hash::make($request->password),
//         'role' => $request->role,
        
//     ]);

//     // Return success response
//     return response()->json([
//         'status' => 'success',
//         'message' => 'User created successfully',
//         'data' => $user
//     ], 201);
// }

public function store(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|unique:users,email|max:255',
        'password' => 'required|string|min:6',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validate image
    ]);

    // Create a new user instance
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->role = $request->role ?? 'staff'; // Default to 'staff'

    // Handle image upload
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $fileName = time() . '.' . $file->getClientOriginalExtension(); // Unique file name
        $file->move(public_path('images'), $fileName); // Move to folder
        $user->image = url('images/' . $fileName); // Save image URL
    }

    // Save the user
    $user->save();

    // Return success response
    return response()->json([
        'status' => 'success',
        'message' => 'User created successfully',
        'data' => $user
    ], 201);
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        if($user == null){
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ],404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $user
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, string $id)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id, // Allow current email for the user
        'password' => 'required|string|min:6',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validate image type
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => $validator->errors()
        ], 400);
    }

    $user = User::find($id);
    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'User not found'
        ], 404);
    }
    
    $user->name = $request->name;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    if ($request->hasFile('image')) {
        if ($user->image) {
            $imagePath = public_path('images/' . basename($user->image));
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $file = $request->file('image');
        $fileName = rand(0, 999999999) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $fileName);
        $user->image = url('images/' . $fileName);
    }

    $user->save();

    return response()->json([
        'status' => 'success',
        'message' => 'User updated successfully',
        'data' => $user
    ], 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if($user == null){
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ],404);
        }
        $user->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ],200);
    }
}
