<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("welcome");
});

Route::get("/api/test", function () {
    return response()->json([
        "status" => "success",
        "service" => "customers-api",
        "message" => "Le service Customers API fonctionne correctement",
        "data" => [
            "customers_count" => 42,
            "version" => "1.0.0"
        ]
    ]);
});