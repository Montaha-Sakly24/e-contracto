<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Spatie\Permission\Models\Role;


class ResetForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

// Check if the user with the provided email is a superadministrator
$user = User::where('email', $request->email)->first();
$superadminRole = Role::where('name', 'Superadmin')->first();

if ($user && $user->hasRole($superadminRole)) {
    return response()->json(['error' => 'Superadministrators cannot reset their passwords.'], 403);
}
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status)]);
        } else {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8',
            'c_password' => 'required|same:password'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'c_password', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)]);
        } else {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }
    }

}
