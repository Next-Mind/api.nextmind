<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ProviderUserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthGoogleFormRequest;
use Laravel\Socialite\Facades\Socialite;

class AuthGoogleTokenController extends Controller
{
    public function __invoke(AuthGoogleFormRequest $request)
    {
        $idToken = $request->validated('id_token');
        $googleUser = Socialite::driver('google')->userFromToken($idToken);
        if(!$googleUser){
            throw new ProviderUserNotFoundException();
        }
        dd($googleUser);
    }
}
