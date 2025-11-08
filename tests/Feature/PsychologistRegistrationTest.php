<?php

use Illuminate\Session\SessionManager;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie;

beforeEach(function () {
    app()->forgetInstance('session.store');
    app()->forgetInstance(SessionManager::class);
});

function validPsychologistPayload(array $override = []): array
{
    return array_replace_recursive([
        'name' => 'Dr. '.Str::random(8),
        'email' => Str::random(8).'@example.com',
        'password' => 'Password123',
        'birth_date' => '1990-01-01',
        'cpf' => generateValidCpf(),
        'crp' => 'CRP'.Str::upper(Str::random(6)),
        'speciality' => 'Clinical Psychology',
        'bio' => 'Experienced professional focused on human behavior.',
        'address' => [
            'label' => 'Office',
            'postal_code' => '12345-678',
            'street' => 'Main Street',
            'number' => '123',
            'complement' => 'Suite 1',
            'neighborhood' => 'Downtown',
            'city' => 'Sample City',
            'state' => 'SP',
            'country' => 'BR',
            'is_primary' => true,
        ],
        'phone' => [
            'label' => 'Mobile',
            'country_code' => '55',
            'area_code' => '11',
            'number' => (string) random_int(100000000, 999999999),
            'is_whatsapp' => true,
            'is_primary' => true,
        ],
    ], $override);
}

function generateValidCpf(): string
{
    $digits = [];

    for ($i = 0; $i < 9; $i++) {
        $digits[] = random_int(0, 9);
    }

    $digits[] = calculateCpfDigit($digits, 10);
    $digits[] = calculateCpfDigit($digits, 11);

    return implode('', $digits);
}

function calculateCpfDigit(array $digits, int $weight): int
{
    $sum = 0;

    foreach ($digits as $index => $digit) {
        $sum += $digit * ($weight - $index);
    }

    $remainder = $sum % 11;

    return $remainder < 2 ? 0 : 11 - $remainder;
}

function collectSessionCookies($response)
{
    $sessionCookieName = config('session.cookie');

    return collect($response->headers->getCookies())
        ->filter(fn (Cookie $cookie) => $cookie->getName() === $sessionCookieName);
}

it('starts a session for spa clients during psychologist registration', function () {
    $response = $this
        ->withHeader('X-Client', 'spa')
        ->postJson('/register/psychologist', validPsychologistPayload());

    $response->assertSuccessful();

    $this->assertAuthenticated();
    expect(app('session.store')->isStarted())->toBeTrue();
    expect(collectSessionCookies($response))->not->toBeEmpty();
});

it('keeps stateless clients free of sessions during psychologist registration', function () {
    $response = $this
        ->withHeader('X-Client', 'mobile')
        ->postJson('/register/psychologist', validPsychologistPayload([
            'email' => Str::random(8).'@example.net',
            'crp' => 'CRP'.Str::upper(Str::random(6)),
        ]));

    $response->assertSuccessful();

    $this->assertGuest();
    expect(app('session.store')->isStarted())->toBeFalse();
    expect(collectSessionCookies($response))->toBeEmpty();
});
