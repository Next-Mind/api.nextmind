<?php

namespace Database\Factories\Users;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Users\StudentProfile>
 */
class StudentProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ra' => fake()->unique()->randomNumber(5,true),
            'course' => fake()->randomElement(['Edificações','Enfermagem','Geodésia','Administração','ADS']),
            'bio' => fake()->sentence()
        ];
    }
}
