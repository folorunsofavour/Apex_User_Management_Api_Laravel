<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    
    //Profile API (GET)
    public function user_profile(){
        
        $user = Auth::user();

        if($user){
            return response()->json([
                "status" => true,
                "message" => "User Profile",
                "data" => $user
            ], 200);   
        }else{
            return response()->json([
                "status" => false, 
                "message" => "Unauthenticated."
            ], 401); 
        }
    }

    // Update User (PUT)
    public function user_update(Request $request){

        // Data Validation
        $rules = Validator::make($request->all(), [
            "name" => "required|string|max:250|regex:/^[a-zA-Z \-_]+$/",
            "email" => "required|email|max:250",
            "password" => "required|confirmed|min:8",
        ]);

        if($rules->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'Validation failed',
                'data' => $rules->errors()
            ], 422);
        }

        // Check if user is Auth and Exist
        $user = User::find(Auth::user()->id);
        
        if($user){

            // User Update 
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                "status" => true,
                "message" => "User Details Updated Succesfully",
                "data" => $user
            ], 200);

        }else{
            return response()->json([
                "status" => false,
                "message" => "Unauthenticated."
            ], 401);
        }
        
    }

    // Delete User (DELETE)
    public function user_delete($user_id){
        
        // authentication and role has already been checked through the middleware attached on the route
        $user = User::find($user_id);
        if($user){
            $user->delete();

            return response()->json([
                "status" => true,
                "message" => "User Deleted Succesfully"
            ], 200);
        }else{
            return response()->json([
                "status" => false,
                "message" => "User Not Found"
            ], 404);
        }
        
    }

    // Update Role to admin (PUT)
    public function update_role_admin($user_id){
        
        // authentication and role has already been checked through the middleware attached on the route
        $user_to_admin = User::find($user_id);

        if($user_to_admin){
            $user_to_admin->roles = "admin";
            $user_to_admin->save();

            return response()->json([
                "status" => true,
                "message" => "User Role Updated Succesfully",
                "data" => $user_to_admin
            ]);
        }else{
            return response()->json([
                "status" => false,
                "message" => "User Not Found"
            ]);
        }
        
    }
}
