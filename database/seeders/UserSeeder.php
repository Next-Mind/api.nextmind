<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Users\PsychologistProfile;
use App\Models\Users\StudentProfile;
use App\Models\Users\UserAddress;
use App\Models\Users\UserPhone;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            ->state(fn()=>['name'=>'Admin','email'=>'admin@admin.com'])
            ->create();
        User::factory(50)
            ->withStudentRole()
            ->has(StudentProfile::factory())
            ->hasAddresses(2)
            ->hasPhones(2)
            ->create();
        User::factory(5)
            ->withAdminRole()
            ->hasAddresses(2)
            ->hasPhones(2)
            ->create();
        User::factory(20)
            ->asPsychologistWithProfileAndDocuments()
            ->hasAddresses(2)
            ->hasPhones(2)
            ->create();

    }
}
