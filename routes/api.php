<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AddressesController;
use App\Http\Controllers\Api\CompaniesController;
use App\Http\Controllers\Api\CustomersController;

Route::apiResource('/customers', CustomersController::class);
Route::apiResource('/companies', CompaniesController::class);
Route::apiResource('/addresses', AddressesController::class);
