<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use App\Http\Controllers\PostController;
use App\Http\Middleware\UserLevel;
use Illuminate\Support\Facades\Route;

Route::get('/posts/{id}', [PostController::class, 'show']);
Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/register', [AuthenticationController::class, 'register']);
Route::get('/posts', [PostController::class, 'index']);

Route::middleware(['auth:sanctum', UserLevel::class.':user'])->group(function () {


    Route::get('/myposts', [PostController::class, 'myposts']);
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/me', [AuthenticationController::class, 'me']);
    Route::post('/posts',[PostController::class, 'store']);
    Route::patch('/posts/{id}',[PostController::class, 'update'])->middleware('PostOwner');
    Route::delete('/posts/{id}',[PostController::class, 'destroy'])->middleware('PostOwner');

    Route::post('/comment', [CommentController::class, 'store']);
    Route::patch('/comment/{id}', [CommentController::class, 'update'])->middleware('CommentOwner');
    Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->middleware('CommentOwner');

    // Route::post('/reportpost', [ReportController::class, 'store'])->middleware('PostOwner');
    Route::post('/reportpost/{post_id}', [ReportController::class, 'store']);
});
Route::middleware(['auth:sanctum'])->group(function () {

    // Route::post('/reportpost', [ReportController::class, 'store'])->middleware('PostOwner');
});
