<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerification;
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

Route::get( '/', function () {
    return view( 'welcome' );
} );

Route::post( '/user-register', [UserController::class, 'user_registration'] );
Route::post( '/user-login', [UserController::class, 'user_login'] );
Route::post( '/send-otp', [UserController::class, 'send_otpcode'] );
Route::post( '/verify-otp', [UserController::class, 'verify_otp'] );
Route::post( '/reset-password', [UserController::class, 'reset_password'] )->middleware( [TokenVerification::class] );
