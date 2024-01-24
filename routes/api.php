<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/verify',[AuthController::class, 'sms_verification']);
Route::post('/signin',[AuthController::class, 'signin']);
Route::post('/forget_pwd',[AuthController::class, 'forgot_password']);
Route::post('/reset_pwd',[AuthController::class, 'reset_password']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/direct_m',[TestingController::class, 'direct_method']);

Route::post('/little_f',[TestingController::class, 'little_friend']);

Route::post('/big_f',[TestingController::class, 'big_friend']);
