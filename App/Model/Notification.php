<?php
namespace App\Model;

use Core\ORM\BaseModel;

/*
 * Notification class
 * Notifiche da visualizzare nella dashboard utente 
 */

class Notification extends BaseModel{

    protected $table = "notification";
    private $id; // Identificatico univoco 
    private $student_id; // Studente autore
    private $type; // [sistena, like, commento]
    private $message;
    private $is_read;
    private $created_at;

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    public function id(){return $this->id;}
    public function student_id(){return $this->student_id;}
    public function type(){return $this->type;}
    public function message(){return $this->message;}
    public function is_read(){return $this->is_read;}
    public function created_At(){return $this->created_at;}

}

?>
