<?php

use App\Modules\Auth\Providers\AuthModuleServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    Kreait\Laravel\Firebase\ServiceProvider::class,
    AuthModuleServiceProvider::class,
];
