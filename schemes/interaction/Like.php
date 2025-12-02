<?php 

/*
*  Comment class
*/

class Like extends UserInteraction{

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