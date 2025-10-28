<?php

namespace App\Modules\Users\Services;

use App\Modules\Users\Models\User;

class GetUserCountByRoleService
{
    public function execute(string $role): int
    {
        switch ($role) {
            case 'all':
            case 'users':
                return User::query()->count();

            case 'admin':
            case 'admins':
                return User::role('admin')->count();

            case 'psychologist':
            case 'psychologists':
                return User::role('psychologist')->count();

            case 'student':
            case 'students':
                return User::role('student')->count();

            default:
                return 0;
        }
    }
}
