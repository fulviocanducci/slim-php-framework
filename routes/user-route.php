<?php

use App\Middlewares\JwtMiddleware;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UserController;

$app->group('/user', function (RouteCollectorProxy $group) {
    $group->get('', UserController::class . ':index');
    $group->get('/{id}', UserController::class . ':show');
    $group->post('', UserController::class . ':create');
    $group->post('/update/password', UserController::class . ':updatePassword');
    $group->put('/{id}', UserController::class . ':update');
    $group->delete('/{id}', UserController::class . ':delete');
})->addMiddleware(new JwtMiddleware());