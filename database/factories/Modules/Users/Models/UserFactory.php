<?php

namespace Database\Factories\Modules\Users\Models;

use App\Modules\Users\Models\User;
use App\Modules\Users\Models\UserFile;
use App\Modules\Psychologists\Models\PsychologistProfile;
use App\Modules\Psychologists\Models\PsychologistDocument;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Users\Models\User>
 */
class UserFactory extends Factory
{

  protected $model = User::class;

  /**
   * The current password being used by the factory.
   */
  protected static ?string $password;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $faker = $this->faker;
    $faker->addProvider(new \Faker\Provider\pt_BR\Person($faker));

    $email = fake()->unique()->safeEmail();
    return [
      'name'              => fake()->name(),
      'email'             => $email,
      'email_verified_at' => now(),
      'password'          => Hash::make('Password123'),
      'cpf'               => $this->faker->cpf(false),
      'birth_date'        => fake()->date(),
      'photo_url'         => 'https://i.pravatar.cc/300?u=' . $email,
      'remember_token'    => Str::random(10),
    ];
  }

  /**
   * Indicate that the model's email address should be unverified.
   */
  public function unverified(): static
  {
    return $this->state(fn(array $attributes) => [
      'email_verified_at' => null,
    ]);
  }

  /**
   * Método responsável por vincular o cargo de estudante ao modelo que foi criado
   * @return UserFactory
   */
  public function withStudentRole()
  {
    return $this->afterCreating(fn(User $user) => $user->assignRole('student'));
  }

  /**
   * Método responsável por vincular o cargo de psicólogo ao modelo que foi criado
   * @return UserFactory
   */
  public function withPsychologistRole()
  {
    return $this->afterCreating(fn(User $user) => $user->assignRole('psychologist'));
  }

  /**
   * Método responsável por vincular o cargo de administrador ao modelo que foi criado
   * @return UserFactory
   */
  public function withAdminRole()
  {
    return $this->afterCreating(fn(User $user) => $user->assignRole('admin'));
  }

  public function asPsychologistWithProfileAndDocuments(): static
  {
    return $this->withPsychologistRole()
      ->afterCreating(function (User $user) {

        $profile = PsychologistProfile::factory()->for($user)->create();

        $types = ['crp_card', 'id_front', 'id_back', 'proof_of_address'];

        foreach ($types as $type) {
          PsychologistDocument::factory()
            ->for($profile, 'psychologistOwner')
            ->type($type)
            ->for(
              UserFile::factory()
                ->forOwner($user)
                ->pdfNamed($type . '.pdf'),
              'userFile'
            )
            ->create();
        }
      });
  }
}
