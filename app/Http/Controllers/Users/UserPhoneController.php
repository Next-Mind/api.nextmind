<?php

namespace App\Http\Controllers\Users;

use App\Exceptions\UserPhoneAlreadyRegisteredException;
use App\Http\Resources\UserPhoneResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UpdateUserPhoneRequest;
use App\Http\Requests\Users\StoreUserPhoneFormRequest;
use App\Models\Users\UserPhone;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserPhoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserPhoneFormRequest $request)
    {
        //RECUPERANDO O USUÁRIO AUTENTICADO E VERIFICANDO SE TEM PERMISSÃO PARA CRIAR UM NOVO REGISTRO
        $user = Auth::user();
        Gate::authorize('create',$user);

        //RECUPERANDO CAMPOS VALIDADOS DA REQUISIÇÃO
        $input = $request->validated();

        //VERIFICANDO SE JÁ EXISTE UM REGISTRO IGUAL NO BANCO
        $exists = UserPhone::where([
            'country_code' => $input['country_code'],
            'area_code' => $input['area_code'],
            'number' => $input['number'],
        ])->exists();

        if($exists){
            throw new UserPhoneAlreadyRegisteredException();
        }

        //INICIANDO BLOCO DE CADASTRO DO NOVO TELEFONE
        try{
            $phone = $user->phones()->create($input);
            return new UserPhoneResource($phone);
        }catch(QueryException $e){
            throw new UserPhoneAlreadyRegisteredException();
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(UserPhone $userPhone)
    {
        Gate::authorize('view',$userPhone);
        return new UserPhoneResource($userPhone);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserPhoneRequest $request, UserPhone $userPhone)
    {
        Gate::authorize("update",$userPhone);
        $input = $request->validated();
        $userPhone->update($input);
        return new UserPhoneResource($userPhone);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserPhone $userPhone)
    {
        Gate::authorize("delete",$userPhone);
        $userPhone->delete();
    }
}
