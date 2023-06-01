<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\User\ProfileUserController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::group(['prefix' => 'user'], function ($router) {
    Route::post('/profile-user', [ProfileUserController::class, 'profileUpdate']);
    Route::post('/change-password', [ProfileUserController::class, 'updatePassword']);
    Route::get('/contact-user', [ProfileUserController::class, 'contactUsers']);
});