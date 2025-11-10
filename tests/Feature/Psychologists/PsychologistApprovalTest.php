<?php

use App\Modules\Psychologists\Models\PsychologistDocument;
use App\Modules\Psychologists\Models\PsychologistProfile;
use App\Modules\Users\Models\User;

it('marks the psychologist profile as rejected when all documents are rejected', function () {
    $admin = User::factory()->withAdminRole()->create();
    $psychologist = User::factory()->withPsychologistRole()->create();

    $profile = PsychologistProfile::factory()
        ->for($psychologist, 'user')
        ->create([
            'status' => 'pending',
        ]);

    PsychologistDocument::factory()
        ->forProfile($profile)
        ->type('crp_card')
        ->create([
            'status' => 'rejected',
            'reviewed_by' => $admin->getKey(),
            'reviewed_at' => now()->subDay(),
            'rejection_reason' => 'Documento anterior inválido.',
        ]);

    $document = PsychologistDocument::factory()
        ->forProfile($profile)
        ->type('id_front')
        ->create([
            'status' => 'pending',
        ]);

    $response = $this->actingAs($admin, 'sanctum')
        ->patchJson("/admin/psychologists/documents/{$document->getKey()}/repprove", [
            'rejection_reason' => 'Documento ilegível, por favor envie novamente.',
        ]);

    $response->assertOk();

    $document->refresh();
    expect($document->status)->toBe('rejected');
    expect($document->reviewed_by)->toBe($admin->getKey());

    $profile->refresh();
    expect($profile->status)->toBe('rejected');
    expect($profile->rejection_reason)->toBe('Documento ilegível, por favor envie novamente.');
    expect($profile->rejected_at)->not->toBeNull();
    expect($profile->approved_at)->toBeNull();
    expect($profile->approved_by)->toBeNull();
});

it('can disapprove multiple documents for the same psychologist at once', function () {
    $admin = User::factory()->withAdminRole()->create();
    $psychologist = User::factory()->withPsychologistRole()->create();

    $profile = PsychologistProfile::factory()
        ->for($psychologist, 'user')
        ->approved($admin)
        ->create();

    $documentOne = PsychologistDocument::factory()
        ->forProfile($profile)
        ->type('crp_card')
        ->create([
            'status' => 'pending',
        ]);

    $documentTwo = PsychologistDocument::factory()
        ->forProfile($profile)
        ->type('id_front')
        ->create([
            'status' => 'pending',
        ]);

    PsychologistDocument::factory()
        ->forProfile($profile)
        ->type('id_back')
        ->create([
            'status' => 'approved',
            'reviewed_by' => $admin->getKey(),
            'reviewed_at' => now()->subDay(),
        ]);

    $response = $this->actingAs($admin, 'sanctum')
        ->patchJson('/admin/psychologists/documents/repprove', [
            'documents' => [
                $documentOne->getKey(),
                $documentTwo->getKey(),
            ],
            'rejection_reason' => 'Documentos inconsistentes.',
        ]);

    $response->assertOk();
    $response->assertJsonCount(2, 'data');

    $documentOne->refresh();
    $documentTwo->refresh();

    expect($documentOne->status)->toBe('rejected');
    expect($documentTwo->status)->toBe('rejected');
    expect($documentOne->reviewed_by)->toBe($admin->getKey());
    expect($documentTwo->reviewed_by)->toBe($admin->getKey());

    $profile->refresh();
    expect($profile->status)->toBe('pending');
    expect($profile->approved_at)->toBeNull();
    expect($profile->approved_by)->toBeNull();
});
