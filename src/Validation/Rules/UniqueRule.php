<?php //https://github.com/rakit/validation
namespace App\Validation\Rules;

use Rakit\Validation\Rule;

class UniqueRule extends Rule
{
    protected $message = ":attribute already exists";
    protected $fillableParams = ['table', 'column', 'except'];
    public function check($value): bool
    {           
        $table = $this->params["table"];
        $column = $this->params["column"];
        $except = $this->params["except"];
        
        $query = $table::where($column, $value);
        if ($except) {
            $query = $query->where($column,$except);
        }
        return !$query->exists();
    }
}