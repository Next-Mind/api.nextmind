<?php

namespace Database\Seeders\Users;

use App\Models\Spatie\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'psychologist']);
        Role::create(['name' => 'student']);
        Role::create(['name' => 'default']);
    }
}
