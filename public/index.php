<?php

require __DIR__ . '/../src/Router.php';
require __DIR__ . '/../src/Controller/PageController.php';

$router = new Router;

// carica tutte le route
require __DIR__ . '/../Routes/routes.php';

echo "Backend in sviluppo!<br>";

$router->resolve();

?>
