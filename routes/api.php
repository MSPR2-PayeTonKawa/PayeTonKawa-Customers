<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AddressesController;
use App\Http\Controllers\Api\CompaniesController;
use App\Http\Controllers\Api\CustomersController;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */

Route::apiResource('/customers', CustomersController::class);
Route::apiResource('/companies', CompaniesController::class);
Route::apiResource('/addresses', AddressesController::class);
