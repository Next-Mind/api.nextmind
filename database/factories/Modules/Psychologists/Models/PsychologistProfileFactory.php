<?php

namespace Database\Factories\Modules\Psychologists\Models;

use App\Modules\Psychologists\Models\PsychologistProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Psychologists\Models\PsychologistProfile>
 */
class PsychologistProfileFactory extends Factory
{

    protected $model = PsychologistProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'crp' => fake()->unique()->randomNumber(8, true),
            'speciality' => fake()->word(),
            'bio' => fake()->sentence(),
            'status' => 'approved'
        ];
    }
}
