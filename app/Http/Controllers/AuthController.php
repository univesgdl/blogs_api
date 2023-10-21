<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete(); // Revocar todos los tokens de acceso del usuario

        return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
    }


    public function me( Request $request )
    {
        $user = $request->user();

        return $user;
    }
}
