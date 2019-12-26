<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// Index page
$router->get('/', ['as'=> 'home', 'uses'=>'Controller@index']);
// Analyze page
$router->get('/analyze', ['as'=> 'analyze', 'uses'=>'FactoryController@index']);
$router->post('/analyze', ['as'=> 'results', 'uses'=>'FactoryController@post']);
