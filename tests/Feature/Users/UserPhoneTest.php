<?php

use App\Models\User;
use App\Models\Users\UserPhone;

beforeEach(function () {
    $this->studentA = User::factory()->withStudentRole()->create();
    $this->studentB = User::factory()->withStudentRole()->create();
    $this->admin    = User::factory()->withAdminRole()->create();

    $this->studentBPhone = UserPhone::factory()->for($this->studentB)->create();
});

describe('User Phone Authorization', function () {

    it('um estudante não pode acessar telefone de outro usuário', function () {
        $response = $this->actingAs($this->studentA)
            ->getJson(route('users.phone.show', $this->studentBPhone));

        $response->assertStatus(403);
    });

    it('um admin pode acessar telefone de outro usuário', function () {
        $response = $this->actingAs($this->admin)
            ->getJson(route('users.phone.show', $this->studentBPhone));

        $response->assertOk();
    });
});

describe('User Phone CRUD', function () {

    it('um estudante pode cadastrar seu próprio telefone', function () {
        $phoneData = UserPhone::factory()->make()->toArray();

        $response = $this->actingAs($this->studentA)
            ->postJson(route('users.phone'), $phoneData);

        $response->assertCreated();

        $this->assertDatabaseHas('user_phones', [
            'user_id' => $this->studentA->id,
            'number'  => $phoneData['number'],
        ]);
    });

    it('não permite cadastrar dois telefones iguais para o mesmo usuário', function () {
        $phone = UserPhone::factory()->for($this->studentA)->create();

        $response = $this->actingAs($this->studentA)
            ->postJson(route('users.phone'), $phone->toArray());

        $response->assertStatus(422);
    });

    it('um estudante pode atualizar seu próprio telefone', function () {
        $phone = UserPhone::factory()->for($this->studentA)->create();

        $response = $this->actingAs($this->studentA)
            ->putJson(route('users.phone.update', $phone), [
                'label' => 'Novo Label',
                'country_code' => $phone->country_code,
                'area_code' => $phone->area_code,
                'number' => $phone->number,
                'is_whatsapp' => $phone->is_whatsapp,
                'is_primary' => $phone->is_primary,
            ]);

        $response->assertOk();

        $this->assertDatabaseHas('user_phones', [
            'id'    => $phone->id,
            'label' => 'Novo Label',
        ]);
    });

    it('um estudante não pode atualizar telefone de outro usuário', function () {
        $response = $this->actingAs($this->studentA)
            ->putJson(route('users.phone.update', $this->studentBPhone), [
                'label' => 'Tentativa inválida',
                'country_code' => $this->studentBPhone->country_code,
                'area_code' => $this->studentBPhone->area_code,
                'number' => $this->studentBPhone->number,
                'is_whatsapp' => $this->studentBPhone->is_whatsapp,
                'is_primary' => $this->studentBPhone->is_primary,
            ]);

        $response->assertStatus(403);
    });

    it('um admin pode atualizar telefone de outro usuário', function () {
        $response = $this->actingAs($this->admin)
            ->putJson(route('users.phone.update', $this->studentBPhone), [
                'label' => 'Atualizado pelo admin',
                'country_code' => $this->studentBPhone->country_code,
                'area_code' => $this->studentBPhone->area_code,
                'number' => $this->studentBPhone->number,
                'is_whatsapp' => $this->studentBPhone->is_whatsapp,
                'is_primary' => $this->studentBPhone->is_primary,
            ]);

        $response->assertOk();

        $this->assertDatabaseHas('user_phones', [
            'id'    => $this->studentBPhone->id,
            'label' => 'Atualizado pelo admin',
        ]);
    });

    it('um estudante pode excluir seu próprio telefone', function () {
        $phone = UserPhone::factory()->for($this->studentA)->create();

        $response = $this->actingAs($this->studentA)
            ->deleteJson(route('users.phone.destroy', $phone));

        $response->assertOk();

        $this->assertDatabaseMissing('user_phones', ['id' => $phone->id]);
    });

    it('um estudante não pode excluir telefone de outro usuário', function () {
        $response = $this->actingAs($this->studentA)
            ->deleteJson(route('users.phone.destroy', $this->studentBPhone));

        $response->assertStatus(403);
    });
});