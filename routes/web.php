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
    return view('welcome');
});

Route::prefix('users')->group(function() {
    Route::post('/', [\App\Http\Controllers\Api\UserController::class, 'create'])->name('user-create');
    Route::post('/{user_id}/score', [\App\Http\Controllers\Api\UserController::class, 'score'])->name('user-score');
});
Route::prefix('leaderboard')->group(function() {
    Route::get('/top', [\App\Http\Controllers\Api\BoardController::class, 'index'])->name('leaderboard');
    Route::get('/rank/{user_id}', [\App\Http\Controllers\Api\BoardController::class, 'rank'])->name('user-rank');
});

