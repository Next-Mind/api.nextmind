<?php

namespace Database\Factories\Users;

use App\Models\Users\UserFile;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
* @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Users\UserFile>
*/
class UserFileFactory extends Factory
{
    /**
    * Define the model's default state.
    *
    * @return array<string, mixed>
    */
    public function definition(): array
    {
        
        // $disk = 'local';
        // $original = $this->faker->randomElement([
        //     'crp_card.pdf', 'id_front.pdf', 'id_back.pdf', 'proof_of_address.pdf'
        // ]);
        // // $path = 'uploads/'.Str::uuid().'/psychologist_doc/'.$original;
        
        return [
            'purpose'   => 'psychologist_doc',
            'mime_type' => 'application/pdf',
        ];
    }
    
    public function configure()
    {
        return $this
        ->afterMaking(function (UserFile $file) {
            $ownerId = $file->user_id ?? $file->user?->getKey();
            
            if (!$ownerId) {
                throw new \RuntimeException('UserFileFactory precisa de user associado para montar o path.');
            }
            
            $file->path = 'uploads/'.$ownerId.'/psychologist_doc/'.$file->original_name;
        })
        ->afterCreating(function (UserFile $file) {
            // grava um PDF fake para testes/dev
            if (!Storage::disk('local')->exists($file->path)) {
                Storage::disk('local')->put($file->path, "%PDF-1.4\n% Fake\n%%EOF\n");
            }
        });
    }

    public function forOwner(\App\Models\User $user): static
    {
        return $this->for($user, 'user');
    }

    public function pdfNamed(string $name): static
    {
        return $this->state(fn () => ['original_name' => $name]);
    }
}
