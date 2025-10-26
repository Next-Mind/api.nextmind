<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Modules\Users\Models\User;
use App\Modules\Users\Events\UserRegistered;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Request as FacadeRequest;
use Request;

class AuthGoogleWebClientController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback(Request $request)
    {
        $google = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate(
            ['email' => $google->getEmail()],
            [
                'name'      => $google->getName(),
                'photo_url' => $google->getAvatar(),
            ]
        );

        // Se o Google confirmou o e-mail, considere verificar localmente
        if (($google->user['email_verified'] ?? false) && $user->email_verified_at === null) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        $is_new_user = $user->wasRecentlyCreated;
        UserRegistered::dispatchIf($is_new_user, $user);

        // Login por sessão (Sanctum SPA)
        Auth::login($user, remember: true);
        FacadeRequest::session()->regenerate();

        // Redirecione para o SPA
        $front = rtrim(config('app.frontend_url'), '/');
        return redirect()->to($front . '/auth/success');
    }
}
