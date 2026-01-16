<?php
namespace App\Model;

use Core\ORM\BaseModel;

class Like extends BaseModel
{
    protected $table = 'LIKE';

    /**
     * Insert a like (expects associative array with student_id and note_id)
     * Returns boolean/insert id depending on underlying ORM
     */
    public function add(array $data)
    {
        return $this->insert($data);
    }
}

?>