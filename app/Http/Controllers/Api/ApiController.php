<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ApiController extends Controller
{
    //Register API (POST)
    public function user_register(Request $request){

        // Data Validation; Names doesn't allow for numbers, but allows - and _
        $rules = Validator::make($request->all(), [
            "name" => "required|string|max:250|regex:/^[a-zA-Z \-_]+$/",
            "email" => "required|email|max:250|unique:users",
            "password" => "required|confirmed|min:8",
        ]);

        if($rules->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'Validation failed',
                'data' => $rules->errors()
            ], 422);
        }

        // User Registration
        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        return response()->json([
            "status" => true,
            "message" => "User Created Succesfully"
        ], 201);
    }

    //Login API (POST)
    public function user_login(Request $request){
        
        // Data Validation
        $rules = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required",
        ]);

        if($rules->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'Validation failed',
                'data' => $rules->errors()
            ], 422);
        }

        if(Auth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ])){
            //Check if User Exist
            $user = Auth::user();
            $token = $user->createToken("userToken")->accessToken;
            
            return response()->json([
                "status" => true,
                "message" => "User logged in successfully",
                "token" => $token
            ], 200);

        }else{
            // User does not exist
            return response()->json([
                "status" => false,
                "message" => "Invalid Login details"
            ], 401);
        }
    }

    //Logout API (GET)
    public function user_logout(){
        
        $user = Auth::user();

        if($user){
            // auth()->user()
            Auth::user()->token()->revoke();

            return response()->json([
                "status" => true, 
                "message" => "User Logged out Successfully"
            ], 200); 
        }else{
            return response()->json([
                "status" => false, 
                "message" => "Unauthenticated"
            ], 401); 
        }
         
    }
}
