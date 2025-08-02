<?php namespace App\Middlewares;

use App\Helpers\JwtHelper;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response as SlimResponse;

class JwtMiddleware implements MiddlewareInterface
{

    public function process(Request $request, Handler $handler): Response
    {
        $authHeader = $request->getHeaderLine('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) 
        {
            return $this->unauthorizedResponse();
        }
        $token = $matches[1];
        try {
            $decoded = JwtHelper::decodeToken($token);            
            $request = $request->withAttribute('jwt', $decoded);
            return $handler->handle($request);
        } catch (\Exception $e) {
            return $this->unauthorizedResponse();
        }
    }
    
    private function unauthorizedResponse(): Response
    {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode(['error' => 'invalid or non-existent token']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }

    public static function create(): JwtMiddleware
    {
        return new JwtMiddleware();
    }
}