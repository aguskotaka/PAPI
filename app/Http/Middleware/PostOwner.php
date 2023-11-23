<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PostOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): mixed  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $CurrentUser = Auth::user();

        // Pastikan pengguna telah login
        if (!$CurrentUser) {
            return response()->json(['message' => 'Not logged in'], 401);
        }

        // Pastikan rute telah dikonfigurasi dengan parameter {id}
        $post = Post::findOrFail($request->route('id'));

        // Periksa apakah pengguna adalah pemilik atau admin
        if ($post->author != $CurrentUser->id) {
            return response()->json(['message' => 'You are not the owner or an admin'], 401);
        }

        return $next($request);
    }
}

