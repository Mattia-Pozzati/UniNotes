<?php 
namespace App\Model;

use Core\ORM\BaseModel;

/*
*  Course class
*/

class Course extends BaseModel{

    protected $table = "course";
    private $id; // identificatore univoco
    private $name; // nome del corso (Es. Tecnologie Web)
    private $cfu; // Valore cfu

    /**
     * Metodo chiamato da fetch class per definire l'oggetto mantenendo i campi privati
     */
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    public function id() {return $this->id;}
    public function name() {return $this->name;}
    public function cfu() {return $this->cfu;}
}

?>