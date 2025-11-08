<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Audits\Services\AuditLogger;
use App\Modules\Users\Http\Resources\UserResource;
use App\Modules\Users\Models\User;
use App\Modules\Auth\Http\Requests\LoginFormRequest;
use App\Modules\Auth\Exceptions\InvalidAuthenticationException;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(private readonly AuditLogger $auditLogger)
    {
    }

    public function loginStateless(LoginFormRequest $request)
    {
        $credentials = $request->only('email', 'password');

        /** @var \Illuminate\Http\Request $request */
        $platform = $request->attributes->get('client', 'spa');

        if ($platform === 'spa') {
            throw new InvalidAuthenticationException();
        }

        $guard = Auth::guard('web');

        if (!$guard->once($credentials)) {
            throw new InvalidAuthenticationException();
        }

        $user = $guard->user();

        // Garantir que nenhuma sessão fique ativa para clientes stateless
        $guard->logout();

        if ($request->hasSession() && $request->session()->isStarted()) {
            $request->session()->invalidate();
        }

        return $this->respondStateless($user, $platform);
    }

    public function loginStateful(LoginFormRequest $request)
    {
        $credentials = $request->only('email', 'password');

        /** @var \Illuminate\Http\Request $request */
        $platform = $request->attributes->get('client', 'spa');

        if ($platform !== 'spa') {
            throw new InvalidAuthenticationException();
        }

        if (!Auth::attempt($credentials)) {
            throw new InvalidAuthenticationException();
        }

        $user = Auth::user();
        $request->session()->regenerate();

        return $this->respondStateful($user, $platform);
    }

    private function respondStateful(User $user, string $platform): UserResource
    {
        $this->recordLogin($user, $platform);

        return new UserResource($user);
    }

    private function respondStateless(User $user, string $platform): UserResource
    {
        $this->recordLogin($user, $platform);

        // Deletar token antigo
        $user->tokens()->whereName($platform)->delete();

        //Emite novo token
        $token = $user->createToken($platform)->plainTextToken;

        return (new UserResource($user))->additional(compact('token'));
    }

    private function recordLogin(User $user, string $platform): void
    {
        $this->auditLogger->record(
            $user,
            'users.logged_in',
            null,
            null,
            ['client' => $platform]
        );
    }
}
