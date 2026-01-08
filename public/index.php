<?php
use Core\Routing\Router;

require_once "../Config/bootstrap.php";

// carica tutte le route
require_once "../Config/routes.php";

Router::getInstance()->resolve();

?>
