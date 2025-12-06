<?php

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

$router->get('/', 'PageController@index');

?>