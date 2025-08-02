<?php
namespace App\Helpers;

use Firebase\JWT\JWT;

class JwtHelper
{
    private static $secret = '2afa87bca42486d22d86467d6bfea3c5d0ac40780bf18fe6dfcadd5499d51e1d';

    public static function generateToken($payload, $expireSeconds = 3600)
    {
        $issuedAt = time();
        $expire = $issuedAt + $expireSeconds;

        $tokenPayload = array_merge($payload, [
            'iat' => $issuedAt,
            'exp' => $expire
        ]);

        return JWT::encode($tokenPayload, self::$secret, 'HS256');
    }

    public static function decodeToken($token)
    {
        return JWT::decode($token, new \Firebase\JWT\Key(self::$secret, 'HS256'));
    }
}