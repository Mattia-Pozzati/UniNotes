<?php 

/*
*  File class
    Nome del file nello storage -> {filepath}/{filename}/ver{current_version}.{mime_type}
*/

class File implements CrudInterface{

    private $id; // identificativo univoco file (non versione file)
    private $note_id; // identificatico nota corrispondente
    private $filename; // nome del file
    private $filepath; // path del file
    private $mime_type; // tipo di file [pdf, markdown, latex]
    private $size; // dimensione del file
    private $current_version; // Attuale versione del file 

    public function __construct(
        $id,
        $note_id,
        $filename,
        $filepath,
        $mime_type,
        $size
        )
    {
        $this-> id = $id;
        $this->note_id = $note_id;
        $this->filename = $filename;
        $this->filepath = $filepath;
        $this->mime_type = $mime_type;
        $this->size = $size;
        $this->current_version = 0; 
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
        $this->current_version+=1;
    }
    /**
     * [TODO]:
     */
    public function delete(PDO $db){

    }
}

?>