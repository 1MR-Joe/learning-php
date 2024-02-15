<?php

declare(strict_types = 1);

use App\App;
use App\Config;
use App\Container;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Router;

require_once __DIR__ . '/vendor/autoload.php';

define('STORAGE_PATH', __DIR__ . '/storage');
define('VIEW_PATH', __DIR__ . '/views');

$container = new Container();
$router = new Router($container);

// $router->register('/public/', [HomeController::class, 'index']);

$router->registerRouteFromControllerAttributes([
    HomeController::class,
    UserController::class,
]);

echo $_SERVER['REQUEST_URI'];echo "<br/>";
echo $_SERVER['REQUEST_METHOD'];echo "<br/>";

echo "<pre>";
print_r($router->getRoutes());
echo "</pre>";

(new App($container))->boot()->run();