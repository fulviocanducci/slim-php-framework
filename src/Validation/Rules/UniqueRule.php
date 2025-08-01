<?php //https://github.com/rakit/validation
namespace App\Validation\Rules;

use Rakit\Validation\Rule;

class UniqueRule extends Rule
{
    protected $message = ":attribute already exists";
    protected $fillableParams = ['table', 'column','id','except'];
    public function check($value): bool
    {           
        $table = $this->params["table"];
        $column = $this->params["column"];
        $id = $this->params["id"];
        $except = $this->params["except"];
        if ($id && $except) {
            return !$table::where($column, '=', $value)->where($id, '!=', $except)->exists();
        }
        return !$table::where($column, $value)->exists();        
    }
}