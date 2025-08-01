<?php namespace App\Controllers;


use App\Helpers\JsonHelper;
use App\Services\HashService;
use App\Services\ValidatorService;
use App\Validation\Entities\UserRules;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User;

class UserController
{
    private $hashService;
    private $validatorService; 
    private $userRules;  
    
    public function __construct(HashService $hashService, ValidatorService $validatorService, UserRules $userRules)
    {
        $this->hashService = $hashService;
        $this->validatorService = $validatorService;
        $this->userRules = $userRules;
    }
    
    public function index(Request $request, Response $response, array $args): Response
    {        
        return JsonHelper::ok($response, User::paginate(30));
    }

    public function create(Request $request, Response $response, array $args): Response
    {
        $data = (array)$request->getParsedBody();        
        $validate = $this->validatorService->validate($data, $this->userRules->create());
        if ($validate->isFails())
        {
            return JsonHelper::unprocessableEntity($response, $validate->getErrors());
        }
        $data['password'] = $this->hashService->make($data['password']);
        $user = User::create($data);
        return JsonHelper::created($response, $user);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $data = (array)$request->getParsedBody();        
        $validate = $this->validatorService->validate($data, $this->userRules->update($id));
        if ($validate->isFails())
        {
            return JsonHelper::unprocessableEntity($response, $validate->getErrors());
        }
        $user = User::find($id);
        if ($user)
        {
            $user->name = $data['name'];
            $user->email = $data['email'];                
            $user->save();
            return JsonHelper::ok($response, $user);
        }
        return JsonHelper::notFound($response);        
    }

    public function updatePassword(Request $request, Response $response, array $args): Response
    {
        $data = (array)$request->getParsedBody();
        $validate = $this->validatorService->validate($data, $this->userRules->updatePassword());
        if ($validate->isFails()) 
        {
            return JsonHelper::unprocessableEntity($response, $validate->getErrors());
        }
        $user = User::find($data['id']);
        if (!$user)
        {
            return JsonHelper::notFound($response);
        }
        if ($this->hashService->check($data['passwordOld'], $user->password)) 
        {
            $user->password = $this->hashService->make($data['passwordNew']);
            $user->save();
            return JsonHelper::ok($response, $user);
        }
        return JsonHelper::badRequest($response, ["message" => "Password inválid"]);
    }
}