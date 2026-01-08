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

Router::getInstance()->get('/', 'PageController@index');

// Auth
Router::getInstance()->get('/login', 'AuthController@showLogin');
Router::getInstance()->post('/login', 'AuthController@Login');

Router::getInstance()->get('/register', 'AuthController@showRegister');
Router::getInstance()->post('/register', 'AuthController@register');

Router::getInstance()->get('/logout', 'AuthController@logout');

// Log
Router::getInstance()->get("/log", "LogController@show");

?>
