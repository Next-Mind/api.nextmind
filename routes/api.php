<?php

use Illuminate\Support\Facades\File;

$modulesPath = app_path('Modules');

$modules = File::directories($modulesPath);

foreach ($modules as $modulePath) {
    $routesFile = $modulePath . '/routes.php';

    if (File::exists($routesFile)) {
        require $routesFile;
    }
}
