<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    //Consulter profil
    public function getProfile()
    {
        return response()->json(Auth::user(), 200);
    }

    //Modifier profil
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:25',
            'phone' => 'required|string|regex:/^[0-9]{8,15}$/',
            'address' => 'required|string|max:255',
        ]);

        try {
            $user->update($validatedData);
            return response()->json(['success' => true, 'message' => 'Profile updated successfully !']);
        } catch(\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8',
            'c_new_password' => 'required|same:new_password'
        ]);

        $user = Auth::user();

        // Vérifier si le mot de passe actuel est correcte
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'errors' => [
                    'current_password' => ['The provided password does not match your current password.']
                ]
            ], 422);
        }

        // Màj mot de passe
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password successfully updated']);

    }

    //Supprimer compte
    public function deleteProfile()
    {
        $user = Auth::user();

        try {
            $user->delete();
            return response()->json(['message' => 'Your account has been deleted successfully.'], 200);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Something went wrong. Please try again later.'], 500);
        }
    }
}

