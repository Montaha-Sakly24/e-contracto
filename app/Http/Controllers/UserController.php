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


    //Modifier une catégorie de contrat
    public function updateUserProfil(Request $request,User $user)
    {


        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' =>'required',
        ]);
        // Vérifie si la catégorie de contrat existe
        if (!$user) {
            return response()->json(['error' => 'Catégorie de contrat non trouvée'], 404);
        }

        $success = $user->update($validated);
        if ($success) {
            return response()->json($user,200);
        } else {
            return response()->json(['error' => 'Échec de la mise à jour de la catégorie de contrat'], 500);
        }
    }


    //Supprimer une catégorie de contrat
    public function deleteUserProfil(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
