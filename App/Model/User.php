<?php
namespace App\Model;

use Core\ORM\BaseModel;

class User extends BaseModel
{
    protected $table = 'USER';

    /**
     * Convenience: find user by email
     * Returns associative array or null
     */
    public function findByEmail(string $email): ?array
    {
        return $this->where('email', '=', $email)->first();
    }
}

?>