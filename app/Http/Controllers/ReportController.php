<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\ReportPosts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    private function validatePostid($post_id)
    {
        if(!is_numeric($post_id) || $post_id <= 0)
        {
            abort(400, 'Invalid post_id. It must be a positive integer.');
        }
    }
    public function store($post_id)
    {

        $this->validatePostid($post_id);

        // Mendapatkan ID pengguna yang melaporkan
        $reporterId = Auth::id();

        $postAuthorId = Post::where('id', $post_id)->value('author');

        // Membuat laporan baru
        $report = ReportPosts::create([
            'post_id' => $post_id,
            'author' => $postAuthorId, // ID penulis postingan
            'user_id' => $reporterId,
        ]);

        // Jika laporan berhasil disimpan
        if ($report) {
            return response()->json(['message' => 'Report created successfully'], 201);
        } else {
            return response()->json(['message' => 'Failed to create report'], 500);
        }
        return response()->json(['message' => 'Laporan berhasil disimpan', 'data' => $report], 201);
    }
}
