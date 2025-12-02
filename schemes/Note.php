<?php 

/*
*  Note class
*/

class Note implements CrudInterface{

    private $id; // identificativo univoco
    private $student_id; // Studente autore
    private $title; // Titolo
    private $visibility; // visibilità [private, public(default)]
    private $create_at; // Data di creazione
    private $updated_at; // Data ultimo aggiornamento
    private $is_deleted; // Tag per soft delete

    public function __construct(
        $id,
        $student_id,
        $title,
        $visibility,
        $updated_at,
        $is_deleted
        )
    {
        $this-> id = $id;
        $this->student_id = $student_id;
        $this->title = $title;
        $this->visibility = $visibility;
        $this->create_at = date("d-m-Y");
        $this->updated_at = date("d-m-Y");
        $this->is_deleted = $is_deleted; 
    }

    /**
     * [TODO]:
     */
    public function create(PDO $db){

    }
    /**
     * [TODO]:
     */
    public static function read(PDO $db, $id){

    }
    /**
     * [TODO]:
     */
    public function update(PDO $db, $new_content){
        $this->updated_at = date("d-m-Y");
    }

    /**
     * [TODO]:
     * soft-delete mantengo le informazioni ma non mostro la nota
     */
    public function delete(PDO $db){
        $this->is_deleted = true;
        $this->visibility = "private";
    }

    /**
     * [TODO]: Il file associato (Ultima versione)
     */
    public function get_file(PDO $db){

    }

    /**
     * [TODO]: Il corso associato
     */
    public function get_course(PDO $db){

    }

    /**
     * [TODO]: La lista dei tag associati per visualizzazione
     */
    public function get_tags(PDO $db){

    }

    /**
     * [TODO]: Il nome dell'autore
     */
    public function get_user(PDO $db){

    }

}

?>