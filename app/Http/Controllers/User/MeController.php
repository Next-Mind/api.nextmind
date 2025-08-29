<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{
    public function show()
    {
        return new UserResource(Auth::user());
    }
}
