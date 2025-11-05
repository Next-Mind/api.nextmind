<?php

use App\Modules\Audits\Models\Audit;
use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;

it('returns audit history for morph alias types', function () {
    $actor = User::factory()->withAdminRole()->create();
    $auditable = User::factory()->withStudentRole()->create();

    $originalMorphMap = Relation::morphMap() ?? [];
    Relation::morphMap(array_merge($originalMorphMap, [
        'custom-user' => User::class,
    ]), true);

    try {
        $audit = Audit::create([
            'event' => 'updated',
            'auditable_type' => 'custom-user',
            'auditable_id' => $auditable->getKey(),
            'user_type' => User::class,
            'user_id' => $actor->getKey(),
            'old_values' => ['name' => 'Old Name'],
            'new_values' => ['name' => 'New Name'],
            'extra' => ['note' => 'alias audit'],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Pest',
        ]);

        $response = $this->actingAs($actor, 'sanctum')
            ->getJson(route('audits.history', [
                'type' => 'custom-user',
                'id' => $auditable->getKey(),
            ]));

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $audit->getKey());
        $response->assertJsonPath('data.0.auditable_type', 'custom-user');
    } finally {
        Relation::morphMap($originalMorphMap, false);
    }
});
