<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Audits\Services\AuditLogger;
use App\Modules\Users\Http\Resources\UserResource;
use App\Modules\Auth\Http\Requests\LoginFormRequest;
use App\Modules\Auth\Exceptions\InvalidAuthenticationException;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(private readonly AuditLogger $auditLogger)
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginFormRequest $request)
    {
        $credentials = $request->only('email', 'password');

        /** @var \Illuminate\Http\Request $request */
        $platform = $request->attributes->get('client', 'spa');

        if ($platform === 'spa') {
            if (!Auth::attempt($credentials)) {
                throw new InvalidAuthenticationException();
            }

            $user = Auth::user();
            $request->session()->regenerate();
        } else {
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
        }

        $this->auditLogger->record(
            $user,
            'users.logged_in',
            null,
            null,
            ['client' => $platform]
        );

        if ($platform === 'spa') {
            return new UserResource($user);
        }

        // Deletar token antigo
        $user->tokens()->whereName($platform)->delete();

        //Emite novo token
        $token = $user->createToken($platform)->plainTextToken;

        return (new UserResource($user))->additional(compact('token'));
    }
}
