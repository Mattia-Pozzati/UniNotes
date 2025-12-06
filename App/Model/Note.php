<?php 
namespace App\Model;

use Core\ORM\BaseModel;

/*
*  Note class
*/

class Note extends BaseModel{

    protected $table = "note";
    private $id; // identificativo univoco
    private $student_id; // Studente autore
    private $title; // Titolo
    private $visibility; // visibilità [private, public(default)]
    private $create_at; // Data di creazione
    private $updated_at; // Data ultimo aggiornamento
    private $is_deleted; // Tag per soft delete

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    public function id(){return $this->id;}
    public function student_id(){return $this->student_id;}
    public function title(){return $this->title;}
    public function visibility(){return $this->visibility;}
    public function create_at(){return $this->create_at;}
    public function updated_at(){return $this->updated_at;}
    public function is_deleted(){return $this->is_deleted;}

}

?>