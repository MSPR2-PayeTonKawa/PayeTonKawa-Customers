<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\Company;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Companies",
 *     description="Operations related to companies"
 * )
 */
/**
 * @OA\Schema(
 *     schema="Company",
 *     type="object",
 *     required={"name", "email"},
 *     @OA\Property(property="name", type="string", description="Company name"),
 *     @OA\Property(property="email", type="string", format="email", description="Company email"),
 *     @OA\Property(property="phone", type="string", description="Company phone number (optional)"),
 *     @OA\Property(property="billing_address_id", type="integer", description="Billing address ID (optional)"),
 *     @OA\Property(property="shipping_address_id", type="integer", description="Shipping address ID (optional)")
 * )
 */
class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @OA\Get(
     *     path="/api/companies",
     *     summary="List all companies",
     *     tags={"Companies"},
     *     security={{"InternalApiKey": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Companies retrieved successfully.",
     *         @OA\JsonContent(
     *            type="array",
     *            @OA\Items(ref="#/components/schemas/Company")
     *         )
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/companies",
     *     summary="Create a new company",
     *     tags={"Companies"},
     *     security={{"InternalApiKey": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Company")
     *     ),
     *     @OA\Response(
     *        response=201,
     *        description="Company created successfully."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="The email field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/companies/{id}",
     *     summary="Get a company by ID",
     *     tags={"Companies"},
     *     security={{"InternalApiKey": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company retrieved successfully.",
     *         @OA\JsonContent(ref="#/components/schemas/Company")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found."
     *     )
     * )
     */
    public function show($id)
    {
        $company = Company::find($id);

        if (!$company) {
            return response()->json([
                'status' => false,
                'message' => 'Company not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Company found.',
            'data' => $company
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @OA\Patch(
     *     path="/api/companies/{id}",
     *     summary="Update a company",
     *     tags={"Companies"},
     *     security={{"InternalApiKey": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Company")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company updated successfully."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string", example="The email field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $company = Company::find($id);

        if (!$company) {
            return response()->json([
                'status' => false,
                'message' => 'Company not found.',
            ], 404);
        }

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
     * @OA\Delete(
     *     path="/api/companies/{id}",
     *     summary="Delete a company",
     *     tags={"Companies"},
     *     security={{"InternalApiKey": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Company deleted successfully."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found."
     *     )
     * )
     */
    public function destroy($id)
    {
        $company = Company::find($id);

        if (!$company) {
            return response()->json([
                'status' => false,
                'message' => 'Company not found.',
            ], 404);
        }

        $company->delete();
        return response()->json([
            'status' => true,
            'message' => 'Company deleted successfully.',
        ], 204);
    }
}
