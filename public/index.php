<?php

declare(strict_types=1);

ini_set('display_errors', 1);
error_reporting(E_ALL);

use Core\Routing\Router;

require_once __DIR__ . "/../Config/bootstrap.php";
require_once __DIR__ . "/../Config/routes.php";

Router::getInstance()->resolve();