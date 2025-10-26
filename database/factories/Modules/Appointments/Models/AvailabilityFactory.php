<?php

namespace Database\Factories\Modules\Appointments\Models;

use App\Modules\Appointments\Models\Availability;
use App\Modules\Users\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Appointments\Models\Availability>
 */
class AvailabilityFactory extends Factory
{

    protected $model = Availability::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid()->toString(),
            'reserved_by' => null,
            'date_availability' => $this->faker->dateTimeBetween('+1 day', '+14 days'),
            'status' => 'available',
        ];
    }

    public function available(): self
    {
        return $this->state(fn() => ['status' => 'available', 'reserved_by' => null]);
    }

    public function unavailable(): self
    {
        return $this->state(fn() => ['status' => 'unavailable', 'reserved_by' => null]);
    }

    public function reserved(User $by): self
    {
        return $this->state(function () use ($by) {
            return [
                'status' => 'reserved',
                'reserved_by' => $by?->id ?? User::factory(),
            ];
        });
    }

    public function canceled(): self
    {
        return $this->state(fn() => ['status' => 'canceled']);
    }
}
