<?php
namespace Core\Database;

use \PDO;

/**
 * Classe per la creazione della connessione con il database
 */

class Database {
    // Voglio accedere staticamente all'istanza (Pattern singleton)
    private static $instance = null;
    private $pdo;

    //TODO cambiare con effettivi parametri connessione al db
    private function __construct() {
        $this->pdo = new PDO(
            "mysql:host=localhost;dbname=test;charset=utf8",
            "root",
            ""
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}



?>