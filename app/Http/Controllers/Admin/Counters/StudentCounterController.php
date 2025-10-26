<?php

namespace App\Http\Controllers\Admin\Counters;

use App\Http\Resources\Admin\Counters\CounterResource;
use stdClass;
use App\Modules\Users\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentCounterController extends Controller
{

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $count = User::role('student')->count();
        $obj = new stdClass();
        $obj->role = "student";
        $obj->count = $count;
        return new CounterResource($obj);
    }
}
