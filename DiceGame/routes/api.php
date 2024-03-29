<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginRegisterController;
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
// Public routes of authtication
Route::controller(LoginRegisterController::class)->group(function() {
    Route::post('/players', 'register')->name('user.register');
    Route::post('/login', 'login')->name('user.login');
});

Route::middleware('auth:api')->group( function () {
    Route::post('/logout', [LoginRegisterController::class, 'logout'])->name('user.logout');
});

Route::middleware('auth:api')->group(function(){
    
    Route::middleware('role:player')->group(function () {
        Route::post('/players/{id}/games/',[GameController::class,'play'])->name('games.play');
        Route::delete('/players/{id}/games/',[GameController::class,'destroy'])->name('games.destroy');
        Route::get('players/{id}/games',[GameController::class,'index'])->name('games.index');
        Route::put('/players/{id}', [UserController::class, 'update'])->name('user.update');
    });

    Route::middleware('role:admin')->group(function(){
        Route::get('/players', [UserController::class, 'index'])->name('user.index'); 
        Route::get('/players/ranking', [UserController::class, 'getRanking'])->name('user.ranking'); 
        Route::get('/players/ranking/loser', [UserController::class, 'getLoser'])->name('user.loser');
        Route::get('/players/ranking/winner', [UserController::class, 'getWinner'])->name('user.winner');
    });
    

});

