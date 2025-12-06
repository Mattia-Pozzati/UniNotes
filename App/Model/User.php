<?php 
namespace App\Model;

use Core\ORM\BaseModel;

/*
*  User class
*/

class User extends BaseModel{

    protected $table = "user";
    private $id; // identificativo
    private $name; // {Nome}-{Cognome}
    private $email; // email con cui si registra
    private $password_hash; // password hash per non salvare in chiaro le variabili
    private $role; // Ruolo [admin(può notificare studenti), studente]
    private $reputation; // Somma dei like
    private $created_at; // Data di iscrizione

    public function __set($property, $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }

    public function id(){return $this->id;}
    public function name(){return $this->name;}
    public function email(){return $this->email;}
    public function password_hash(){return $this->password_hash;}
    public function role(){return $this->role;}
    public function reputation(){return $this->reputation;}
    public function created_At(){return $this->created_at;}


}

?>