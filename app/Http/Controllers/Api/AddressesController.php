<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Addresses",
 *     description="Operations related to addresses"
 * )
 */
/**
 * @OA\Schema(
 *     schema="Address",
 *     type="object",
 *     required={"number", "way_name", "way_type", "city", "zip_code", "country"},
 *     @OA\Property(property="number", type="integer", description="House or building number"),
 *     @OA\Property(property="number_complement", type="string", description="Additional address information (optional)"),
 *     @OA\Property(property="way_name", type="string", description="Street name"),
 *     @OA\Property(property="way_type", type="string", description="Type of street (e.g., Street, Avenue)"),
 *     @OA\Property(property="city", type="string", description="City name"),
 *     @OA\Property(property="zip_code", type="string", description="Postal code"),
 *     @OA\Property(property="country", type="string", description="Country name"),
 *     @OA\Property(property="latitude", type="string", description="Latitude coordinate (optional)"),
 *     @OA\Property(property="longitude", type="string", description="Longitude coordinate (optional)")
 * )
 */
class AddressesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @OA\Get(
     *     path="/api/addresses",
     *     summary="List all addresses",
     *     tags={"Addresses"},
     *     @OA\Response(
     *         response=200,
     *         description="Addresses retrieved successfully.",
     *         @OA\JsonContent(
     *            type="array",
     *            @OA\Items(ref="#/components/schemas/Address")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'Addresses retrieved successfully.',
            'data' => [Address::all()]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @OA\Post(
     *     path="/api/addresses",
     *     summary="Create a new address",
     *     tags={"Addresses"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Address")
     *     ),
     *    @OA\Response(
     *         response=201,
     *         description="Address created successfully."
     *     )
     * )
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

        Address::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Address registered successfully.',
        ], 201);
    }

    /**
     * Display the specified resource.
     * @OA\Get(
     *     path="/api/addresses/{id}",
     *     summary="Get an address by ID",
     *     tags={"Addresses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Address found.",
     *         @OA\JsonContent(ref="#/components/schemas/Address")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Address not found."
     *     )
     * )
     */
    public function show($id)
    {
        $address = Address::find($id);

        if (!$address) {
            return response()->json([
                'status' => false,
                'message' => 'Address not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Address found.',
            'data' => $address
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @OA\Patch(
     *     path="/api/addresses/{id}",
     *     summary="Update an address",
     *     tags={"Addresses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Address")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Address updated successfully."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Address not found."
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $address = Address::find($id);

        if (!$address) {
            return response()->json([
                'status' => false,
                'message' => 'Address not found.',
            ], 404);
        }

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

        $address->update($validated);
        return response()->json([
            'status' => true,
            'message' => 'Address updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @OA\Delete(
     *     path="/api/addresses/{id}",
     *     summary="Delete an address",
     *     tags={"Addresses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Address deleted successfully."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Address not found."
     *     )
     * )
     */
    public function destroy($id)
    {
        $address = Address::find($id);

        if (!$address) {
            return response()->json([
                'status' => false,
                'message' => 'Address not found.',
            ], 404);
        }

        $address->delete();
        return response()->json([
            'status' => true,
            'message' => 'Address deleted successfully.',
        ], 204);
    }
}
