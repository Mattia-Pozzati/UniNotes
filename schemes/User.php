<?php 

/*
*  User class
*/

class User implements CrudInterface{

    private $id; // identificativo
    private $name; // {Nome}-{Cognome}
    private $email; // email con cui si registra
    private $password_hash; // password hash per non salvare in chiaro le variabili
    private $role; // Ruolo [admin(può notificare studenti), studente]
    private $reputation; // Somma dei like
    private $create_at; // Data di iscrizione

    public function __construct(
        $id,
        $name,
        $email,
        $password_hash,
        $role,
        $reputation
        )
    {
        $this-> id = $id;
        $this->name = $name;
        $this->password_hash = $password_hash;
        $this->email = $email;
        $this->create_at = date("d-m-Y");
        $this->role = $role;
        $this->reputation = $reputation; 
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
    }

    /**
     * [TODO]:
     * soft-delete mantengo le informazioni ma non mostro la nota
     */
    public function delete(PDO $db){

    }

    /**
     * [TODO]: Tutte le note SCRITTE dall'utente
     */
    public static function get_all_notes(PDO $db){

    }

    /**
     * [TODO]: Tutte le notifiche con is_read = false
     */
    public static function get_all_not_read_notification(PDO $db){

    }

    /**
     * [TODO]: Tutte le notifiche con is_read = false
     */
    public static function get_all_user_interaction(PDO $db){
        
    }

}

?>