<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Companies;

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
            'data' => [Companies::all()]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:companies,email',
            'phone' => 'nullable|string|max:20',
            'billing_address_id' => 'nullable|exists:addresses,id',
            'shipping_address_id' => 'nullable|exists:addresses,id'
        ]);

        Companies::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Company registered successfully.',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Companies $companies)
    {
        //return $companies->load(['billingAddresses', 'shippingAddresses']);
        return response()->json([
            'status' => true,
            'message' => 'Company found.',
            'data' => $companies
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Companies $companies)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|max:100|unique:companies,email,' . $companies->email,
            'phone' => 'nullable|string|max:20',
            'billing_address_id' => 'nullable|exists:addresses,id',
            'shipping_address_id' => 'nullable|exists:addresses,id'
        ]);

        $companies->update($validated);
        return response()->json([
            'status' => true,
            'message' => 'Company updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Companies $companies)
    {
        $companies->delete();
        return response()->json([
            'status' => true,
            'message' => 'Company deleted successfully.',
        ], 204);
    }
}
