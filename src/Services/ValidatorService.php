<?php
namespace App\Services;

use App\Records\ModelValidationRecord;
use App\Validation\Rules\ExistsRule;
use App\Validation\Rules\UniqueRule;
use Rakit\Validation\Validator;

class ValidatorService
{
    private $validator;
    private $errors = [];
    private $isValid = false;
    public function __construct()
    {
        $this->validator = new Validator;
        $this->validator->addValidator('unique', new UniqueRule());
        $this->validator->addValidator('exists', new ExistsRule());
    }

    public function validate(array $data, array $rules): ModelValidationRecord
    {
        $validation = $this->validator->make($data, $rules);
        $validation->validate();
        if ($validation->fails()) {            
            return ModelValidationRecord::Fails($validation->errors()->firstOfAll());
        }        
        return ModelValidationRecord::Success();
    }
}