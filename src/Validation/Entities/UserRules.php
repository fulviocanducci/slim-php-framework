<?php namespace App\Validation\Entities;

class UserRules 
{
    public function create() 
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:\App\Models\User,email',
            'password' => 'required|min:6'
        ];
    }

    public function update(int $id) 
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:\App\Models\User,email,id,'.$id,
        ];
    }
    
    public function updatePassword() 
    {
        return [        
            'id' => 'required|exists:\App\Models\User,id',    
            'passwordOld' => 'required|min:6',
            'passwordNew' => 'required|min:6|different:passwordOld'
        ];
    }
}