<?php

use Core\Routing\Router;

/**
 * Rotte rest API:
 * 
 * Metodi supportati:
 * - get
 * - post
 * 
 * Per ogni endpoint è necessaria una funzione definita in un qualche controller. 
 * Potremo direttamente scrivere funzioni anonime ma credo sia più pulito creare controller specializzati.
 */
$router = Router::getInstance();

Router::getInstance()->get('/', 'PageController@index');
Router::getInstance()->get('/search', 'PageController@search');

// Auth
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@Login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Note
$router->get('/note/{id}', 'NoteController@show');
$router->post('/note/{id}/like', 'NoteController@toggleLike');
$router->post('/note/{id}/chat', 'NoteController@Chat');
$router->post('/note/{id}/comment', 'NoteController@addComment');
$router->post('/note/{id}/comment/{commentId}/delete', 'NoteController@deleteComment');

// Log
Router::getInstance()->get("/log", "LogController@show");   

$router->get('/user/dashboard', 'UserDashboardController@show');
$router->get('/admin', 'AdminDashboardController@show');
Router::getInstance()->post('/note/create', 'NoteController@create'); // per il form

?>
