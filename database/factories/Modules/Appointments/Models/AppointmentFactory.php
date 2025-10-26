<?php

namespace Database\Factories\Modules\Appointments\Models;

use App\Modules\Appointments\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Appointments\Models\Appointment>
 */
class AppointmentFactory extends Factory
{


    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
