<?php
use Core\Routing\Router;

require_once "../config/bootstrap.php";

// carica tutte le route
require_once "../config/routes.php";

Router::getInstance()->resolve();

?>
