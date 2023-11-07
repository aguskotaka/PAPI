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
        // Validasi data yang dikirim oleh pengguna
        $request->validate([
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required',
        ]);

        // Buat entri pengguna baru di basis data
        $user = new User();
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->level = "user";
        $user->save();

        // Beri respons sukses
        return response()->json(['message' => 'Registrasi berhasil'], 200);
    }

    public function login(Request $request)
    {
        // Validasi data yang dikirim oleh pengguna
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba melakukan autentikasi pengguna
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // Jika autentikasi berhasil, buat token untuk pengguna
            $token = $user->createToken('user login')->plainTextToken;

            // Beri respons sukses bersama dengan token
            return response()->json(['token' => $token]);
        } else {
            // Autentikasi gagal
            return response()->json(['error' => 'Email atau password salah'], 401);
        }
    }

    public function logout(Request $request)
    {
        // Hapus token saat logout
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }

    public function me(Request $request)
    {
        return response()->json(Auth::user());
    }
}
