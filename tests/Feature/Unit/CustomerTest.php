<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_customer()
    {
        $response = $this->postJson('/api/customers', [
            'username' => 'toto',
            'last_name' => 'Test',
            'first_name' => 'Tom',
            'email' => 'toto@test.com',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('customers', ['email' => 'toto@test.com']);
    }

    public function test_can_create_customer_with_optional_fields()
    {
        $company = Company::factory()->create();

        $response = $this->postJson('/api/customers', [
            'username' => 'toto',
            'last_name' => 'Test',
            'first_name' => 'Tom',
            'email' => 'toto@test.com',
            'phone' => '0600000000',
            'company_id' => $company->id
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('customers', ['email' => 'toto@test.com']);
    }

    public function test_cannot_create_customer_with_invalid_email()
    {
        $response = $this->postJson('/api/customers', [
            'username' => 'toto',
            'last_name' => 'Test',
            'first_name' => 'Tom',
            'email' => 'invalid-email'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_cannot_create_customer_with_duplicate_email()
    {
        Customer::factory()->create(['email' => 'toto@test.com']);

        $response = $this->postJson('/api/customers', [
            'username' => 'toto',
            'last_name' => 'Test',
            'first_name' => 'Tom',
            'email' => 'toto@test.com',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_cannot_create_customer_with_duplicate_username()
    {
        Customer::factory()->create(['username' => 'toto']);

        $response = $this->postJson('/api/customers', [
            'username' => 'toto',
            'last_name' => 'Test',
            'first_name' => 'Tom',
            'email' => 'toto@test.com'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['username']);
    }

    public function test_can_get_all_customers()
    {
        Customer::factory()->count(2)->create();

        $response = $this->getJson('/api/customers');
        $response->assertStatus(200);
    }

    public function test_can_get_single_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->getJson("/api/customers/{$customer->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $customer->id]);
    }

    public function test_cannot_get_non_existent_customer()
    {
        $response = $this->getJson('/api/customers/999999');

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Customer not found.']);
    }

    public function test_can_update_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->patchJson("/api/customers/{$customer->id}", [
            'username' => 'newusername',
            'last_name' => 'NewLastName',
            'first_name' => 'NewFirstName',
            'email' => 'newemail@example.com'
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('customers', ['email' => 'newemail@example.com']);
    }

    public function test_can_update_customer_with_optional_fields()
    {
        $customer = Customer::factory()->create();

        $response = $this->patchJson("/api/customers/{$customer->id}", [
            'phone' => '0600000000'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('customers', ['phone' => '0600000000']);
    }

    public function test_cannot_update_customer_with_invalid_email()
    {
        $customer = Customer::factory()->create();

        $response = $this->patchJson("/api/customers/{$customer->id}", [
            'email' => 'invalid-email'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_cannot_update_customer_with_already_used_email()
    {
        Customer::factory()->create(['email' => 'test@customer.com']);
        $existingCustomer = Customer::factory()->create();

        $response = $this->patchJson("/api/customers/{$existingCustomer->id}", [
            'email' => 'test@customer.com',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_cannot_update_customer_with_already_used_username()
    {
        Customer::factory()->create(['username' => 'existingusername']);
        $existingCustomer = Customer::factory()->create();

        $response = $this->patchJson("/api/customers/{$existingCustomer->id}", [
            'username' => 'existingusername',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['username']);
    }

    public function test_cannot_update_customer_with_non_existent_company()
    {
        $customer = Customer::factory()->create();

        $response = $this->patchJson("/api/customers/{$customer->id}", [
            'company_id' => 999999 // Non-existent company ID
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['company_id']);
    }

    public function test_cannot_update_non_existent_customer()
    {
        $response = $this->patchJson('/api/customers/999999', [
            'username' => 'newusername',
            'last_name' => 'NewLastName',
            'first_name' => 'NewFirstName',
            'email' => 'newemail@example.com'
        ]);

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Customer not found.']);
    }

    public function test_can_delete_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->deleteJson("/api/customers/{$customer->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_cannot_delete_non_existent_customer()
    {
        $response = $this->deleteJson('/api/customers/999999');

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Customer not found.']);
    }
}
