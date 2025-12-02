<?php 

/*
*   Tag class
*   - Da utilizzare per cercare appunti specifici: (riassunti, esercizi svolti...)
*/

class Tag implements CrudInterface{

    private $id; // Identificativo univoco
    private $name; // Nome

    public function __construct(
        $id, 
        $name, 
        )
    {
        $this-> id = $id;
        $this->name = $name;
    }

    public function create(PDO $db){

    }
    public static function read(PDO $db, $id){

    }
    public function update(PDO $db, $new_content){

    }
    public function delete(PDO $db){

    }
}

?>