<?php

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

Route::post('/login', [App\Http\Controllers\AuthController::class, 'store']);
Route::get('/friends', [App\Http\Controllers\FriendController::class, 'friendsApi']);
Route::get('/requests', [App\Http\Controllers\FriendController::class, 'requestsApi']);
Route::get('/search', [App\Http\Controllers\SearchController::class, 'store']);
Route::post('/friend/{user}', [App\Http\Controllers\FriendController::class, 'store']);
Route::delete('/friend/{user}/{page}', [App\Http\Controllers\FriendController::class, 'delete2']);
Route::patch('/friend/{user}/{page}', [App\Http\Controllers\FriendController::class, 'update2']);


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
