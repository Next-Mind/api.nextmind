<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\PsychologistRegisterFormRequest;

class PsychologistRegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PsychologistRegisterFormRequest $request)
    {
        //Pega os campos validados da request
        $inputUser = $request->safe()->only([
            'name','email','password','birth_date','cpf'
        ]);

        $inputProfile = $request->safe()->only([
            'crp','speciality','bio'
        ]);

        //Cria o usuário no banco de dados
        $user = User::create($inputUser);

        $user->assignRole('psychologist');

        //Cria o perfil do usuário no banco de dados
        $user->psychologistProfile()->create($inputProfile);

        //Carrega o perfil para o front
        $user->load('psychologistProfile');

        //Faz o login para o SPA
        Auth::login($user);
        $request->session()->regenerate();

        return new UserResource($user);
    }
}
