<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/home', [App\Http\Controllers\HomeController::class, 'store'])->name('home-post');

Route::get('/chat-profile', [App\Http\Controllers\ChatProfileController::class, 'index'])->name('chat-profile');
Route::get('/chat-profile/{user}', [App\Http\Controllers\ChatProfileController::class, 'show'])->name('chat-profile-not-me');

Route::get('/chat/{user}', [App\Http\Controllers\ChatController::class, 'show'])->name('chat');
Route::post('/chat/{user}', [App\Http\Controllers\ChatController::class, 'store'])->name('chat-send');

Route::get('/friend', [App\Http\Controllers\FriendController::class, 'index'])->name('friends');
Route::get('/friend/search/{query}', [App\Http\Controllers\FriendController::class, 'search'])->name('friend-search');
Route::post('/friend/{user}', [App\Http\Controllers\FriendController::class, 'store'])->name('add-friend');
Route::delete('/friend/{user}/{page}', [App\Http\Controllers\FriendController::class, 'delete'])->name('deny-request');
Route::patch('/friend/{user}/{page}', [App\Http\Controllers\FriendController::class, 'update'])->name('accept-request');

