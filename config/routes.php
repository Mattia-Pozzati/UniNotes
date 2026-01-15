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
Router::getInstance()->get('/search', 'PageController@search');

// Auth
Router::getInstance()->get('/login', 'AuthController@showLogin');
Router::getInstance()->post('/login', 'AuthController@Login');

Router::getInstance()->get('/register', 'AuthController@showRegister');
Router::getInstance()->post('/register', 'AuthController@register');

Router::getInstance()->get('/logout', 'AuthController@logout');

// Log
Router::getInstance()->get("/log", "LogController@show");   

Router::getInstance()->get('/user/dashboard', 'PageController@userDashboard');
Router::getInstance()->get('/admin', 'PageController@adminDashboard');
Router::getInstance()->post('/note/create', 'NoteController@create'); // per il form

?>
