<?php 

/*
*  Comment class
*  - (int) id -> identifier
*  - (string) name -> name (Es. Tecnologie Web)
*  - (int) cfu -> [TODO]
*/

class Comment extends UserInteraction{

    private $content;

    public function __construct(
        $id, // Identificatore univoco
        $note_id, // Identificativo della nota a cui è riferito il commento
        $student_id, // Identificatico studente autore del commento
        $content // Testo del commento
        )
    {
        parent::__construct($id, $note_id, $student_id);
        $this->content = $content;
    }

    /**
     * [TODO]: Implementare insert nel database
     */
    public function create(PDO $db)
    {
        throw new Exception("Error: Non implementata", 1);
    }

    /**
     * [TODO]: Implementare update nel database
     */
    public function update(PDO $db, $new_content)
    {
        /*
            [TODO]: Ha senso aggiornare i corsi 
        */
        throw new Exception("Error: Non modificabile", 1);
        
    }

    public static function read(PDO $db, $id)
    {
        throw new Exception("Error: Non implementata", 1);
    }

    public function delete(PDO $db)
    {
        throw new Exception("Error: Non implementata", 1);
    } 
}

?>