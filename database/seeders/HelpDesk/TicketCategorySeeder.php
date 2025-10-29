<?php

namespace Database\Seeders\HelpDesk;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Modules\HelpDesk\Models\TicketCategory;

class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'id'       => (string) Str::uuid(),
                'name'     => 'Bug / Error',
                'slug'     => 'bug',
                'position' => 1,
            ],
            [
                'id'       => (string) Str::uuid(),
                'name'     => 'Improvement / Suggestion',
                'slug'     => 'improvement',
                'position' => 2,
            ],
            [
                'id'       => (string) Str::uuid(),
                'name'     => 'Report / Abuse / Violation',
                'slug'     => 'report',
                'position' => 3,
            ],
        ];

        foreach ($categories as $cat) {
            TicketCategory::query()
                ->updateOrCreate(
                    ['slug' => $cat['slug']],
                    $cat
                );
        }
    }
}
