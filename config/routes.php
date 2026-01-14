<?php

use Core\Routing\Router;

/**
 * Rotte rest API:
 * 
 * Metodi supportati:
 * - get
 * - post
 * // TODO Aggiungere metodi delete
 * 
 * Per ogni endpoint è necessaria una funzione definita in un qualche controller. 
 * Potremo direttamente scrivere funzioni anonime ma credo sia più pulito creare controller specializzati.
 */
$router = Router::getInstance();

// Home
$router->get('/', 'PageController@index');

// Auth
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@Login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Note - QUESTE SONO LE NUOVE ROTTE
$router->get('/note/{id}', 'NoteController@show');
$router->post('/note/{id}/like', 'NoteController@toggleLike');
$router->post('/note/{id}/comment', 'NoteController@addComment');

// Log
$router->get('/log', 'LogController@show');

// Debug (rimuovi in produzione)
$router->get('/debug-routes', function() use ($router) {
    echo "<h2>Rotte registrate:</h2><pre>";
    foreach ($router->getRoutes() as $route) {
        echo "{$route['method']} {$route['url']} -> ";
        echo is_string($route['handler']) ? $route['handler'] : 'Closure';
        echo "\n";
    }
    echo "</pre>";
});

?>
