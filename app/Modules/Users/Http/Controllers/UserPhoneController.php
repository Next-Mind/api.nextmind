<?php

namespace App\Modules\Users\Http\Controllers;

use App\Modules\Users\Models\User;
use App\Modules\Users\Models\UserPhone;
use App\Http\Controllers\Controller;
use App\Modules\Users\Http\Resources\UserPhoneResource;
use App\Modules\Users\Http\Requests\UpdateUserPhoneRequest;
use App\Modules\Users\Http\Requests\StoreUserPhoneFormRequest;
use App\Modules\Users\Exceptions\UserPhoneAlreadyRegisteredException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;

class UserPhoneController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(User $user, StoreUserPhoneFormRequest $request)
    {;
        Gate::authorize('create', $user);

        //RECUPERANDO CAMPOS VALIDADOS DA REQUISIÇÃO
        $input = $request->validated();

        $existing = UserPhone::withTrashed()->where([
            'country_code' => $input['country_code'],
            'area_code' => $input['area_code'],
            'number' => $input['number'],
        ])->first();

        if ($existing) {
            if (! $existing->trashed()) {
                throw new UserPhoneAlreadyRegisteredException();
            }

            $existing->forceFill(array_merge($input, [
                'user_id' => $user->getKey(),
            ]));

            $existing->restore();
            $existing->save();

            return new UserPhoneResource($existing->refresh());
        }

        //INICIANDO BLOCO DE CADASTRO DO NOVO TELEFONE
        try {
            $phone = $user->phones()->create($input);
            return new UserPhoneResource($phone);
        } catch (QueryException $e) {
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
        Gate::authorize('view', $phone);
        return new UserPhoneResource($phone);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, UserPhone $phone, UpdateUserPhoneRequest $request)
    {
        Gate::authorize("update", $phone);
        $input = $request->validated();
        $phone->update($input);
        return new UserPhoneResource($phone);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, UserPhone $phone)
    {
        Gate::authorize("delete", $phone);
        $phone->delete();
        return response()->noContent();
    }
}
