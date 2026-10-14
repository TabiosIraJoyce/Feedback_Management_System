<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$router = $app->make('router');
foreach ($router->getRoutes() as $route) {
    if (str_contains($route->uri(), 'feedback')) {
        echo $route->uri() . PHP_EOL;
    }
}
