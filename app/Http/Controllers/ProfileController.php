<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    //Consulter profil
    public function getProfile()
    {
        return response()->json(Auth::user(),200);
    }

    //Modifier profil
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $this->validate($request,[
            'name' => 'string|max:25',
            'phone' => 'string|regex:/^[0-9]{8,15}$/',
            'address' => 'string|max:255',
        ]);

        try {
            $user->update($request->all());
            return response()->json(['success' => true, 'message' => 'Profile updated successfully !']);
        } catch(\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    //Supprimer compte
    public function deleteProfile():RedirectResponse
    {
        $user = Auth::user();

        try {
            $user->delete();
            Auth::logout();

            return redirect()->route('login')->with('status', 'Your account has been deleted successfully.');

        } catch(\Exception $e) {
            return back()->withErrors('Something went wrong.. Please try again later !');
        }
    }
}

