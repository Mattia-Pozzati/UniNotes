<?php
namespace App\Controller;

use Core\Helper\Logger;

class LogController
{
    public function show()
    {
        Logger::getInstance()->info("Mostra log");
        require_once __DIR__."/../View/template/log.php";
    }
}

?>