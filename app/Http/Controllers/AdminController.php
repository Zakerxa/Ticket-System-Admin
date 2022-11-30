<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Dotenv\Exception\ValidationException;
use Illuminate\Validation\Rule as ValidationRule;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        return $request->user();
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email', ValidationRule::exists('users', 'email'), 'max:255'],
                'password' => ['required', 'min:6', 'max:100'],
            ]);
        } catch (ValidationException $th) {
            return $th->validator->errors();
        }

        $user = User::where('email', $credentials['email'])->where(function($q){
            $q->where('role_id', 3)->orWhere('role_id',2);
        })->first();

        if (auth()->attempt($credentials) && $user) {
            $token = $user->createToken('admin')->plainTextToken;
            return response()->json([
                'response' => 'success',
                'token' => $token
            ]);
        } else return response(['errors' => 'The provided credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['response' => 'logout']);
    }
}
