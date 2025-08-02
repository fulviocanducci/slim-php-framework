<?php namespace App\Validation\Entities;

class LoginRules
{
    public function login() 
    {
        return [            
            'email' => 'required|email|exists:\App\Models\User,email',
            'password' => 'required|min:6'
        ];
    }
}