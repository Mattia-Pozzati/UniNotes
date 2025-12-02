<?php

/*
*  Comment class
*/

class Notification implements CrudInterface{

    private $id;
    private $student_id;
    private $type;
    private $message;
    private $is_read;
    private $created_At;

    public function __construct(
        $id, // Identificatore univoco
        $type, // Identificativo della nota a cui è riferito il commento
        $student_id, // Identificatico studente autore del commento
        $message // Testo del commento
        )
    {
        $this->id = $id;
        $this->type = $type;
        $this->student_id = $student_id;
        $this->message = $message;
        $this->is_read = false;
        $this->created_At = date("d-n-Y"); // data del commento
    }

    /**
     * [TODO]: Implementare insert nel database
     */
    public function create(PDO $db){

    }

    /**
     * [TODO]: Implementare update nel database
     */
    public function update(PDO $db, $new_content){
        $this->is_read = true;
    }

    /**
     * [TODO]: Lettura dal database dato l'id
     */
    public static function read(PDO $db, $id){
        
    }

    /**
     * [TODO]: Implementare delete nel database
     */
    public function delete(PDO $db){

    }
}

?>
