<?php

namespace App\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Users\Http\Resources\UserBasicResource;
use App\Modules\Users\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $actor = $request->user();

        abort_unless($actor->can('profiles.view.any'), 403);

        $perPage = $request->integer('per_page') ?? 15;
        $perPage = max(1, min($perPage, 100));

        $users = User::query()
            ->with('primaryPhone')
            ->simplePaginate($perPage);

        return UserBasicResource::collection($users);
    }

    public function showBasic(Request $request, User $user)
    {
        $actor = $request->user();

        abort_unless(
            $actor->can('profiles.view.any') || ($actor->can('profiles.view.self') && $actor->is($user)),
            403
        );

        $user->loadMissing('primaryPhone');

        return new UserBasicResource($user);
    }
}
