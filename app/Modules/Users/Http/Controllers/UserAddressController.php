<?php

namespace App\Modules\Users\Http\Controllers;

use App\Modules\Users\Models\User;
use App\Modules\Users\Models\UserAddress;
use App\Http\Controllers\Controller;
use App\Modules\Users\Http\Resources\UserAddressResource;
use App\Modules\Users\Http\Requests\StoreUserAddressFormRequest;
use App\Modules\Users\Exceptions\UserAddressAlreadyRegisteredException;
use App\Modules\Users\Http\Requests\UpdateUserAddressFormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;

class UserAddressController extends Controller
{

    public function index(User $user)
    {
        Gate::authorize('viewAny', [UserAddress::class, $user]);
        $user->load('addresses');
        return UserAddressResource::collection($user->addresses);
    }

    public function show(User $user, UserAddress $address)
    {
        Gate::authorize("view", $address);
        return new UserAddressResource($address);
    }

    public function store(User $user, StoreUserAddressFormRequest $request)
    {
        Gate::authorize("create", $user);

        $input = $request->validated();
        try {
            $address = $user->addresses()->create($input);
        } catch (QueryException $e) {
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
        Gate::authorize("delete", $address);
        $address->delete();
        return response()->noContent();
    }
}
