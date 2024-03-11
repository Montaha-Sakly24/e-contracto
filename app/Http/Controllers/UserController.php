<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //Céer une nouvelle catégorie de contrat
    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password'
        ]);

        $user = User::create($validated);
        return response()->json($user, 201);
    }


    //Consulter liste catégories de contrat
    public function showlisteUser()
    {
        $user = User::all();
        return response()->json($user,200);
    }


    //Consulter catégorie de contrat
    public function showUserProfil(User $user)
    {
        return response()->json($user,200);
    }


    //Supprimer une catégorie de contrat
    public function deleteUserProfil(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
