<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request) {
        try {
            // Validation Rules
            $valiateUser = Validator::make($request->all(), [
                'name'      => 'required',
                'email'     => 'required|email|unique:users,email',
                'password'  => 'required'
            ]);
    
            if($valiateUser->fails()){
                return response()->json([
                    'status'    => false,
                    'message'   => 'Validation error',
                    'errors'    => $valiateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password)
            ]);

            return response()->json([
                'status'    => true,
                'message'   => 'User Created Successfully',
                'token'     => $user->createToken('API TOKEN')->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request) {
        try {
            // Validation Rules
            $valiateUser = Validator::make($request->all(), [
                'email'     => 'required|email',
                'password'  => 'required'
            ]);
    
            if($valiateUser->fails()){
                return response()->json([
                    'status'    => false,
                    'message'   => 'Validation error',
                    'errors'    => $valiateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status'    => false,
                    'message'   => 'Username or Password does not match'
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status'    => true,
                'message'   => 'User Logged In Successfully',
                'token'     => $user->createToken('API TOKEN')->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage()
            ], 500);
        }
    }
}
