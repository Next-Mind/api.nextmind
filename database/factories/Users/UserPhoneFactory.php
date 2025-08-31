<?php

namespace Database\Factories\Users;

use App\Models\Users\UserPhone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Users\UserPhone>
 */
class UserPhoneFactory extends Factory
{

    /**
     * Model
     * @var class-string<\App\Models\Users\UserPhone>
     */
    protected $model = UserPhone::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' =>  fake()->randomElement(['Work','Personal']),
            'country_code' => '55',
            'area_code' => fake()->areaCode(),
            'number' => fake()->phone(),
            'is_whatsapp' => fake()->boolean(),
            'is_primary' => fake()->boolean(),
        ];
    }
}
