<?php
namespace App\Model;

use Core\ORM\BaseModel;

class Notification extends BaseModel
{
    protected $table = 'NOTIFICATION';

    public function inboxFor(int $userId): array
    {
        return $this->where('recipient_id', '=', $userId)->order_by('created_at', 'DESC')->get();
    }
}

?>
