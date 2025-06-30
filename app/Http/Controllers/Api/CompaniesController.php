<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\Company;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'Companies retrieved successfully.',
            'data' => [Company::all()]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:100|unique:companies,email',
                'phone' => 'nullable|string|max:20',
                'billing_address_id' => 'nullable|exists:addresses,id',
                'shipping_address_id' => 'nullable|exists:addresses,id'
            ]);

            Company::create($validated);

            return response()->json([
                'status' => true,
                'message' => 'Company registered successfully.',
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
    public function show(Company $company)
    {
        return response()->json([
            'status' => true,
            'message' => 'Company found.',
            'data' => $company
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|max:100|unique:companies,email,' . $company->email,
            'phone' => 'nullable|string|max:20',
            'billing_address_id' => 'nullable|exists:addresses,id',
            'shipping_address_id' => 'nullable|exists:addresses,id'
        ]);

        $company->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Company updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return response()->json([
            'status' => true,
            'message' => 'Company deleted successfully.',
        ], 204);
    }
}
