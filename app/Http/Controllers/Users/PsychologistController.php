<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class PsychologistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = User::query()
        ->whereHas('psychologistProfile', function ($q) {
            $q->whereNotNull('approved_at');
        })
        ->with(['psychologistProfile' => function ($q) {
            $q->whereNotNull('approved_at');
        }]);

        $psychos = $q->paginate(15);

        return UserResource::collection($psychos);
    }

    /**
     * Display the specified resource.
     */
     public function show(string $uuid)
    {
        $user = User::query()
            ->where('id', $uuid)
            ->whereHas('psychologistProfile')
            ->with('psychologistProfile')
            ->firstOrFail();

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
}
