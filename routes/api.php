<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\WebController;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}/details', [UserController::class, 'show']);
    Route::post('users/create', [UserController::class, 'store']);
    Route::put('users/{user}/update', [UserController::class, 'update']);
    Route::delete('users/{user}/delete', [UserController::class, 'destroy']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}/details', [CategoryController::class, 'show']);
    Route::post('categories/create', [CategoryController::class, 'store']);
    Route::post('categories/{category}/update', [CategoryController::class, 'update']);
    Route::delete('categories/{category}/delete', [CategoryController::class, 'destroy']);

    Route::get('tags', [TagController::class, 'index']);
    Route::get('tags/{tag}/details', [TagController::class, 'show']);
    Route::post('tags/create', [TagController::class, 'store']);
    Route::post('tags/{tag}/update', [TagController::class, 'update']);
    Route::delete('tags/{tag}/delete', [TagController::class, 'destroy']);

    Route::get('domains', [DomainController::class, 'index']);
    Route::get('domains/{domain}/details', [DomainController::class, 'show']);
    Route::post('domains/create', [DomainController::class, 'store']);
    Route::post('domains/{domain}/update', [DomainController::class, 'update']);
    Route::delete('domains/{domain}/delete', [DomainController::class, 'destroy']);

    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{post}/details', [PostController::class, 'show']);
    Route::get('posts/{post}/categories', [PostController::class, 'categories']);
    Route::get('posts/{post}/tags', [PostController::class, 'tags']);
    Route::get('posts/{post}/blocks', [PostController::class, 'blocks']);
    Route::post('posts/create', [PostController::class, 'store']);
    Route::post('posts/{post}/update', [PostController::class, 'update']);
    Route::post('posts/{post}/updateimage', [PostController::class, 'updateImage']);
    Route::post('posts/{post}/updateslug', [PostController::class, 'updateSlug']);
    Route::delete('posts/{post}/delete', [PostController::class, 'destroy']);
    Route::post('posts/{post}/updatecategory', [PostController::class, 'updateCategory']);
    Route::post('posts/{post}/updatetag', [PostController::class, 'updateTag']);

    Route::resource('posts', PostController::class);
    Route::resource('tags', TagController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('comments', CommentController::class);
    Route::resource('blocks', BlockController::class);

    Route::post('logout', [AuthController::class,'logout'])->name('logout');
    Route::get('/me', [AuthController::class,'me'])->name('me');


});

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::prefix('web')->group(function(){
    Route::get('posts', [WebController::class, 'postsindex']);
    Route::get('posts/{post:slug}', [WebController::class, 'postsingle']);
    Route::get('categories/{category:slug}/posts', [WebController::class, 'categoryposts']);
    Route::get('tags/{tag:slug}/posts', [WebController::class, 'tagposts']);
    Route::get('posts', [WebController::class, 'postsindex']);
    Route::get('posts/{post}/comments', [WebController::class, 'postcomments']);

    Route::post('/posts/{post}/comments/save', [WebController::class, 'saveComment'])->name('saveComment');
});