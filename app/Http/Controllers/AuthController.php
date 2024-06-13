<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravolt\Avatar\Avatar;
use Illuminate\Support\Facades\Storage;


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

        // Generate avatar
        $avatar = new Avatar(config('laravolt.avatar'));
        $file = $avatar->create($user->name)->getImageObject()->encode('png');
        $filename = 'avatars/'.$user->id.'/avatar.png';

        // Store the avatar in the public disk
        Storage::disk('public')->put($filename, (string) $file);

        // Save the file path in the user's avatar column in the database
        $user->avatar = $filename;
        $user->save();

        // Création du jeton d'accès
        $token = $user->createToken('MyApp')->plainTextToken;


        $response = [
            'success' => true,
            'data' => [
                'token' => $token,
                'name' => $user->name,
                'avatar' => $user->avatar
            ],
            'message' => 'User registered successfully'
        ];


        return response()->json($response, 200);
    }



    public function login(Request $request)
    {
        $validator = Validator::make($request->only('email', 'password'), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'errors' => ['email' => ['Invalid email address.']]], 401);
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['success' => false, 'errors' => ['password' => ['Invalid password.']]], 401);
        }

        if (!$user->status) {
            Auth::logout();
            return response()->json(['success' => false, 'message' => 'Your account is deactivated. Please contact the administrator!'], 403);
        }

        $role = $user->getRoleNames();
        $token = $user->createToken('MyApp')->plainTextToken;

        $response = [
            'success' => true,
            'data' => [
                'token' => $token,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role,

            ],
            'message' => 'User logged in successfully'
        ];

        return response()->json($response, 200);

    }



}
