<?php

use App\Modules\Users\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->withAdminRole()->create();
});

describe('User index endpoint', function () {
    it('permite que um admin liste usuários com paginação simples', function () {
        User::factory()->count(5)->withStudentRole()->create();

        $response = $this->actingAs($this->admin)
            ->getJson(route('users.index', ['per_page' => 2]));

        $response->assertOk();
        $response->assertJsonCount(2, 'data');

        $payload = $response->json();

        expect($payload)
            ->toHaveKey('meta.current_page', 1)
            ->toHaveKey('meta.per_page', 2)
            ->toHaveKey('links.next');

        expect($payload['links']['next'])->not()->toBeNull();

        $userData = collect($payload['data'])->first();

        expect($userData)
            ->toHaveKeys(['id', 'name', 'email', 'photo_url', 'role'])
            ->and($userData['primary_phone'])
            ->toBeNull();
    });

    it('impede que usuários sem permissão listem outros usuários', function () {
        $student = User::factory()->withStudentRole()->create();

        $response = $this->actingAs($student)
            ->getJson(route('users.index'));

        $response->assertForbidden();
    });
});
