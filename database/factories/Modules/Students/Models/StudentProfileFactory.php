<?php

namespace Database\Factories\Modules\Students\Models;

use App\Modules\Students\Models\StudentProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Students\Models\StudentProfile>
 */
class StudentProfileFactory extends Factory
{

    protected $model = StudentProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ra' => fake()->unique()->randomNumber(5, true),
            'course' => fake()->randomElement(['Edificações', 'Enfermagem', 'Geodésia', 'Administração', 'ADS']),
            'bio' => fake()->sentence()
        ];
    }
}
