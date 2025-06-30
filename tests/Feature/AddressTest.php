<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Address;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_address()
    {
        $response = $this->postJson('/api/addresses', [
            'number' => 10,
            'way_name' => 'Rue Laravel',
            'way_type' => 'rue',
            'city' => 'Rennes',
            'zip_code' => '35000',
            'country' => 'France',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('addresses', ['city' => 'Rennes']);
    }

    public function test_can_create_address_with_optional_fields()
    {
        $response = $this->postJson('/api/addresses', [
            'number' => 20,
            'number_complement' => 'Bâtiment A',
            'way_name' => 'Avenue Symfony',
            'way_type' => 'avenue',
            'city' => 'Nantes',
            'zip_code' => '44000',
            'country' => 'France',
            'latitude' => '47.218371',
            'longitude' => '-1.553621'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('addresses', ['city' => 'Nantes']);
    }

    public function test_can_get_all_addresses()
    {
        Address::factory()->count(3)->create();

        $response = $this->getJson('/api/addresses');

        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_can_get_single_address()
    {
        $address = Address::factory()->create();

        $response = $this->getJson("/api/addresses/{$address->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $address->id]);
    }

    public function test_cannot_get_non_existent_address()
    {
        $response = $this->getJson('/api/addresses/999');

        $response->assertStatus(404)
                 ->assertJson(['status' => false, 'message' => 'Address not found.']);
    }

    public function test_can_update_address()
    {
        $address = Address::factory()->create();

        $response = $this->patchJson("/api/addresses/{$address->id}", [
            'city' => 'Nantes',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('addresses', ['city' => 'Nantes']);
    }

    public function test_can_update_address_with_optional_fields()
    {
        $address = Address::factory()->create();

        $response = $this->patchJson("/api/addresses/{$address->id}", [
            'number_complement' => 'Bâtiment B',
            'latitude' => '48.856613',
            'longitude' => '2.352222'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'number_complement' => 'Bâtiment B',
            'latitude' => '48.856613',
            'longitude' => '2.352222'
        ]);
    }

    public function test_cannot_update_non_existent_address()
    {
        $response = $this->patchJson('/api/addresses/999', [
            'city' => 'Paris',
        ]);

        $response->assertStatus(404)
                 ->assertJson(['status' => false, 'message' => 'Address not found.']);
    }

    public function test_can_delete_address()
    {
        $address = Address::factory()->create();

        $response = $this->deleteJson("/api/addresses/{$address->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    public function test_cannot_delete_non_existent_address()
    {
        $response = $this->deleteJson('/api/addresses/999');

        $response->assertStatus(404)
                 ->assertJson(['status' => false, 'message' => 'Address not found.']);
    }
}
