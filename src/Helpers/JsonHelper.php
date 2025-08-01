<?php
namespace App\Helpers;

use Psr\Http\Message\ResponseInterface as Response;

class JsonHelper
{
    public static function ok(Response $response, $data): Response
    {
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(200);
    }

    public static function created(Response $response, $data): Response
    {
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(201);
    }

    public static function error(Response $response, array $errors): Response
    {
        $response->getBody()->write(json_encode(['errors' => $errors]));
        return $response->withStatus(422)->withHeader('Content-Type', 'application/json');
    }

    public static function notFound(Response $response): Response
    {
        $response->getBody()->write(json_encode(['errors' => ["notfound" => 404]]));
        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
    }

    public static function badRequest(Response $response, $errors): Response
    {
        $response->getBody()->write(json_encode(['errors' => $errors]));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
}