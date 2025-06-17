<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;

Route::prefix('api')->group(function () {
    Route::get('/test', [CustomerController::class, 'test']);
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
});
