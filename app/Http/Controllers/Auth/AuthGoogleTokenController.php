<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Models\User;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Exceptions\ProviderUserNotFoundException;
use App\Http\Requests\Auth\AuthGoogleFormRequest;
use App\Http\Resources\UserResource;
use Exception;
use Illuminate\Auth\Events\Registered;

class AuthGoogleTokenController extends Controller
{
    /**
     * Método responsável por recuperar um usuário no Google através do seu ID Token
     * Esta função é utilizada principalmente pelo app mobile para emitir um
     * token de autenticação na api
     *
     * @param AuthGoogleFormRequest $request
     * @return UserResource
     */
    public function __invoke(AuthGoogleFormRequest $request)
    {
        
        try{
            $idToken = $request->validated('id_token');
            $googleUser = Socialite::driver('google')->userFromToken($idToken);

        }catch(Exception $e){
            throw new ProviderUserNotFoundException();
        }

        $user = User::firstOrCreate([
            'email' => $googleUser->getEmail(),
        ],[
            'name' => $googleUser->getName(),
            'photo_url' => $googleUser->getAvatar(),
        ]);

        $is_new_user = $user->wasRecentlyCreated;

        /** @var \Illuminate\Http\Request $request */
        $platform = $request->attributes->get("client");

        // Deletar token antigo
        $user->tokens()->whereName($platform)->delete();

        // Emite novo token
        $token = $user->createToken($platform, ['*'],now()->addDay())->plainTextToken;

        //Dispara E-Mail de bem-vindo caso seja um novo usuário
        if($is_new_user){
            UserRegistered::dispatch($user);
            event(new Registered($user));
        }

        return (new UserResource($user))->additional(compact(['is_new_user','token']));
    }
}
