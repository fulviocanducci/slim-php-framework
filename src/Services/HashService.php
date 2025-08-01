<?php
namespace App\Services;

use Illuminate\Hashing\BcryptHasher;

class HashService
{
    protected $hasher;

    public function __construct()
    {
        $this->hasher = new BcryptHasher();
    }

    public function make($value)
    {
        return $this->hasher->make($value);
    }

    public function check($value, $hashedValue)
    {
        return $this->hasher->check($value, $hashedValue);
    }
}