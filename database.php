<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Pagination\Paginator;

date_default_timezone_set('America/Sao_Paulo');

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'slim',
    'username'  => 'root',
    'password'  => 'senha',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
$capsule->getConnection()->statement("SET time_zone = '-03:00'");
Paginator::currentPageResolver(function ($pageName = 'page') {
    return $_GET[$pageName] ?? 1;
});