<?php 

/*
*  Comment class
*/

abstract class UserInteraction implements CrudInterface{

    private $id; // Identificatore univoco
    private $note_id; // Identificativo della nota a cui è riferito il commento
    private $student_id; // Identificatico studente autore del commento
    private $created_At; // Data del commento

    public function __construct(
        $id, 
        $note_id, 
        $student_id, 
        )
    {
        $this->id = $id;
        $this->note_id = $note_id;
        $this->student_id = $student_id;
        $this->created_At = date("d-n-Y"); 
    }

    /**
     * [TODO]: Implementare insert nel database
     */
    public abstract function create(PDO $db);

    /**
     * [TODO]: Implementare update nel database
     */
    public abstract function update(PDO $db, $new_content);

    /**
     * [TODO]: Lettura dal database dato l'id
     */
    public static function read(PDO $db, $id){
        
    }

    /**
     * [TODO]: Implementare delete nel database
     */
    public abstract function delete(PDO $db);
}

?>