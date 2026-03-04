<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
    public function store(Request $request)
    {
        //
        $Validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',

        ]);
        if($Validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $Validator->errors()
            ],400);
        }else{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => $user
            ],201);
        }   
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
        $Validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required',

        ]);
        if($Validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $Validator->errors()
            ],400);
        }else{
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => $user
            ],200);
        }
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
