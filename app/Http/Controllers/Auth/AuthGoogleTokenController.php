<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Auth\Events\Registered;
use App\Http\Resources\AuthUserResource;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Exceptions\ProviderUserNotFoundException;
use App\Http\Requests\Auth\AuthGoogleFormRequest;
use Kreait\Firebase\Auth as FirebaseAuth;

class AuthGoogleTokenController extends Controller
{
    /**
     * Método responsável por recuperar um usuário no Google através do seu ID Token
     * Esta função é utilizada principalmente pelo app mobile para emitir um
     * token de autenticação na api
     *
     * @param AuthGoogleFormRequest $request
     */
    public function __invoke(AuthGoogleFormRequest $request)
    {
        
        try{
            $auth = app('firebase.auth');
            $idToken = $request->validated('id_token');

            $verified = $auth->verifyIdToken($idToken);
            $claims = $verified->claims();

            $email = $claims->get('email');
            $name   = $claims->get('name') ?? trim(($claims->get('given_name').' '.$claims->get('family_name')) ?: '');
            $photo  = $claims->get('picture');
            $emailVerified = (bool) $claims->get('email_verified');

        }catch(Exception $e){
            throw new ProviderUserNotFoundException();
        }

        $user = User::firstOrCreate([
            'email' => $email,
        ],[
            'name' => $name,
            'photo_url' => $photo,
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
            $user->assignRole('student');
        }

        // return (new AuthUserResource($user))->additional(compact(['is_new_user','token']));

        JsonResource::withoutWrapping();
        
        return response()->json([
        ...UserResource::make($user)->resolve(),
        'is_new_user' => $is_new_user,
        'token' => $token
    ]);
    }
}
