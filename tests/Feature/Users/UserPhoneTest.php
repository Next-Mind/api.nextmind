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
            ->getJson(route('users.phones.show', [$this->studentB, $this->studentBPhone]));

        $response->assertForbidden(); // 403
    });

    it('um admin pode acessar telefone de outro usuário', function () {
        $response = $this->actingAs($this->admin)
            ->getJson(route('users.phones.show', [$this->studentB, $this->studentBPhone]));

        $response->assertOk(); // 200
    });
});

describe('User Phone CRUD', function () {

    it('um estudante pode cadastrar seu próprio telefone', function () {
        $phoneData = UserPhone::factory()->make()->toArray();

        $response = $this->actingAs($this->studentA)
            ->postJson(route('users.phones.store', $this->studentA), $phoneData);

        $response->assertCreated();

        $this->assertDatabaseHas('user_phones', [
            'user_id' => $this->studentA->id,
            'number'  => $phoneData['number'],
        ]);
    });

    it('não permite cadastrar dois telefones iguais para o mesmo usuário', function () {
        $phone = UserPhone::factory()->for($this->studentA)->create();

        $response = $this->actingAs($this->studentA)
            ->postJson(route('users.phones.store', $this->studentA), $phone->toArray());

        $response->assertUnprocessable(); // 422
    });

    it('um estudante pode atualizar seu próprio telefone', function () {
        $phone = UserPhone::factory()->for($this->studentA)->create();

        $response = $this->actingAs($this->studentA)
            ->putJson(route('users.phones.update', [$this->studentA, $phone]), [
                'label'        => 'Novo Label',
                'country_code' => $phone->country_code,
                'area_code'    => $phone->area_code,
                'number'       => $phone->number,
                'is_whatsapp'  => $phone->is_whatsapp,
                'is_primary'   => $phone->is_primary,
            ]);

        $response->assertOk();

        $this->assertDatabaseHas('user_phones', [
            'id'    => $phone->id,
            'label' => 'Novo Label',
        ]);
    });

    it('um estudante não pode atualizar telefone de outro usuário', function () {
        $payload = [
            'label'        => 'Tentativa inválida',
            'country_code' => $this->studentBPhone->country_code,
            'area_code'    => $this->studentBPhone->area_code,
            'number'       => $this->studentBPhone->number,
            'is_whatsapp'  => $this->studentBPhone->is_whatsapp,
            'is_primary'   => $this->studentBPhone->is_primary,
        ];

        $response = $this->actingAs($this->studentA)
            ->putJson(route('users.phones.update', [$this->studentB, $this->studentBPhone]), $payload);

        $response->assertForbidden(); // 403
    });

    it('um admin pode atualizar telefone de outro usuário', function () {
        $payload = [
            'label'        => 'Atualizado pelo admin',
            'country_code' => $this->studentBPhone->country_code,
            'area_code'    => $this->studentBPhone->area_code,
            'number'       => $this->studentBPhone->number,
            'is_whatsapp'  => $this->studentBPhone->is_whatsapp,
            'is_primary'   => $this->studentBPhone->is_primary,
        ];

        $response = $this->actingAs($this->admin)
            ->putJson(route('users.phones.update', [$this->studentB, $this->studentBPhone]), $payload);

        $response->assertOk();

        $this->assertDatabaseHas('user_phones', [
            'id'    => $this->studentBPhone->id,
            'label' => 'Atualizado pelo admin',
        ]);
    });

    it('um estudante pode excluir seu próprio telefone', function () {
        $phone = UserPhone::factory()->for($this->studentA)->create();

        $response = $this->actingAs($this->studentA)
            ->deleteJson(route('users.phones.destroy', [$this->studentA, $phone]));

        // Se o controller retornar response()->noContent();
        $response->assertNoContent(); // 204

        $this->assertDatabaseMissing('user_phones', ['id' => $phone->id]);
    });

    it('um estudante não pode excluir telefone de outro usuário', function () {
        $response = $this->actingAs($this->studentA)
            ->deleteJson(route('users.phones.destroy', [$this->studentB, $this->studentBPhone]));

        $response->assertForbidden(); // 403
    });
});
