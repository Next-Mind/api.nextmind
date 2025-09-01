<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use App\Models\Users\UserAddress;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use App\Http\Resources\UserAddressResource;
use App\Http\Requests\Users\StoreUserAddressFormRequest;
use App\Exceptions\UserAddressAlreadyRegisteredException;
use App\Http\Requests\Users\UpdateUserAddressFormRequest;

class UserAddressController extends Controller
{
    public function show(User $user,UserAddress $address){
        Gate::authorize("view", $address);
        return new UserAddressResource($address);
    }

    public function store(User $user,StoreUserAddressFormRequest $request)
    {
        Gate::authorize("create", $user);

        $input = $request->validated();
        try {
            $address = $user->addresses()->create($input);
        } catch(QueryException $e){
            throw new UserAddressAlreadyRegisteredException();
        }

        return new UserAddressResource($address);
    }

    public function update(User $user, UserAddress $address, UpdateUserAddressFormRequest $request)
    {
        Gate::authorize("update", $address);
        $input = $request->validated();
        $address->update($input);
        return new UserAddressResource($address);
    }

    public function destroy(User $user, UserAddress $address)
    {
        Gate::authorize("delete",$address);
        $address->delete();
        return response()->noContent();
    }
}
