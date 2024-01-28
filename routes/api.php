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

Route::post('/signin',[AuthController::class, 'signin']);
Route::post('/verify',[AuthController::class, 'sms_verification']);
Route::get('/resend_otp/{id}',[AuthController::class, 'resendOTP']);
Route::post('/signup',[AuthController::class, 'signup']);
Route::post('/forget_pwd',[AuthController::class, 'forgot_password']);
Route::post('/reset_pwd',[AuthController::class, 'reset_password']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/direct_method',[TestingController::class, 'generateDMQ']);

Route::post('/little_friend',[TestingController::class, 'generateLFQ']);

Route::post('/big_friend',[TestingController::class, 'generateBFQ']);

Route::post('/level_1',[TestingController::class, 'generate_lv1']);
