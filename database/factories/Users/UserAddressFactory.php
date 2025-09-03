<?php

namespace Database\Factories\Users;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Users\UserAddress>
 */
class UserAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => fake()->randomElement(['Work','House','Office']),
            'postal_code' => fake()->postcode(),
            'street' => fake()->streetName(),
            'number' => fake()->randomNumber(4),
            'complement' => fake()->secondaryAddress(),
            'neighborhood' => fake()->streetSuffix(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'country' => 'Brasil',
            'is_primary' => fake()->boolean()
            // 'label' => fake()->randomElement(['Work','House']),
            // 'line1' => fake()->streetName(),
            // 'line2' => fake()->secondaryAddress(),
            // 'district' => fake()->streetSuffix(),
            // 'city' => fake()->city(),
            // 'state' => fake()->stateAbbr(),
            // 'postal_code' => fake()->postcode(),
            // 'country' => fake()->country(),
            // 'is_primary' => fake()->boolean()
        ];
    }
}
