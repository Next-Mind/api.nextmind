<?php

namespace Database\Factories\Modules\Psychologists\Models;

use Illuminate\Support\Carbon;
use App\Modules\Users\Models\User;
use App\Modules\Users\Models\UserFile;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Psychologists\Models\PsychologistProfile;
use App\Modules\Psychologists\Models\PsychologistDocument;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Psychologists\Models\PsychologistDocument>
 */
class PsychologistDocumentFactory extends Factory
{
    protected $model = PsychologistDocument::class;

    public function definition(): array
    {
        return [
            'psychologist_profile_id' => PsychologistProfile::factory(),
            'type'        => $this->faker->randomElement(['crp_card', 'id_front', 'id_back', 'proof_of_address']),
            'status'      => 'pending',

            'reviewed_by' => null,
            'reviewed_at' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn() => [
            'status'      => 'pending',
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);
    }

    public function approvedDoc(?User $adminReviewer = null): static
    {
        return $this->state(function () use ($adminReviewer) {

            $reviewer = $adminReviewer ?: User::factory()
                ->withAdminRole()
                ->create();

            $ts = Carbon::now()->subDay();

            return [
                'status'      => 'approved',
                'reviewed_by' => $reviewer->getKey(),
                'reviewed_at' => $ts,
            ];
        });
    }

    public function type(string $type): static
    {
        return $this->state(fn() => [
            'type' => $type,
        ]);
    }

    public function forProfile(PsychologistProfile $profile): static
    {
        return $this->state(fn() => [
            'psychologist_profile_id' => $profile->getKey(),
        ]);
    }

    public function withFileForOwner(User $owner): static
    {
        return $this->afterMaking(function (PsychologistDocument $doc) use ($owner) {

            if ($doc->user_file_id) {
                return;
            }

            $originalName = ($doc->type ?? 'document') . '.pdf';

            $file = UserFile::factory()
                ->forOwner($owner)
                ->pdfNamed($originalName)
                ->create();

            $doc->user_file_id = $file->getKey();
        });
    }
}
