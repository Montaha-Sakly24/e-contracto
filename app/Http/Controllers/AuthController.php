<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        // Création de l'utilisateur
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);

        // Création du jeton d'accès
        $token = $user->createToken('MyApp')->plainTextToken;

        $response = [
            'success' => true,
            'data' => [
                'token' => $token,
                'name' => $user->name
            ],
            'message' => 'User registered successfully'
        ];

        return response()->json($response, 200);
    }
}
