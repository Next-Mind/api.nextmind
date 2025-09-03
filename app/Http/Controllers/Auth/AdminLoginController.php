<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\InvalidAdminCredencialsException;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Auth\LoginFormRequest;

class AdminLoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginFormRequest $request)
    {
        $input = $request->validated();
        $user = User::whereEmail($input["email"])->first();
        if(!$user?->hasRole('admin')){
            throw new InvalidAdminCredencialsException();
        }

        /** @var \Illuminate\Http\Request $request */
        $platform = $request->attributes->get("client");

        // Deletar token antigo
        $user->tokens()->whereName($platform)->delete();

        // Emite novo token
        $token = $user->createToken($platform, ['*'],now()->addDay())->plainTextToken;

        return (new UserResource($user))->additional(compact('token'));
    }
}
