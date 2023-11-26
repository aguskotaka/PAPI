<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use function Laravel\Prompts\alert;
use function Laravel\Prompts\password;

class AuthenticationController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required',
        ]);

        $user = new User();
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->level = "user";
        $user->save();
        return response()->json(['message' => 'Registrasi berhasil'], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                $token = $user->createToken('admin login')->plainTextToken;
                return response()->json(['token' => $token, 'role' => 'admin']);
            } else {
                $token = $user->createToken('user login')->plainTextToken;
                return response()->json(['token' => $token, 'role'=> 'user']);
            }
        } else {
            return response()->json(['error' => 'Email atau password salah'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }

    public function me(Request $request)
    {
        return response()->json(Auth::user());
    }
}
