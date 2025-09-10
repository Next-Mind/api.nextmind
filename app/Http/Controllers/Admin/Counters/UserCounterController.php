<?php

namespace App\Http\Controllers\Admin\Counters;

use stdClass;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Counters\CounterResource;

class UserCounterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $count = User::count();
        $obj = new stdClass();
        $obj->role = "users";
        $obj->count = $count;
        return new CounterResource($obj);
    }
}
