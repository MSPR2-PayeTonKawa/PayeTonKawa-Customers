<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Customer API Routes
Route::prefix('customers')->group(function () {
    Route::get('/', function () {
        return response()->json(['message' => 'Customer API is working!']);
    });

    // Add more routes here when implementing the full API
    // Route::apiResource('/', CustomerController::class);
});
