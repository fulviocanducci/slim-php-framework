<?php namespace App\Controllers;


use App\Helpers\JsonHelper;
use App\Services\HashService;
use App\Services\ValidatorService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User;

class UserController
{
    private $hashService;
    private $validatorService;   
    private function ruleCreate() {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:\App\Models\User,email',
            'password' => 'required|min:6'
        ];
    }
    private function ruleUpdate(int $id) {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:\App\Models\User,email,'.$id,
        ];
    }
    private function ruleUpdatePassword() {
        return [        
            'id' => 'required|exists:\App\Models\User,id',    
            'passwordOld' => 'required|min:6',
            'passwordNew' => 'required|min:6|different:passwordOld'
        ];
    }
    public function __construct(HashService $hashService, ValidatorService $validatorService)
    {
        $this->hashService = $hashService;
        $this->validatorService = $validatorService;
    }
    public function index(Request $request, Response $response, array $args): Response
    {        
        return JsonHelper::ok($response, User::paginate(1));
    }

    public function create(Request $request, Response $response, array $args): Response
    {
        $data = (array)$request->getParsedBody();        
        $validate = $this->validatorService->validate($data, $this->ruleCreate());
        if ($validate->isFails()){
            return JsonHelper::error($response, $validate->getErrors());
        }
        $data['password'] = $this->hashService->make($data['password']);
        $user = User::create($data);
        return JsonHelper::created($response, $user);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $data = (array)$request->getParsedBody();        
        $validate = $this->validatorService->validate($data, $this->ruleUpdate($id));
        if ($validate->isFails()){
            return JsonHelper::error($response, $validate->getErrors());
        }
        $user = User::find($id);
        if ($user){
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
        $validate = $this->validatorService->validate($data, $this->ruleUpdatePassword());
        if ($validate->isFails()) {
            return JsonHelper::error($response, $validate->getErrors());
        }
        $user = User::find($data['id']);
        if (!$user){
            return JsonHelper::notFound($response);
        }
        if ($this->hashService->check($data['passwordOld'], $user->password)) {
            $user->password = $this->hashService->make($data['passwordNew']);
            $user->save();
            return JsonHelper::ok($response, $user);
        }
        return JsonHelper::badRequest($response, ["message" => "Password inválid"]);
    }
}