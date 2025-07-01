<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelRequestController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/travel-requests/create', [TravelRequestController::class, 'store'])->name('travel-request.store');
    Route::get('/travel-requests', [TravelRequestController::class, 'index'])->name('travel-request.index');
    Route::get('/travel-requests/{travelRequest}', [TravelRequestController::class, 'show'])->name('travel-request.show');
    Route::patch('/travel-requests/{travelRequest}', [TravelRequestController::class, 'update'])->name('travel-request.update');
});
