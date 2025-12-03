<?php 

/*
*  Like class
*/
class Like extends BaseModel{

    protected $table = "like";
    private $id; // identificatore univoco
    private $note_id; // identificatore univoco della nota
    private $student_id; // identificatore univoco dello studente autore del commento
    private $created_at; // Data di creazioen

    /**
     * Metodo chiamato da fetch class per definire l'oggetto mantenendo i campi privati
     */
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    public function id() {return $this->id;}
    public function note_id() {return $this->note_id;}
    public function student_id() {return $this->student_id;}
    public function created_at() {return $this->created_at;}
}

?>