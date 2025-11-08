<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\Users\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_spa_login_uses_session_authentication(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('Password123'),
        ]);

        $response = $this->withHeader('X-Client', 'spa')->postJson('/login', [
            'email' => $user->email,
            'password' => 'Password123',
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.id', $user->id);
        $response->assertJsonMissingPath('token');
        $response->assertCookie(config('session.cookie'));
    }

    public function test_mobile_login_is_stateless_and_returns_token(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('Password123'),
        ]);

        $response = $this->withHeader('X-Client', 'mobile')->postJson('/login', [
            'email' => $user->email,
            'password' => 'Password123',
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.id', $user->id);
        $response->assertJsonPath('token', fn ($token) => is_string($token) && $token !== '');
        $response->assertCookieMissing(config('session.cookie'));

        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => 'mobile',
            'tokenable_id' => $user->id,
        ]);
    }

    public function test_desktop_login_is_stateless_and_returns_token(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('Password123'),
        ]);

        $response = $this->withHeader('X-Client', 'desktop')->postJson('/login', [
            'email' => $user->email,
            'password' => 'Password123',
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.id', $user->id);
        $response->assertJsonPath('token', fn ($token) => is_string($token) && $token !== '');
        $response->assertCookieMissing(config('session.cookie'));

        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => 'desktop',
            'tokenable_id' => $user->id,
        ]);
    }
}
