<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login( Request $request )
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required|min:6"
        ]);

        $user = User::where("email", $request->email)->first();

        if( !$user || !Hash::check($request->password, $user->password) ){
            return response(
                [
                    "status" => "error",
                    "message" => "Invalid credentials"
                ],
                401
            );
        }

        $token = $user->createToken("auth_token")->plainTextToken;

        return response(
            [
                "status" => "success",
                "token" => $token,
                "user" => $user
            ],
            201
        );
    }

    public function logout()
    {
        Auth::user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
    
        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }
}
