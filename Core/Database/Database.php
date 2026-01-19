<?php
namespace Core\Database;

use Core\Helper\Logger;
use \PDO;

/**
 * Classe per la creazione della connessione con il database
 */
class Database {
    // Voglio accedere staticamente all'istanza (Pattern singleton)
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct() {
        // Carica configurazione da .env.local
        $envPath = dirname(__DIR__, 2) . '/.env.local';
        
        $host = 'localhost';
        $port = 3306;  // Porta standard MySQL
        $dbname = 'uninotes';
        $username = 'root';
        $password = '';
        
        if (file_exists($envPath)) {
            $env = parse_ini_file($envPath);
            $host = $env['DB_HOST'] ?? $host;
            $port = $env['DB_PORT'] ?? $port;
            $dbname = $env['DB_NAME'] ?? $dbname;
            $username = $env['DB_USERNAME'] ?? $username;
            $password = $env['DB_PASSWORD'] ?? $password;
        }
        
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            Logger::getInstance()->info("Connessione al database riuscita", [
                "host" => $host,
                "port" => $port,
                "database" => $dbname
            ]);
        } catch (\PDOException $e) {
            Logger::getInstance()->error("Connessione al database fallita", [
                "error" => $e->getMessage(),
                "dsn" => $dsn
            ]);
            throw $e;
        }
    }

    public static function getInstance(): PDO { 
        if (!self::$instance) {
            self::$instance = new self();
        }
        Logger::getInstance()->info("Rilasciata istanza database a user");
        return self::$instance->pdo;
    }
}

?>