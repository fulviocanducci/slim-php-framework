<?php

use App\Controllers\AuthController;
use App\Middlewares\JwtMiddleware;
use Slim\Routing\RouteCollectorProxy;

$app->group('/auth', function (RouteCollectorProxy $group) 
{
    $group->get('', AuthController::class . ':index')->addMiddleware(new JwtMiddleware());
    $group->post('', AuthController::class . ':login');    
});