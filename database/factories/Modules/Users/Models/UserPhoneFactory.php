<?php

namespace Database\Factories\Modules\Users\Models;

use App\Modules\Users\Models\UserPhone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Users\Models\UserPhone>
 */
class UserPhoneFactory extends Factory
{
    protected $model = UserPhone::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' =>  fake()->randomElement(['Work', 'Personal']),
            'country_code' => '55',
            'area_code' => fake()->areaCode(),
            'number' => fake()->phone(),
            'is_whatsapp' => fake()->boolean(),
            'is_primary' => fake()->boolean(),
        ];
    }
}
