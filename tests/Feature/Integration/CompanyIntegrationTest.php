<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_company()
    {
        $response = $this->postJson('/api/companies', [
            'name' => 'Test SARL',
            'email' => 'test@company.fr',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('companies', ['email' => 'test@company.fr']);
    }

    public function test_can_create_company_with_optional_fields()
    {
        $billing = Address::factory()->create();
        $shipping = Address::factory()->create();

        $response = $this->postJson('/api/companies', [
            'name' => 'Test SARL',
            'email' => 'test@company.fr',
            'phone' => '0600000000',
            'billing_address_id' => $billing->id,
            'shipping_address_id' => $shipping->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('companies', ['email' => 'test@company.fr']);
    }

    public function test_cannot_create_company_with_invalid_email()
    {
        $response = $this->postJson('/api/companies', [
            'name' => 'Test SARL',
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_cannot_create_company_with_duplicate_email()
    {
        Company::factory()->create(['email' => 'test@company.fr']);

        $response = $this->postJson('/api/companies', [
            'name' => 'Test SARL',
            'email' => 'test@company.fr',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_can_get_all_companies()
    {
        Company::factory()->count(3)->create();

        $response = $this->getJson('/api/companies');

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_can_get_single_company()
    {
        $company = Company::factory()->create();

        $response = $this->getJson("/api/companies/{$company->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $company->id]);
    }

    public function test_cannot_get_non_existent_company()
    {
        $response = $this->getJson('/api/companies/999');

        $response->assertStatus(404)
                 ->assertJson(['status' => false, 'message' => 'Company not found.']);
    }

    public function test_can_update_company()
    {
        $company = Company::factory()->create();

        $response = $this->patchJson("/api/companies/{$company->id}", [
            'name' => 'Updated Company',
            'email' => 'updated@company.fr',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('companies', ['email' => 'updated@company.fr']);
    }

    public function test_can_update_company_with_optional_fields()
    {
        $company = Company::factory()->create();
        $billing = Address::factory()->create();
        $shipping = Address::factory()->create();

        $response = $this->patchJson("/api/companies/{$company->id}", [
            'phone' => '0600000000',
            'billing_address_id' => $billing->id,
            'shipping_address_id' => $shipping->id,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'phone' => '0600000000',
            'billing_address_id' => $billing->id,
            'shipping_address_id' => $shipping->id,
        ]);
    }

    public function test_cannot_update_company_with_invalid_email()
    {
        $company = Company::factory()->create();

        $response = $this->patchJson("/api/companies/{$company->id}", [
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_cannot_update_company_with_already_used_email()
    {
        Company::factory()->create(['email' => 'test@company.fr']);
        $company2 = Company::factory()->create();

        $response = $this->patchJson("/api/companies/{$company2->id}", [
            'email' => 'test@company.fr',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_cannot_update_with_non_existent_address()
    {
        $company = Company::factory()->create();

        $response = $this->patchJson("/api/companies/{$company->id}", [
            'billing_address_id' => 999,
            'shipping_address_id' => 999,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['billing_address_id', 'shipping_address_id']);
    }

    public function test_cannot_update_non_existent_company()
    {
        $response = $this->patchJson('/api/companies/999', [
            'name' => 'Updated Company',
        ]);

        $response->assertStatus(404)
                 ->assertJson(['status' => false, 'message' => 'Company not found.']);
    }

    public function test_can_delete_company(){
        $company = Company::factory()->create();

        $response = $this->deleteJson("/api/companies/{$company->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }

    public function test_cannot_delete_non_existent_company()
    {
        $response = $this->deleteJson('/api/companies/999');

        $response->assertStatus(404)
                 ->assertJson(['status' => false, 'message' => 'Company not found.']);
    }
}
