<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Addresses;
use Illuminate\Http\Request;

class AddressesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return Addresses::with(['billingCompanies', 'shippingCompanies'])->get();
        return response()->json([
            'status' => true,
            'message' => 'Addresses retrieved successfully.',
            'data' => [Addresses::all()]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|integer',
            'number_complement' => 'nullable|string|max:10',
            'way_name' => 'required|string|max:255',
            'way_type' => 'required|string|max:45',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:45',
            'latitude' => 'nullable|string|max:30',
            'longitude' => 'nullable|string|max:30'
        ]);

        Addresses::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Address registered successfully.',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Addresses $addresses)
    {
        return $addresses->load(['billingCompanies', 'shippingCompanies']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Addresses $addresses)
    {
        $validated = $request->validate([
            'number' => 'sometimes|integer',
            'number_complement' => 'nullable|string|max:10',
            'way_name' => 'sometimes|string|max:255',
            'way_type' => 'sometimes|string|max:45',
            'city' => 'sometimes|string|max:255',
            'zip_code' => 'sometimes|string|max:20',
            'country' => 'sometimes|string|max:45',
            'latitude' => 'nullable|string|max:30',
            'longitude' => 'nullable|string|max:30'
        ]);

        $addresses->update($validated);
        return response()->json([
            'status' => true,
            'message' => 'Address updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Addresses $addresses)
    {
        $addresses->delete();
        return response()->json([
            'status' => true,
            'message' => 'Address deleted successfully.',
        ], 204);
    }
}
