<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginFormRequest;
use App\Exceptions\InvalidAuthenticationException;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginFormRequest $request)
    {
        $input = $request->only('email','password');

        if(!Auth::attempt($input)){
            throw new InvalidAuthenticationException();
        }

        $user = Auth::user();

        /** @var \Illuminate\Http\Request $request */
        $platform = $request->attributes->get('client');

        if($platform === 'spa') {
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
