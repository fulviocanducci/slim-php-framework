<?php
namespace App\Services;

use App\Validation\Rules\ExistsRule;
use App\Validation\Rules\UniqueRule;
use Rakit\Validation\Validator;

class ValidatorService
{
    protected $validator;

    public function __construct()
    {
        $this->validator = new Validator;
        $this->validator->addValidator('unique', new UniqueRule());
        $this->validator->addValidator('exists', new ExistsRule());
    }

    public function validate(array $data, array $rules)
    {
        $validation = $this->validator->make($data, $rules);
        $validation->validate();
        if ($validation->fails()) {            
            return $validation->errors()->firstOfAll();
        }
        return null; // sucesso
    }
}