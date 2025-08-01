<?php
namespace App\Validation\Rules;

use Rakit\Validation\Rule;

class ExistsRule extends Rule
{
    protected $message = ":attribute not exists";
    protected $fillableParams = ['table', 'column'];
    public function check($value): bool
    {           
        $table = $this->params["table"];
        $column = $this->params["column"];
        
        $query = $table::where($column, $value);        
        return $query->exists();
    }
}