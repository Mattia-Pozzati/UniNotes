<?php
namespace Core\Helper;

use \Exception;


/**
 * Logger class
 * @param istance -> istanza della classe (singleton)
 * @param dirname -> rirname
 */

class Storage
{
    private static ?Storage $instance = null;
    private string $dirname;

    private function __construct(string $dirname)
    {
        $this->dirname = $dirname;
    }

    public static function getInstance(string $dirname=""): Storage
    {
        if (self::$instance === null) {
            if ($dirname === "" ){
                throw new Exception("Error: Devi fornire filepath", 1);
            }
            self::$instance = new Storage($dirname);
        }

        return self::$instance;
    }

    /**
     * save a file in storage
     * @param msg -> autoesplicativo
     * @param level -> livello del log (INFO, DEBUG, ERROR)
     * @param context -> array associativo per informazioni aggiutive
     */
    public function save(string $msg, string $level = 'INFO', array $context = []): void
    {

    }
}

