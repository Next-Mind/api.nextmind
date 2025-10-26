<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Users\Models\User;
use Database\Seeders\Users\RoleSeeder;
use Database\Seeders\Users\UserSeeder;
use Database\Seeders\Users\PermissionSeeder;
use Database\Seeders\Posts\PostCategorySeeder;
use Database\Seeders\Users\RoleHasPermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleHasPermissionSeeder::class);
        $this->call(PostCategorySeeder::class);
        $this->call(UserSeeder::class);
    }
}
