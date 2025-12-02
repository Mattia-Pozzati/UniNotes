<?php 

/*
*  Entity interface
*/

interface CrudInterface {
    public function create(PDO $db);
    public static function read(PDO $db, $id);
    public function update(PDO $db, $new_content);
    public function delete(PDO $db);
}

?>