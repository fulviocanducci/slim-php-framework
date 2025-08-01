<?php namespace App\Records;

class ModelValidationRecord
{
    private bool $isValid;
    private array $errors = [];
    public function __construct(bool $isValid, array $errors = [])
    {
        $this->errors = $errors;
        $this->isValid = $isValid;
    }

    public function isValid(): bool
    {
        return $this->isValid === true;
    }

    public function isFails(): bool
    {
        return $this->isValid === false;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public static function Success()
    {
        return new ModelValidationRecord(true, []);
    }

    public static function Fails(array $errors)
    {
        return new ModelValidationRecord(false, $errors);
    }
}