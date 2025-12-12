<?php

require_once "../Core/Helper/Autoloader.php";


use Core\Routing\Router;
use Core\Helper\Logger;

Router::getInstance();

Logger::getInstance(__DIR__ . '/../storage/logs/log.log');
// TODO: Da togliere dal commento in prod
// Logger::getInstance()->info("Init...")


?>