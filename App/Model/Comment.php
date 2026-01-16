<?php
namespace App\Model;

use Core\ORM\BaseModel;

class Comment extends BaseModel
{
    protected $table = 'COMMENT';

    public function forNote(int $noteId): array
    {
        return $this->where('note_id', '=', $noteId)
                    ->where('parent_comment_id', 'IS', 'NULL')
                    ->get();
    }

    public function replies(int $parentId): array
    {
        return $this->where('parent_comment_id', '=', $parentId)->get();
    }
}

?>