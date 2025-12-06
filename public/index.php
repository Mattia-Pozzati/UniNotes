<?php
require_once "../Core/Helper/Autoloader.php";

use Core\Routing\Router;

$router = new Router;

// carica tutte le route
require __DIR__ . '/../config/routes.php';

echo "Backend in sviluppo!<br>";

$router->resolve();

?>
