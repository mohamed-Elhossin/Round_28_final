<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PDO;

class AuthController extends Controller
{


    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => "required|string",
            'email' => 'required|email|unique:users,email',
            'password' => "required|confirmed"
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $token = $user->createToken("myToken")->plainTextToken;

        $response  = [
            "status" => 200,
            "data" => $user,
            'message' => "Register Successfully",
            'Token' => $token
        ];


        return response($response, 200);
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => "required"
        ]);

        $user = User::where('email', '=', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            $response  = [
                'message' => "Please Try Agin ",
            ];
        } else {
            $token = $user->createToken("myToken")->plainTextToken;
            $response  = [
                "status" => 200,
                "data" => $user,
                'message' => "Login Successfully",
                'Token' => $token
            ];
        }

        return response($response, 200);
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        $response  = [
            "status" => 200,
            'message' => "LogOut Successfully",
        ];
        return response($response, 200);
    }
}
