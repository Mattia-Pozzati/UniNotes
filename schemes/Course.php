<?php 

/*
*  Course class
*/

class Course implements CrudInterface{

    private $id; // identificatore univoco
    private $name; // nome del corso (Es. Tecnologie Web)
    private $cfu; // Valore cfu

    public function __construct(
        $id, 
        $name, 
        $cfu
        )
    {
        $this-> id = $id;
        $this->name = $name;
        $this->cfu = $cfu;
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