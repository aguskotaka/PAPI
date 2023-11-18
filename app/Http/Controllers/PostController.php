<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostDetailResource;
use Faker\Core\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    function index()
    {
        $posts = Post::all();
        //return response()->json(['data'=>$posts]);
        return PostDetailResource::collection($posts->loadMissing(['writer:id,username', 'comments:id,post_id,user_id,comments_content']));
    }
    function myposts()
    {
        $user = Auth::user();

        $posts = Post::where('author', $user->id)->get();

        if (!$posts) {
            return response()->json(['message' => 'Bro Post Sumting !!!'], 404);
        }

        return PostDetailResource::collection($posts->loadMissing(['writer:id,username', 'comments:id,post_id,user_id,comments_content']));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required',
            'news' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = null;

        // Check if the 'image' is present in the request
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());
        }

        $user = Auth::user();
        $post = Post::create([
            'image' => $image ? $image->hashName() : null,
            'title' => $request->title,
            'news' => $request->news,
            'author' => $user->id,
        ]);

        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    public function show($id)
    {
        $post = Post::with('writer:id,username')->findOrFail($id);
        return new PostDetailResource($post->loadMissing(['writer:id,username', 'comments:id,post_id,user_id,comments_content']));
    }




    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required',
            'news' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get the post by ID
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        // Ensure the authenticated user is the author of the post
        if (Auth::id() !== $post->author) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Update the post fields
        $post->title = $request->title;
        $post->news = $request->news;

        // Check if a new image is provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($post->image) {
                Storage::delete('public/posts/' . $post->image);
            }

            // Store the new image
            $image = $request->file('image');
            $post->image = $image->hashName();
            $image->storeAs('public/posts', $post->image);
        }

        $post->save();

        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }


    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }
}
