<?php

use App\Http\Controllers\AdvController;
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
Route::get('/adv', [AdvController::class, 'index']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/myposts', [PostController::class, 'myposts']);
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/me', [AuthenticationController::class, 'me']);

    Route::post('/posts', [PostController::class, 'store']);
    Route::patch('/posts/{id}', [PostController::class, 'update'])->middleware('PostOwner');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->middleware('PostOwner');

    Route::post('/comment', [CommentController::class, 'store']);
    Route::patch('/comment/{id}', [CommentController::class, 'update'])->middleware('CommentOwner');
    Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->middleware('CommentOwner');

    Route::post('/reportposts/{post_id}', [ReportController::class, 'store']);
});

// Routes for admins only
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/reportposts', [ReportController::class, 'index']);
    Route::delete('/reportposts/{post_id}', [ReportController::class, 'destroy']);


    Route::post('/adv', [AdvController::class, 'store']);
    Route::delete('/adv/{id}', [AdvController::class, 'destroy']);
});

