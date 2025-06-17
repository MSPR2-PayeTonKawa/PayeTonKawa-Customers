<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Events\CustomerRegisteredEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Customers;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        $customers = Customers::all();

        return response()->json([
            'success' => true,
            'data' => $customers,
            'count' => $customers->count()
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        $customer = Customers::create($validatedData);

        $event = new CustomerRegisteredEvent();
        $event->publish([
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'data' => $customer
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $customer = Customers::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $customer
        ]);
    }

    public function test(): JsonResponse
    {
        return response()->json([
            'service' => 'customers',
            'status' => 'running',
            'message' => 'Customers API is operational',
            'timestamp' => now()->toISOString()
        ]);
    }
}
