<?php

namespace Database\Factories\Modules\Psychologists\Models;

use App\Modules\Users\Models\UserFile;
use App\Modules\Psychologists\Models\PsychologistProfile;
use App\Modules\Psychologists\Models\PsychologistDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Psychologists\Models\PsychologistDocument>
 */
class PsychologistDocumentFactory extends Factory
{

    protected $model = PsychologistDocument::class;

    public function definition(): array
    {
        return [
            'type'   => $this->faker->randomElement(['crp_card', 'id_front', 'id_back', 'proof_of_address']),
            'status' => 'pending',
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (PsychologistDocument $doc) {
            if (!$doc->psychologist_profile_id) {
                $profile = PsychologistProfile::factory()->create();
                $doc->psychologist_profile_id = $profile->getKey();
            } else {
                $profile = PsychologistProfile::find($doc->psychologist_profile_id);
            }

            if (!$doc->user_file_id) {
                $user = $profile->psychologist;
                $file = UserFile::factory()
                    ->forOwner($user)
                    ->pdfNamed($doc->type . '.pdf')
                    ->create();

                $doc->user_file_id = $file->getKey();
            }
        });
    }

    public function forProfile(PsychologistProfile $profile): static
    {
        return $this->state(fn() => ['psychologist_profile_id' => $profile->getKey()]);
    }

    public function type(string $type): static
    {
        return $this->state(fn() => ['type' => $type]);
    }
}
