<?php
namespace Core\Helper;

use \Exception;


/**
 * Logger class
 * @param istance -> istanza della classe (singleton)
 * @param log_file -> filepath file log
 */

class Logger
{
    private static ?Logger $instance = null;
    private string $log_file;

    private function __construct(string $filepath)
    {
        $this->log_file = $filepath;
    }

    public static function getInstance(string $filepath=""): Logger
    {
        if (self::$instance === null) {
            if ($filepath === "" ){
                throw new Exception("Error: Devi fornire filepath", 1);
            }
            self::$instance = new Logger($filepath);
        }

        return self::$instance;
    }

    /**
     * log to file
     * @param msg -> autoesplicativo
     * @param level -> livello del log (INFO, DEBUG, ERROR)
     * @param context -> array associativo per informazioni aggiutive
     */
    public function log_to_file(string $msg, string $level = 'INFO', array $context = []): void
    {
        // data e ora
        $timestamp = date('c');

        // ip richiedente
        $ip = $_SERVER['REMOTE_ADDR'] ?? '-';

        // Utente richiedente
        $userId = $_SESSION['user_id'] ?? '-';

        // Context formattato: key=value key=value to k => v
        $contextString = '-';
        if (!empty($context)) {
            $pairs = [];
            foreach ($context as $k => $v) {
                $pairs[] = "$k=>$v";
            }
            $contextString = implode(' ', $pairs);
        }

        $logLine = sprintf(
            "%s %s %s user:%s %s %s\n",
            $timestamp,
            strtoupper($level),
            $ip,
            $userId,
            $contextString,
            $msg
        );

        /**
         * Try catch per la lettura e scrittura di file. Controlla che il file esista e che ci siano i permessi necessari per accedervi.
         */
        try {
            $file = @fopen($this->log_file, 'a');
            if (!$file) {
                throw new Exception("Impossibile aprire il file di log: {$this->log_file}");
            }

            if (@fwrite($file, $logLine) === false) {
                throw new Exception("Impossibile scrivere nel file di log: {$this->log_file}");
            }

            fclose($file);

        } catch (Exception $e) {
            error_log("LOGGER ERROR: " . $e->getMessage());
        }
    }

    /**
     * Fatto apposta per log di debug. Sbizzarritevi
     * Chiama log_to_file
     */
    public function debug(string $message, array $context = []){
        $this->log_to_file($message, "DEBUG", $context);
    }


    /**
     * Fatto apposta per log di info. Sbizzarritevi
     * Chiama log_to_file
     */
    public function info(string $message, array $context = []){
        $this->log_to_file($message, "INFO", $context);
    }

    /**
     * Fatto apposta per log di info. Se lo vedi scappa più lontano che puoi
     * Chiama log_to_file
     */
    public function error(string $message, array $context = []){
        $this->log_to_file($message, "ERROR", $context);
    }
}

