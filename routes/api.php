<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DailyController;
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

Route::post('/login', [AuthController::class, 'login']);


Route::group(['middleware'=> 'auth:sanctum'], function(){
    Route::get('/daily', [DailyController::class, 'index']);
    Route::post('/daily/store', [DailyController::class, 'store']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
