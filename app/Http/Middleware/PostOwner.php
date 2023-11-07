<?php

namespace App\Http\Middleware;

use App\Models\Post;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PostOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $CurrentUser = Auth::user();
        $post = Post::findOrFail($request->id);

        if($post->author != $CurrentUser->id)
        {
            return response()->json(['message'=>'data not found'],404);

        }
        return $next($request);
    }
}
