<?php
namespace App\Model;

use Core\ORM\BaseModel;

class Note extends BaseModel
{
    protected $table = 'NOTE';

    public function byStudent(int $studentId): array
    {
        return $this->where('student_id', '=', $studentId)->get();
    }
}

?>