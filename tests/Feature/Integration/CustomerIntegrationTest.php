<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerIntegrationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_full_customer_flow()
    {
        $company = Company::factory()->create();

        $response = $this->postJson('/api/customers', [
            'username' => 'toto',
            'last_name' => 'Test',
            'first_name' => 'Tom',
            'email' => 'toto@test.com',
            'company_id' => $company->id
        ]);

        $response->assertStatus(201);

        $customerId = $response->json('id') ?? Customer::first()->id;

        $this->assertDatabaseHas('customers', [
            'email' => 'toto@test.com'
        ]);

        $get = $this->getJson("/api/customers/{$customerId}");
        $get->assertStatus(200)
            ->assertJsonFragment(['email' => 'toto@test.com']);

        $update = $this->patchJson("/api/customers/{$customerId}", [
            'first_name' => 'Tommy'
        ]);
        $update->assertStatus(200);
        $this->assertDatabaseHas('customers', ['first_name' => 'Tommy']);

        $delete = $this->deleteJson("/api/customers/{$customerId}");
        $delete->assertStatus(204);
        $this->assertDatabaseMissing('customers', ['id' => $customerId]);
    }
}
