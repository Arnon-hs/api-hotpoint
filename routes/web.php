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
$router->get('/score', 'ScoreController@index');

$router->get('/meeting', 'MeetingController@index');

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');

    $router->group(['prefix' => 'meeting'], function () use ($router) {
        $router->post('set', 'MeetingController@setMeeting');
        $router->post('update', 'MeetingController@update');
        $router->post('delete', 'MeetingController@destroy');
    });

    $router->group(['middleware' => 'auth'], function ($router) {
        $router->get('me', 'AuthController@me');
        $router->get('speakers', 'SpeakerController@all');
        $router->get('sessions', 'SessionController@all');
        $router->get('poll', 'PollController@all');
        $router->post('user_answer', 'UserAnswerController@all');
    });

    $router->group(['prefix' => 'score'], function ($router) {
        $router->post('update', 'ScoreController@update');
        $router->post('store', 'ScoreController@store');
        $router->get('rating', 'ScoreController@rating');
        $router->get('list', 'ScoreController@all');
    });
});
