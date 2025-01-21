<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\FollowsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [PostsController::class, 'index'])->name('home');

Route::put('/profile/update/{id}', [HomeController::class, 'update'])->name('profile.update');

Route::resource('posts', PostsController::class);

Route::post('/post/comment/{id}', [CommentsController::class, 'addcomment'])->name('comment.add');

Route::post('/follow/{id}', [FollowsController::class, 'follow'])->name('user.follow');

Route::get('/user/{id}', [PostsController::class, 'showuser'])->name('user.profile');

Route::get('/search-users', [HomeController::class, 'search'])->name('search.users');

Route::get('/is-following/{id}', [FollowsController::class, 'isfollowing'])->name('user.isfollowing');

Route::get('/feed',[HomeController::class, 'feed'])->name('insta.feed');

Route::get('/post/{id}/comments',[CommentsController::class, 'postcomments'])->name('post.comments');

Route::patch('/post/comment/{id}/update',[CommentsController::class, 'updatecomment'])->name('comment.update');

Route::get('post/{id}/like',[LikesController::class, 'toggleLike'])->name('post.like');

Route::get('/post/{id}/likes', [LikesController::class, 'likesection'])->name('post.likes');

Route::delete('/post/comment/{id}/delete', [CommentsController::class, 'deletecomment'])->name('comment.delete');

Route::get('/chat/{id}',[ChatController::class, 'chatform'])->middleware('auth')->name('chat.form');

Route::post('/chat/{receiverId}', [ChatController::class, 'sendmessage'])->middleware('auth');

Route::post('/chat/{id}/mark-as-read', [ChatController::class, 'markAsRead']);

Route::get('/chat', [ChatController::class, 'getchat'])->middleware('auth')->name('chat.list');





