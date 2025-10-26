<?php

namespace Database\Seeders\Users;

use App\Modules\Users\Models\User;
use Illuminate\Database\Seeder;
use App\Modules\Students\Models\StudentProfile;
use App\Modules\Appointments\Models\Availability;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->withAdminRole()
            ->hasAddresses(2)
            ->hasPhones(2)
            ->state(fn() => ['name' => 'Admin', 'email' => 'admin@admin.com'])
            ->create();
        User::factory(100)
            ->withStudentRole()
            ->has(StudentProfile::factory())
            ->hasAddresses(2)
            ->hasPhones(2)
            ->create();
        User::factory(20)
            ->withAdminRole()
            ->hasAddresses(2)
            ->hasPhones(2)
            ->create();
        User::factory(20)
            ->asPsychologistWithProfileAndDocuments()
            ->hasPosts(10)
            ->hasAddresses(2)
            ->hasPhones(2)
            ->has(
                Availability::factory()
                    ->count(20)
                    ->available(),
                'availabilities'
            )
            ->create();
    }
}
