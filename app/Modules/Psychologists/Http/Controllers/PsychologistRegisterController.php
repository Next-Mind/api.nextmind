<?php

namespace App\Modules\Psychologists\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Users\Models\User;
use App\Modules\Users\Http\Resources\UserResource;
use App\Modules\Psychologists\Http\Requests\PsychologistRegisterFormRequest;
use Illuminate\Support\Facades\Auth;

class PsychologistRegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PsychologistRegisterFormRequest $request)
    {
        //Pega os campos validados da request
        $inputUser = $request->safe()->only([
            'name',
            'email',
            'password',
            'birth_date',
            'cpf'
        ]);

        $inputAddress = $request->safe()->only('address');

        $inputPhone = $request->safe()->only('phone');

        $inputProfile = $request->safe()->only([
            'crp',
            'speciality',
            'bio'
        ]);

        //Cria o usuário no banco de dados
        $user = User::create($inputUser);

        $user->assignRole('psychologist');

        $user->addresses()->create($inputAddress['address']);
        $user->phones()->create($inputPhone['phone']);

        //Cria o perfil do usuário no banco de dados
        $user->psychologistProfile()->create($inputProfile);

        //Carrega o perfil para o front
        $user->load('psychologistProfile');

        $platform = $request->attributes->get('client', 'spa');

        if ($platform === 'spa') {
            //Faz o login para o SPA
            Auth::login($user);
            if ($request->hasSession() && $request->session()->isStarted()) {
                $request->session()->regenerate();
            }
        } else {
            // Garante que nenhuma sessão permaneça ativa para clientes stateless
            Auth::guard('web')->logout();

            if ($request->hasSession() && $request->session()->isStarted()) {
                $request->session()->invalidate();
            }
        }

        return new UserResource($user);
    }
}
