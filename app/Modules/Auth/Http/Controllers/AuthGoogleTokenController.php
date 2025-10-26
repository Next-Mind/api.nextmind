<?php

namespace App\Modules\Auth\Http\Controllers;

use Exception;
use App\Modules\Users\Models\User;
use App\Modules\Users\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Modules\Users\Http\Resources\UserResource;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Auth\Http\Requests\AuthGoogleFormRequest;

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

        try {
            $auth = app('firebase.auth');
            $idToken = $request->validated('id_token');

            $verified = $auth->verifyIdToken($idToken);
            $claims = $verified->claims();

            $email = $claims->get('email');
            $name   = $claims->get('name') ?? trim(($claims->get('given_name') . ' ' . $claims->get('family_name')) ?: '');
            $photo  = $claims->get('picture');
            $emailVerified = (bool) $claims->get('email_verified');
        } catch (Exception $e) {
            throw $e;
        }

        $user = User::firstOrCreate([
            'email' => $email,
        ], [
            'name' => $name,
            'photo_url' => $photo,
        ]);

        $is_new_user = $user->wasRecentlyCreated;

        /** @var \Illuminate\Http\Request $request */
        $platform = $request->attributes->get("client");

        // Deletar token antigo
        $user->tokens()->whereName($platform)->delete();

        // Emite novo token
        $token = $user->createToken($platform, ['*'], now()->addDay())->plainTextToken;

        //Dispara E-Mail de bem-vindo caso seja um novo usuário
        if ($is_new_user) {
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
