<?php

use App\Modules\Contacts\Models\Contact;
use App\Modules\Users\Models\User;

it('does not list existing contacts as candidates', function () {
    $student = User::factory()->withStudentRole()->create();
    $alreadyContact = User::factory()->withPsychologistRole()->create([
        'name' => 'Candidate Example Already',
    ]);
    $availableContact = User::factory()->withPsychologistRole()->create([
        'name' => 'Candidate Example Available',
    ]);

    Contact::create([
        'owner_id' => $student->getKey(),
        'contact_id' => $alreadyContact->getKey(),
    ]);

    $response = $this->actingAs($student)
        ->getJson('/contacts/candidates?search=Candidate%20Example');

    $response->assertOk();

    $ids = collect($response->json('data'))->pluck('id');

    expect($ids)->not->toContain($alreadyContact->getKey());
    expect($ids)->toContain($availableContact->getKey());
});
