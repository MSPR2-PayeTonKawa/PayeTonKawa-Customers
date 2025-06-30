<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->buildingNumber(),
            'number_complement' => $this->faker->optional()->bothify('Bât. ##'),
            'way_name' => $this->faker->streetName(),
            'way_type' => $this->faker->randomElement(['rue', 'avenue', 'boulevard', 'chemin']),
            'city' => $this->faker->city(),
            'zip_code' => $this->faker->postcode(),
            'country' => 'France',
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ];
    }

}
