<?php namespace App\Controllers;

use App\Helpers\JsonHelper;
use App\Helpers\JwtHelper;
use App\Models\User;
use App\Services\HashService;
use App\Services\ValidatorService;
use App\Validation\Entities\LoginRules;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class AuthController
{
    private HashService $hashService;
    private ValidatorService $validatorService;
    private LoginRules $loginRules;
    
    public function __construct(HashService $hashService, ValidatorService $validatorService, LoginRules $loginRules)
    {
        $this->hashService = $hashService;
        $this->validatorService = $validatorService;
        $this->loginRules = $loginRules;
    }
    
    public function index(Request $request, Response $response, array $args)
    {
        $data = $request->getAttribute('jwt');
        return JsonHelper::ok($response, $data);
    }
    
    public function login(Request $request, Response $response, array $args): Response
    {
        $data = (array)$request->getParsedBody();
        $validate = $this->validatorService->validate($data, $this->loginRules->login());
        if ($validate->isFails())
        {
            return JsonHelper::unprocessableEntity($response, $validate->getErrors());
        }  
        $user = User::where('email', $data['email'])->first();
        if ($user && $this->hashService->check($data['password'], $user->password)) 
        {
            $token = JwtHelper::generateToken([
                'user' => $user->email,
                'role' => '*'
            ]);
            return JsonHelper::ok($response, ['token' => $token]);
        }
        return JsonHelper::unauthorized($response);
    }
}