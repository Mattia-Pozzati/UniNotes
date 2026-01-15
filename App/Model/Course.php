<?php
namespace App\Model;

use Core\ORM\BaseModel;

class Course extends BaseModel
{
    protected $table = 'COURSE';

    public function getCourseNameById(int $id): ?string
    {
        $c = $this->where('id', '=', $id)->first();
        return $c ? $c['name'] : null;
    }

    public function getAll(): array
    {
        return $this->get();
    }
}

?>