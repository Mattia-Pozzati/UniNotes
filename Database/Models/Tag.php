<?php 

/*
*   Tag class
*   - Da utilizzare per cercare appunti specifici: (riassunti, esercizi svolti...)
*/

class Tag extends BaseModel{

    protected $table = "tag";
    private $id; // Identificativo univoco
    private $name; // Nome

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    public function id(){return $this->id;}
    public function name(){return $this->name;}
}

?>