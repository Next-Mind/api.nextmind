<?php

namespace Database\Seeders\HelpDesk;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Modules\HelpDesk\Models\TicketStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'id'        => (string) Str::uuid(),
                'name'      => 'Open',
                'slug'      => 'open',
                'is_final' => false,
                'position' => 1
            ],
            [
                'id'        => (string) Str::uuid(),
                'name'      => 'In Progress',
                'slug'      => 'in-progress',
                'is_final' => false,
                'position' => 2
            ],
            [
                'id'        => (string) Str::uuid(),
                'name'      => 'Waiting for Customer',
                'slug'      => 'waiting-customer',
                'is_final' => false,
                'position' => 3
            ],
            [
                'id'        => (string) Str::uuid(),
                'name'      => 'Resolved',
                'slug'      => 'resolved',
                'is_final' => true,
                'position' => 4
            ],
            [
                'id'        => (string) Str::uuid(),
                'name'      => 'Closed',
                'slug'      => 'closed',
                'is_final' => true,
                'position' => 5
            ],
        ];

        foreach ($statuses as $status) {
            TicketStatus::query()
                ->updateOrCreate(
                    ['slug' => $status['slug']], // match pelo slug
                    $status
                );
        }
    }
}
