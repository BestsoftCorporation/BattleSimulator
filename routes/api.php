<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Game;
use App\Models\Army;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ArmyController;
use App\Http\Controllers\StartGame;
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



Route::resource('createGame','App\Http\Controllers\GameController');
Route::resource('Army','App\Http\Controllers\ArmyController');
Route::get('/startGame', StartGame::class);
Route::get('/gameArmy/{id}', 'App\Http\Controllers\ArmyController@getGameArmy');




Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


