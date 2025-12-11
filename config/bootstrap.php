<?php

require_once "../Core/Helper/Autoloader.php";


use Core\Routing\Router;
use Core\Helper\Logger;

$router = new Router;

Logger::getInstance(__DIR__ . '/../storage/logs/log.log');
Logger::getInstance()->info("Init...")


?>