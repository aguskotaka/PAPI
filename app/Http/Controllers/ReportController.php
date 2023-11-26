<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\ReportPosts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ReportPostsResource;

class ReportController extends Controller
{
    public function index()
    {
        $reportposts = ReportPosts::latest('updated_at')->get();

        return ReportPostsResource::collection($reportposts->loadMissing([
            'post' => ['id', 'title', 'image', 'news'],
            'writer:id,username',
            'reporter:id,username',
        ]));
    }
    public function destroy($post_id)
    {
        $this->validatePostid($post_id);

        $report = ReportPosts::where('post_id', $post_id)
            ->first();
        if ($report === null) {
            return response()->json(['message' => 'Report not found'], 404);
        }
        if (Gate::allows('delete-post', $report)) {
            DB::beginTransaction();

            try {
                $report->delete();
                $post = Post::find($post_id);
                if ($post) {
                    $post->delete();
                }
                DB::commit();

                return response()->json(['message' => 'Report and post deleted successfully'], 200);

            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json(['message' => 'Failed to delete report and post'], 500);
            }
        } else {
            return response()->json(['message' => 'Unauthorized to delete post'], 403);
        }
    }


    private function validatePostid($post_id)
    {
        if (!is_numeric($post_id) || $post_id <= 0) {
            abort(400, 'Invalid post_id. It must be a positive integer.');
        }
    }
    public function store( Request $request, $post_id)
    {

        $validator = Validator::make($request->all(), [
            'reason' =>'required|string|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $this->validatePostid($post_id);

        $reporterId = Auth::id();

        $postAuthorId = Post::where('id', $post_id)->value('author');
        $report = ReportPosts::create([
            'post_id' => $post_id,
            'author' => $postAuthorId,
            'user_id' => $reporterId,
            'reason' => $request->input('reason'),
        ]);

        if ($report) {
            return response()->json(['message' => 'Report created successfully'], 201);
        } else {
            return response()->json(['message' => 'Failed to create report'], 500);
        }
        return response()->json(['message' => 'Laporan berhasil disimpan', 'data' => $report], 201);
    }
}
