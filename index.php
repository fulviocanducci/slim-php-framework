<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/database.php';
require __DIR__ . '/container.php';

use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

AppFactory::setContainer($container);

$app = AppFactory::create();
$app->addBodyParsingMiddleware();


require __DIR__ . "/routes/index.php";

$app->get("/", function (Request $request, Response $response, array $args) {
    $response->getBody()->write(json_encode(["ping"=> "ok", "date" => date('Y-m-d H:i:s') ], JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();