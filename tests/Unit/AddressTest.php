<?php

namespace Tests\Feature;

use App\Models\Address;
use Tests\TestCase;

class AddressTest extends TestCase
{
    public function test_return_address_with_complement()
    {
        $address = new Address([
            'number' => '123',
            'number_complement' => 'Apt 4B',
            'way_name' => 'Main St'
        ]);

        $this->assertEquals('123, Main St (Apt 4B)', $address->getFullAddress());
    }

    public function test_return_address_without_complement()
    {
        $address = new Address([
            'number' => '123',
            'number_complement' => null,
            'way_name' => 'Main St'
        ]);

        $this->assertEquals('123, Main St', $address->getFullAddress());
    }

    public function test_return_city_and_zip_code()
    {
        $address = new Address([
            'city' => 'Paris',
            'zip_code' => '75000'
        ]);

        $this->assertEquals('Paris, 75000', $address->getCityAndZipCode());
    }
    public function test_return_coordinates()
    {
        $address = new Address([
            'latitude' => '48.8566',
            'longitude' => '2.3522'
        ]);

        $this->assertEquals('X: 48.8566, Y: 2.3522', $address->getCoordinates());
    }
}
