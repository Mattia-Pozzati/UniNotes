<?php

require_once "../Core/Helper/Autoloader.php";


use Core\Routing\Router;
use Core\Helper\Logger;

Router::getInstance();

Logger::getInstance(__DIR__ . '/../storage/logs/log.log');
// TODO: Da togliere dal commento in prod
// Logger::getInstance()->info("Init...")

// Abilita il "fake DB" per sviluppo: impostalo a true per usare dati finti
if (!defined(constant_name: 'USE_MOCK_DB')) {
	define('USE_MOCK_DB', true);
}


?>