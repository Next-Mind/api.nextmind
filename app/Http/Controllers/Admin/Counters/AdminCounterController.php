<?php

namespace App\Http\Controllers\Admin\Counters;

use stdClass;
use App\Modules\Users\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Counters\CounterResource;

class AdminCounterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $count = User::role('admin')->count();
        $obj = new stdClass();
        $obj->role = "admin";
        $obj->count = $count;
        return new CounterResource($obj);
    }
}
