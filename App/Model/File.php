<?php 
namespace App\Model;

use Core\ORM\BaseModel;

/*
*  File class
*/
class File extends BaseModel{

    protected $table = "file";
    private $id; // identificatore univoco
    private $note_id; // identificatore univoco della nota
    private $filepath; // Filepath nello storage
    private $filename; // Nome del file nello storage
    private $mime_type; // Tipo di file (pdf, markdown, ...)
    private $size; // Tipo di file (pdf, markdown, ...)
    private $current_version; // Tipo di file (pdf, markdown, ...)

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
    public function filepath() {return $this->filepath;}
    public function filename() {return $this->filename;}
    public function mime_type() {return $this->mime_type;}
    public function size() {return $this->size;}
    public function current_version() {return $this->current_version;}
}

?>