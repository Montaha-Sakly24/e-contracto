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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:8',
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



    public function login(Request $request)
{
    // Tentative d'authentification avec les informations fournies
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $user = Auth::user();

        // Création du jeton d'accès
        $token = $user->createToken('MyApp')->plainTextToken;

        $response = [
            'success' => true,
            'data' => [
                'token' => $token,
                'name' => $user->name
            ],
            'message' => 'User logged in successfully'
        ];
        return response()->json($response, 200);
    } else {
        // Échec de l'authentification
        $response = [
            'success' => false,
            'message' => 'Unauthorized'
        ];
        return response()->json($response, 401); // 401 signifie que l'accès est refusé
    }
}


}
