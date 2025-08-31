<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function show(Request $request)
    {
        $with = $request->query("with",[]);

        if(!empty($with)) {
            Auth::user()->load($with);
        }

        return new UserResource(Auth::user());
    }
}
