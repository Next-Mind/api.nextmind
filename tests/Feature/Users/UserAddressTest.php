<?php

use App\Modules\Users\Models\User;
use App\Modules\Users\Models\User;s\UserAddress;

beforeEach(function () {
    // Usuários com roles
    $this->studentA = User::factory()->withStudentRole()->create();
    $this->studentB = User::factory()->withStudentRole()->create();
    $this->admin    = User::factory()->withAdminRole()->create();

    // Endereço do estudante B (para testes de acesso)
    $this->studentBAddress = UserAddress::factory()->for($this->studentB)->create();
});

/**
 * Helpers
 */
function validAddressPayload(?UserAddress $base = null): array
{
    $addr = $base ?? UserAddress::factory()->make();

    return [
        'label'        => $addr->label,
        'postal_code'  => $addr->postal_code,
        'street'       => $addr->street,
        'complement'   => $addr->complement,
        'neighborhood' => $addr->neighborhood,
        'city'         => $addr->city,
        'number'       => $addr->number,
        'state'        => $addr->state,
        'country'      => $addr->country,
        'is_primary'   => $addr->is_primary
    ];
}

describe('User Address Authorization', function () {
    it('um estudante não pode ver endereço de outro usuário', function () {
        $response = $this->actingAs($this->studentA)
            ->getJson(route('users.addresses.show', [$this->studentB, $this->studentBAddress]));

        $response->assertForbidden(); // 403
    });

    it('um admin pode ver endereço de outro usuário', function () {
        $response = $this->actingAs($this->admin)
            ->getJson(route('users.addresses.show', [$this->studentB, $this->studentBAddress]));

        $response->assertOk(); // 200
    });
});

describe('User Address CRUD', function () {
    it('um estudante pode cadastrar seu próprio endereço', function () {
        $payload = validAddressPayload();

        $response = $this->actingAs($this->studentA)
            ->postJson(route('users.addresses.store', $this->studentA), $payload);

        $response->assertCreated(); // 201

        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $this->studentA->id,
            'street'   => $payload['street'],
            'city'    => $payload['city'],
        ]);
    });

    it('não permite cadastro sem campos obrigatórios', function () {
        $response = $this->actingAs($this->studentA)
            ->postJson(route('users.addresses.store', $this->studentA), [
                'label' => 'Casa',
            ]);

        $response->assertUnprocessable(); // 422
    });

    it('um estudante pode atualizar seu próprio endereço', function () {
        $address = UserAddress::factory()->for($this->studentA)->create();

        $payload = validAddressPayload($address);
        $payload['label'] = 'Novo Label';

        $response = $this->actingAs($this->studentA)
            ->putJson(route('users.addresses.update', [$this->studentA, $address]), $payload);

        $response->assertOk();

        $this->assertDatabaseHas('user_addresses', [
            'id'    => $address->id,
            'label' => 'Novo Label',
        ]);
    });

    it('um estudante não pode atualizar endereço de outro usuário', function () {
        // Enviar payload completo para passar da validação e cair na Policy
        $payload = validAddressPayload($this->studentBAddress);
        $payload['label'] = 'Tentativa inválida';

        $response = $this->actingAs($this->studentA)
            ->putJson(route('users.addresses.update', [$this->studentB, $this->studentBAddress]), $payload);

        $response->assertForbidden();
    });

    it('um admin pode atualizar endereço de outro usuário', function () {
        $payload = validAddressPayload($this->studentBAddress);
        $payload['label'] = 'Atualizado pelo admin';

        $response = $this->actingAs($this->admin)
            ->putJson(route('users.addresses.update', [$this->studentB, $this->studentBAddress]), $payload);

        $response->assertOk();

        $this->assertDatabaseHas('user_addresses', [
            'id'    => $this->studentBAddress->id,
            'label' => 'Atualizado pelo admin',
        ]);
    });

    it('um estudante pode excluir seu próprio endereço', function () {
        $address = UserAddress::factory()->for($this->studentA)->create();

        $response = $this->actingAs($this->studentA)
            ->deleteJson(route('users.addresses.destroy', [$this->studentA, $address]));

        $response->assertNoContent(); // 204
        $this->assertDatabaseMissing('user_addresses', ['id' => $address->id]);
    });

    it('um estudante não pode excluir endereço de outro usuário', function () {
        $response = $this->actingAs($this->studentA)
            ->deleteJson(route('users.addresses.destroy', [$this->studentB, $this->studentBAddress]));

        $response->assertForbidden();
    });
});
