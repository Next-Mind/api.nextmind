<?php

namespace Database\Factories\Modules\Psychologists\Models;

use Illuminate\Support\Carbon;
use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Psychologists\Models\PsychologistProfile;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Psychologists\Models\PsychologistProfile>
 */
class PsychologistProfileFactory extends Factory
{
    protected $model = PsychologistProfile::class;

    public function definition(): array
    {
        $submittedAt = Carbon::now()->subDays(rand(2, 10));

        return [
            'user_id'        => User::factory()->withPsychologistRole(),
            'crp'            => $this->faker->numerify('##/######'),
            'speciality'     => $this->faker->randomElement([
                'TCC',
                'Psicanálise',
                'Neuropsicologia',
                'Terapia Familiar Sistêmica'
            ]),
            'bio'            => $this->faker->sentence(12),

            'status'         => 'pending',

            'submitted_at'   => $submittedAt,

            'approved_at'    => null,
            'approved_by'    => null,
            'verified_at'    => null,

            'rejected_at'      => null,
            'rejection_reason' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn() => [
            'status'       => 'pending',
            'approved_at'  => null,
            'approved_by'  => null,
            'verified_at'  => null,
        ]);
    }

    public function approved(?User $adminReviewer = null): static
    {
        return $this->state(function (array $attributes) use ($adminReviewer) {
            $approvedAt = Carbon::now()->subDays(rand(0, 1));

            $reviewer = $adminReviewer ?: User::factory()
                ->withAdminRole()
                ->create();

            return [
                'status'       => 'approved',
                'approved_at'  => $approvedAt,
                'approved_by'  => $reviewer->getKey(),
                'verified_at'  => $approvedAt,
            ];
        });
    }
}
