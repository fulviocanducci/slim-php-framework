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

    public static function unprocessableEntity(Response $response, array $errors): Response
    {
        $response->getBody()->write(json_encode(['errors' => $errors], JSON_UNESCAPED_UNICODE));
        return $response->withStatus(422)->withHeader('Content-Type', 'application/json');
    }

    public static function notFound(Response $response): Response
    {
        $response->getBody()->write(json_encode(['errors' => ["notfound" => 404], JSON_UNESCAPED_UNICODE]));
        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
    }

    public static function badRequest(Response $response, $errors): Response
    {
        $response->getBody()->write(json_encode(['errors' => $errors], JSON_UNESCAPED_UNICODE));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
    
    public static function unauthorized(Response $response, $message = 'Unauthorized'): Response
    {
        $response->getBody()->write(json_encode(['errors' => ['message' => $message]], JSON_UNESCAPED_UNICODE));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }

    public static function forbidden(Response $response, $message = 'Forbidden'): Response
    {
        $response->getBody()->write(json_encode(['errors' => ['message' => $message]], JSON_UNESCAPED_UNICODE));
        return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
    }

    public static function conflict(Response $response, $message = 'Conflict'): Response
    {
        $response->getBody()->write(json_encode(['errors' => ['message' => $message]], JSON_UNESCAPED_UNICODE));
        return $response->withStatus(409)->withHeader('Content-Type', 'application/json');
    }

    public static function noContent(Response $response): Response
    {
        return $response->withStatus(204);
    }

    public static function internalServerError(Response $response, $message = 'Internal Server Error'): Response
    {
        $response->getBody()->write(json_encode(['errors' => ['message' => $message]], JSON_UNESCAPED_UNICODE));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }

    public static function serviceUnavailable(Response $response, $message = 'Service Unavailable'): Response
    {
        $response->getBody()->write(json_encode(['errors' => ['message' => $message]], JSON_UNESCAPED_UNICODE));
        return $response->withStatus(503)->withHeader('Content-Type', 'application/json');
    }
}