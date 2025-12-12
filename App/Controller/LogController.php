<?php
namespace App\Controller;

use Core\Helper\Logger;

class LogController
{
    public function show()
    {
        // TODO: Da togliere dal commento in prod
        // Logger::getInstance()->info("Mostra log");
        require_once __DIR__."/../../public/log.php";
    }
}

?>