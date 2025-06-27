<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Models\Customers;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'Customers retrieved successfully.',
            'data' => [Customers::all()]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:45|unique:customers,username',
                'last_name' => 'required|string|max:45',
                'first_name' => 'required|string|max:45',
                'email' => 'required|email|max:100|unique:customers,email',
                'phone' => 'nullable|string|max:20',
                'is_active' => 'boolean',
                'password_updated_at' => 'nullable|date',
                'company_id' => 'nullable|exists:companies,id',
            ]);

            Customers::create($validated);

            return response()->json([
                'status' => true,
                'message' => 'Customer registered successfully.',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Customers $customers)
    {
        return response()->json([
            'status' => true,
            'message' => 'Customer found.',
            'data' => $customers
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customers $customers)
    {
        $validated = $request->validate([
            'username' => 'sometimes|required|string|max:45|unique:customers,username,' . $customers->id,
            'last_name' => 'sometimes|required|string|max:45',
            'first_name' => 'sometimes|required|string|max:45',
            'email' => 'sometimes|required|email|max:100|unique:customers,email,' . $customers->id,
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'password_updated_at' => 'nullable|date',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $customers->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Customer updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customers $customers)
    {
        $customers->delete();
        return response()->json([
            'status' => true,
            'message' => 'Customer deleted successfully.',
        ], 204);
    }
}
