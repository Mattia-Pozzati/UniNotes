<?php
namespace App\Model;

use Core\ORM\BaseModel;

class Tag extends BaseModel
{
    protected $table = 'TAG';

    public function findByName(string $name): ?array
    {
        return $this->where('name', '=', $name)->first();
    }
}

?>