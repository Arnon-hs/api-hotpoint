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

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->get('/score', 'ScoreController@all');

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');

    $router->group(['middleware' => 'auth'], function ($router) {
        $router->get('me', 'AuthController@me');
    });

    $router->post('score/update', 'ScoreController@update');
    $router->post('score/store', 'ScoreController@store');
    $router->get('score/rating', 'ScoreController@rating');
});
