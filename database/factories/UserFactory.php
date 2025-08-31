<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
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
            'name' => fake()->name(),
            'email' => $email,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'cpf' => $this->faker->cpf(false),
            'birth_date' => fake()->date(),
            'photo_url' => 'https://i.pravatar.cc/300?u='. $email,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Método responsável por vincular o cargo de estudante ao modelo que foi criado
     * @return UserFactory
     */
    public function withStudentRole()
    {
        return $this->afterCreating(fn(User $user)=> $user->assignRole('student'));
    }

    /**
     * Método responsável por vincular o cargo de psicólogo ao modelo que foi criado
     * @return UserFactory
     */
    public function withPsychologistRole()
    {
        return $this->afterCreating(fn(User $user)=> $user->assignRole('psychologist'));
    }

    /**
     * Método responsável por vincular o cargo de administrador ao modelo que foi criado
     * @return UserFactory
     */
    public function withAdminRole()
    {
        return $this->afterCreating(fn(User $user)=> $user->assignRole('admin'));
    }
}
