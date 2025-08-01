<?php
use DI\Container;

$container = new Container();

$container->set(\App\Services\HashService::class, function() {
    return new \App\Services\HashService();
});
$container->set(\App\Services\ValidatorService::class, function() {
    return new \App\Services\ValidatorService();
});