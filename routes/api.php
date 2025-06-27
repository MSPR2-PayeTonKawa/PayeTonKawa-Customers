<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AddressesController;
use App\Http\Controllers\Api\CompaniesController;
use App\Http\Controllers\Api\CustomersController;

Route::apiResource('/customers', CustomersController::class)->parameters([
    'customers' => 'customers'
]);
Route::apiResource('/companies', CompaniesController::class)->parameters([
    'companies' => 'companies'
]);
Route::apiResource('/addresses', AddressesController::class)->parameters([
    'addresses' => 'addresses'
]);
