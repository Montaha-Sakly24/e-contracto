<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function signUp(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:25',
            'email' => 'required|string|email|max:255|unique:users',
            'phone'=> 'required|string|regex:/^[0-9]{8,15}$/',
            'address'=> 'required|string|max:255',
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
        $user->assignRole('Simple User');


        $role = $user->getRoleNames();
        // Création du jeton d'accès
        $token = $user->createToken('MyApp')->plainTextToken;

        $response = [
            'success' => true,
            'data' => [
                'token' => $token,
                'name' => $user->name,
                'role'=>$role,

            ],
            'message' => 'User registered successfully'
        ];

        return response()->json($response, 200);
    }



    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Tentative d'authentification avec les informations fournies
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            if (!$user->status) {
                Auth::logout();
                return response()->json(['success' => false, 'message' => 'Your account is deactivated. Please contact the administrator!'], 403);
            }


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

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Invalid email address.'], 401);
            }
            return response()->json(['success' => false, 'message' => 'Invalid password.'], 401);
        }
    }


}
