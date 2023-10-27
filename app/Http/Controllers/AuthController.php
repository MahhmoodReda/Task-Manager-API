<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if(! Auth::attempt($validated))
        {
            return  response()->json([
                'message'=>'Invalid email or password',
                ],401);
        }

        return response()->json([
            'user'=>Auth::user(),
            'token'=>Auth::user()->createToken('auth_token')->plainTextToken,
            200]);

    }


    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create($validated);
        return response()->json([
            'user'=>$user,
            'access_token'=>$user->createToken('auth_token')->plainTextToken
        ],201);
    }
}
