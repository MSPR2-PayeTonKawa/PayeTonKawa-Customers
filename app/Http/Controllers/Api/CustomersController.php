<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Services\RabbitMQPublisher;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Customers",
 *     description="Operations related to customers"
 * )
 */
/** * @OA\Schema(
 *     schema="Customer",
 *     type="object",
 *     required={"username", "last_name", "first_name", "email"},
 *     @OA\Property(property="username", type="string", description="Customer username"),
 *     @OA\Property(property="last_name", type="string", description="Customer last name"),
 *     @OA\Property(property="first_name", type="string", description="Customers first name"),
 *     @OA\Property(property="email", type="string", format="email", description="Customer email"),
 *     @OA\Property(property="phone", type="string", description="Customer phone number (optional)"),
 *     @OA\Property(property="is_active", type="boolean", description="Is the customer active? (optional)"),
 *     @OA\Property(property="company_id", type="integer", description="Company ID (optional, must exist in companies table)")
 * )
 */
class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     * @OA\Get(
     *     path="/api/customers",
     *     summary="List all customers",
     *     tags={"Customers"},
     *     security={{"InternalApiKey": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Customers retrieved successfully.",
     *         @OA\JsonContent(
     *            type="array",
     *            @OA\Items(ref="#/components/schemas/Customer")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'Customers retrieved successfully.',
            'data' => [Customer::all()]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @OA\Post(
     *     path="/api/customers",
     *     summary="Create a new customer",
     *     tags={"Customers"},
     *     security={{"InternalApiKey": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *        response=201,
     *        description="Customer registered successfully."
     *     ),
     *     @OA\Response(
     *        response=422,
     *        description="Validation error",
     *        @OA\JsonContent(
     *           type="object",
     *           @OA\Property(property="message", type="string", example="The given data was invalid."),
     *           @OA\Property(
     *               property="errors",
     *               type="object",
     *               @OA\Property(
     *                   property="username",
     *                   type="array",
     *                   @OA\Items(type="string")
     *               ),
     *               @OA\Property(
     *                   property="email",
     *                   type="array",
     *                   @OA\Items(type="string")
     *               )
     *           )
     *        )
     *     )
     * )
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
                'company_id' => 'nullable|exists:companies,id',
            ]);

            $customer = Customer::create($validated);

            app(RabbitMQPublisher::class)->publishEvent(
                env('RABBITMQ_QUEUE_CUSTOMERS', 'customers'),
                'customer.registered',
                $customer->toArray()
            );

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
     * @OA\Get(
     *     path="/api/customers/{id}",
     *     summary="Get a customer by ID",
     *     tags={"Customers"},
     *     security={{"InternalApiKey": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer found.",
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *    @OA\Response(
     *        response=404,
     *        description="Customer not found."
     *    )
     * )
     */
    public function show($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Customer found.',
            'data' => $customer
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @OA\Patch(
     *     path="/api/customers/{id}",
     *     summary="Update a customer",
     *     tags={"Customers"},
     *     security={{"InternalApiKey": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer updated successfully."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found."
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="username",
     *                     type="array",
     *                     @OA\Items(type="string")
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(type="string")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        $validated = $request->validate([
            'username' => 'sometimes|required|string|max:45|unique:customers,username,' . $customer->id,
            'last_name' => 'sometimes|required|string|max:45',
            'first_name' => 'sometimes|required|string|max:45',
            'email' => 'sometimes|required|email|max:100|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $customer->update($validated);

        app(RabbitMQPublisher::class)->publishEvent(
            env('RABBITMQ_QUEUE_CUSTOMERS', 'customers'),
            'customer.updated',
            $customer->toArray()
        );

        return response()->json([
            'status' => true,
            'message' => 'Customer updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @OA\Delete(
     *     path="/api/customers/{id}",
     *     summary="Delete a customer",
     *     tags={"Customers"},
     *     security={{"InternalApiKey": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Customer deleted successfully."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found."
     *     )
     * )
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        $customerId = $customer->id;
        $customer->delete();

        app(RabbitMQPublisher::class)->publishEvent(
            env('RABBITMQ_QUEUE_CUSTOMERS', 'customers'),
            'customer.deleted',
            ['id' => $customerId]
        );

        return response()->json([
            'status' => true,
            'message' => 'Customer deleted successfully.',
        ], 204);
    }
}
