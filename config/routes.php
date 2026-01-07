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
Router::getInstance()->get('/admin', 'PageController@adminDashboard'); 
Router::getInstance()->get("/log", "LogController@show");
Router::getInstance()->get('/user/dashboard', 'PageController@userDashboard');
Router::getInstance()->post('/note/create', 'NoteController@create'); // per il form

?>