<?php

namespace App\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Users\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function show(Request $request)
    {
        $with = $request->query("with", []);

        if (!empty($with)) {
            Auth::user()->load($with);
        }

        return new UserResource(Auth::user());
    }
}
