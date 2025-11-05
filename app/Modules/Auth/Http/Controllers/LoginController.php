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
        $input = $request->only('email', 'password');

        if (!Auth::attempt($input)) {
            throw new InvalidAuthenticationException();
        }

        $user = Auth::user();

        /** @var \Illuminate\Http\Request $request */
        $platform = $request->attributes->get('client');

        $this->auditLogger->record(
            $user,
            'users.logged_in',
            null,
            null,
            ['client' => $platform]
        );

        if ($platform === 'spa') {
            $request->session()->regenerate();
            return new UserResource($user);
        }

        // Deletar token antigo
        $user->tokens()->whereName($platform)->delete();

        //Emite novo token
        $token = $user->createToken($platform)->plainTextToken;

        return (new UserResource($user))->additional(compact('token'));
    }
}
