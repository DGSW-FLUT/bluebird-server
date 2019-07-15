<?php

$router->post('/webhook', 'WebhookController@deploy');

$router->get('/', 'TestController@ping');

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->get('/', 'UserController@index');
        $router->post('/', 'UserController@create');
        $router->get('/test','UserController@userChange');
        $router->get('/count', 'UserController@count');
        $router->get('/{id}', 'UserController@show');
        $router->patch('/{id}', 'UserController@update');
        $router->delete('/{id}', 'UserController@destroy');
    });
    $router->group(['prefix' => 'messages'], function () use ($router) {
        $router->get('/', 'MessageController@index');
        $router->post('/', 'MessageController@create');
        $router->get('/count', 'MessageController@count');
        $router->get('/{id}', 'MessageController@show');
        $router->patch('/{id}', 'MessageController@update');
        $router->delete('/{id}', 'MessageController@destroy');
    });
});
