<?php

use App\Modules\Users\Models\User;
use App\Modules\Users\Models\UserPhone;

beforeEach(function () {
    $this->student = User::factory()->withStudentRole()->create();
    $this->admin = User::factory()->withAdminRole()->create();

    UserPhone::factory()->for($this->student)->state(['is_primary' => true])->create([
        'country_code' => '55',
        'area_code' => '11',
        'number' => '999999999',
    ]);
});

describe('User basic data endpoint', function () {
    it('permite que o usuário visualize seus próprios dados básicos', function () {
        $response = $this->actingAs($this->student)
            ->getJson(route('users.basic', $this->student));

        $response->assertOk();
        $response->assertJsonPath('data.name', $this->student->name);
        $response->assertJsonPath('data.email', $this->student->email);
        $response->assertJsonPath('data.role', 'student');
        $response->assertJsonPath('data.primary_phone.country_code', '55');
        $response->assertJsonPath('data.primary_phone.area_code', '11');
        $response->assertJsonPath('data.primary_phone.number', '999999999');
    });

    it('impede que um usuário sem permissão visualize dados básicos de outro usuário', function () {
        $otherUser = User::factory()->withStudentRole()->create();

        $response = $this->actingAs($this->student)
            ->getJson(route('users.basic', $otherUser));

        $response->assertForbidden();
    });

    it('permite que um admin visualize dados básicos de outro usuário', function () {
        $otherUser = User::factory()->withStudentRole()->create();

        $response = $this->actingAs($this->admin)
            ->getJson(route('users.basic', $otherUser));

        $response->assertOk();
        $response->assertJsonPath('data.name', $otherUser->name);
    });
});
