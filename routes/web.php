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
});
