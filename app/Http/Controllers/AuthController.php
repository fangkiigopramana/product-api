<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        return response()->json([
            'status' => true,
            'message' => 'Please log in first to change data.'
        ]);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:20',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
    
        $request->merge(['password' => Hash::make($request->password)]);
        $user = User::create($request->all());
    
        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $user->name;
    
        return response()->json([
            'status' => true,
            'message' => 'Register successfully',
            'data' => $success,
        ]);
    }

    public function auth(Request $request){
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $success['token'] = $user->createToken('auth_token')->plainTextToken;
            $success['name'] = $user->name;
    
            return response()->json([
                'status' => true,
                'message' => 'Login successfully',
                'data' => $success,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Email or password is wrong',
            ], 401);
        }        
    }
}
