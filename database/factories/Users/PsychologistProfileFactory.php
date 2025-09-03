<?php

namespace Database\Factories\Users;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Users\PsychologistProfile>
 */
class PsychologistProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'crp' => fake()->unique()->randomNumber(8,true),
            'speciality' => fake()->word(),
            'bio' => fake()->sentence(),
            'status' => 'approved'
        ];
    }
}
