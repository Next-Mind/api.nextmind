<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Users\Services\GetUserCountByRoleService;

class UserCountController extends Controller
{
    public function all(GetUserCountByRoleService $service)
    {
        return response()->json([
            'data' => [
                'role'  => 'users',
                'count' => $service->execute('all'),
            ],
        ]);
    }

    public function admins(GetUserCountByRoleService $service)
    {
        return response()->json([
            'data' => [
                'role'  => 'admin',
                'count' => $service->execute('admin'),
            ],
        ]);
    }

    public function psychologists(GetUserCountByRoleService $service)
    {
        return response()->json([
            'data' => [
                'role'  => 'psychologist',
                'count' => $service->execute('psychologist'),
            ],
        ]);
    }

    public function students(GetUserCountByRoleService $service)
    {
        return response()->json([
            'data' => [
                'role'  => 'student',
                'count' => $service->execute('student'),
            ],
        ]);
    }
}
