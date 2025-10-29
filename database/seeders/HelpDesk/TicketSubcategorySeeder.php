<?php

namespace Database\Seeders\HelpDesk;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Modules\HelpDesk\Models\TicketCategory;
use App\Modules\HelpDesk\Models\TicketSubcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TicketSubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bugCategory         = TicketCategory::where('slug', 'bug')->first();
        $improvementCategory = TicketCategory::where('slug', 'improvement')->first();
        $reportCategory      = TicketCategory::where('slug', 'report')->first();

        if (!$bugCategory || !$improvementCategory || !$reportCategory) {
            return;
        }

        $subcategories = [
            // BUG / ERROR
            [
                'ticket_category_id' => $bugCategory->id,
                'id'                 => (string) Str::uuid(),
                'name'               => 'App crash / cannot open',
                'slug'               => 'app-crash',
                'position'           => 1,
            ],
            [
                'ticket_category_id' => $bugCategory->id,
                'id'                 => (string) Str::uuid(),
                'name'               => 'Incorrect data / wrong values',
                'slug'               => 'bad-data',
                'position'           => 2,
            ],
            [
                'ticket_category_id' => $bugCategory->id,
                'id'                 => (string) Str::uuid(),
                'name'               => 'Save / submit error',
                'slug'               => 'cannot-save',
                'position'           => 3,
            ],

            // IMPROVEMENT / SUGGESTION
            [
                'ticket_category_id' => $improvementCategory->id,
                'id'                 => (string) Str::uuid(),
                'name'               => 'New feature request',
                'slug'               => 'feature-request',
                'position'           => 1,
            ],
            [
                'ticket_category_id' => $improvementCategory->id,
                'id'                 => (string) Str::uuid(),
                'name'               => 'Usability / workflow improvement',
                'slug'               => 'ux-improvement',
                'position'           => 2,
            ],
            [
                'ticket_category_id' => $improvementCategory->id,
                'id'                 => (string) Str::ulid(),
                'name'               => 'Visual / layout adjustment',
                'slug'               => 'ui-polish',
                'position'           => 3,
            ],

            // REPORT / ABUSE / VIOLATION
            [
                'ticket_category_id' => $reportCategory->id,
                'id'                 => (string) Str::ulid(),
                'name'               => 'Abusive language / harassment',
                'slug'               => 'abuse',
                'position'           => 1,
            ],
            [
                'ticket_category_id' => $reportCategory->id,
                'id'                 => (string) Str::ulid(),
                'name'               => 'Suspicious behavior / fraud',
                'slug'               => 'fraud',
                'position'           => 2,
            ],
            [
                'ticket_category_id' => $reportCategory->id,
                'id'                 => (string) Str::uuid(),
                'name'               => 'Policy / terms violation',
                'slug'               => 'policy-violation',
                'position'           => 3,
            ],
        ];

        foreach ($subcategories as $sub) {
            TicketSubcategory::query()
                ->updateOrCreate(
                    [
                        'slug' => $sub['slug'],
                        'ticket_category_id' => $sub['ticket_category_id'],
                    ],
                    $sub
                );
        }
    }
}
