<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Laravolt\Avatar\Avatar;

class UserController extends Controller
{
    //Céer un compte pour l'administrateur
    public function createAdminAccount(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:25',
            'email' => 'required|string|email|unique:users',
            'phone' => 'required|string|regex:/^[0-9]{8,15}$/',
            'address' => 'required|string|max:255',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->assignRole('Admin');

            // Generate avatar
        $avatar = new Avatar(config('laravolt.avatar'));
        $file = $avatar->create($user->name)->getImageObject()->encode('png');
        $filename = 'avatars/'.$user->id.'/avatar.png';

        // Store the avatar in the public disk
        Storage::disk('public')->put($filename, (string) $file);

        // Save the file path in the user's avatar column in the database
        $user->avatar = $filename;

        $result = $user->save();

        if (!$result) {
            $response = [
                'success' => false,
                'message' => 'Failed to create admin account'
            ];
            return response()->json($response, 400);
        }

        // Création du jeton d'accès
        $token = $user->createToken('MyApp')->plainTextToken;

        $response = [
            'success' => true,
            'data' => [
                'token' => $token,
                'name' => $user->name,
                'avatar' => $user->avatar
            ],
            'message' => 'Admin account created successfully'
        ];

        return response()->json($response);
    }

    //Consulter liste administrateurs
    public function getAdminsList()
    {
        $admins = User::role('Admin')->get();
        return response()->json($admins,200);
    }


    //Consulter liste utilisateurs
    public function getUsersList(){
        $users = User::role('Simple User')->get();
        return response()->json($users,200);
    }

    //Consulter profil utilisateur (admin || user )
    public function getUser(User $user)
    {
        return response()->json($user,200);
    }

    //Activer/désactiver compte utilisateur (administrateur et utilisateur)
    public function changeAccountStatus(User $user){
        try {
            $user->status = !$user->status;
            $user->save();

            $message = $user->status ? 'Account activated successfully!' : 'Account deactivated successfully!';
            return response()->json(['success' => true, 'message' => $message]);
        } catch(\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

    }

    //Supprimer compte
    public function deleteUser(User $user)
    {
        try {
                $user->delete();
                return response()->json(['success' => true, 'message' => 'User deleted successfully !']);
            }catch(\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Something went wrong..Please try again later !']);
            }
    }
}
