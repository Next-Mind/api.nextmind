<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use App\Models\Users\UserPhone;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use App\Http\Resources\UserPhoneResource;
use App\Http\Requests\Users\UpdateUserPhoneRequest;
use App\Http\Requests\Users\StoreUserPhoneFormRequest;
use App\Exceptions\UserPhoneAlreadyRegisteredException;

class UserPhoneController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(User $user,StoreUserPhoneFormRequest $request)
    {;
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

    public function index(User $user)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        Gate::authorize('viewAny', [UserPhone::class, $user]);
        $user->load('phones');
        return UserPhoneResource::collection($user->phones);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, UserPhone $phone)
    {
        Gate::authorize('view',$phone);
        return new UserPhoneResource($phone);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, UserPhone $phone, UpdateUserPhoneRequest $request)
    {
        Gate::authorize("update",$phone);
        $input = $request->validated();
        $phone->update($input);
        return new UserPhoneResource($phone);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, UserPhone $phone)
    {
        Gate::authorize("delete",$phone);
        $phone->delete();
        return response()->noContent();
    }
}
