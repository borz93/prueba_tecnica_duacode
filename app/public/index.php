<?php

// Enable error reporting for development (turn off in production)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Start the session
session_start();

// Autoload classes
require __DIR__ . '/../core/autoload.php';

// Instantiate the router
$router = new app\core\Router();

// Routes for Equipo management
$router->add('GET', 'equipos', 'EquipoController@index');
$router->add('GET', 'equipos/crear', 'EquipoController@create');
$router->add('POST', 'equipos/crear', 'EquipoController@create');
$router->add('GET', 'equipos/ver/{id}', 'EquipoController@view');
$router->add('DELETE', 'equipos/eliminar/{id}', 'EquipoController@delete');
// Routes for Jugador management
$router->add('GET', 'jugadores/crear/{equipo_id}', 'JugadorController@create');
$router->add('POST', 'jugadores/crear/{equipo_id}', 'JugadorController@create');
$router->add('GET', 'jugadores/editar/{id}', 'JugadorController@edit');
$router->add('POST', 'jugadores/editar/{id}', 'JugadorController@edit');
$router->add('DELETE', 'jugadores/eliminar/{id}', 'JugadorController@delete');


// Dispatch the current request
$router->dispatch();
