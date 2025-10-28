<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Users\Models\User;
use Database\Seeders\Users\RoleSeeder;
use Database\Seeders\Users\UserSeeder;
use Database\Seeders\Users\PermissionSeeder;
use Database\Seeders\Posts\PostCategorySeeder;
use Database\Seeders\HelpDesk\TicketStatusSeeder;
use Database\Seeders\HelpDesk\TicketCategorySeeder;
use Database\Seeders\Users\RoleHasPermissionSeeder;
use Database\Seeders\HelpDesk\TicketSubcategorySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RoleHasPermissionSeeder::class,
            PostCategorySeeder::class,
            UserSeeder::class,
            TicketStatusSeeder::class,
            TicketCategorySeeder::class,
            TicketSubcategorySeeder::class,
        ]);
    }
}
