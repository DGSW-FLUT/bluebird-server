<?php

$router->post('/webhook', 'WebhookController@deploy');


$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['middleware' => 'jwt.auth'], function () use ($router) {
        $router->group(['prefix' => 'users'], function () use ($router) {
            $router->get('/', 'UserController@index');
            $router->post('/', 'UserController@create');
            $router->get('/change', 'UserController@showChange');
            $router->get('/search', 'UserController@search');
            $router->get('/count', 'UserController@count');
            $router->patch('/payment/{id}', 'UserController@payment');
            $router->get('/{id}', 'UserController@show');
            $router->patch('/{id}', 'UserController@update');
            $router->delete('/{id}', 'UserController@destroy');
        });

        $router->group(['prefix' => 'messages'], function () use ($router) {
            $router->get('/', 'MessageController@index');
            $router->post('/', 'MessageController@create');
            $router->post('/send/sms', 'MessageController@sendSMS');
            $router->post('/send/mms', 'MessageController@sendMMS');
            $router->get('/count', 'MessageController@count');
            $router->get('/{id}', 'MessageController@show');
            $router->patch('/{id}', 'MessageController@update');
            $router->delete('/{id}', 'MessageController@destroy');
        });

        $router->group(['prefix' => 'auth'], function () use ($router) {
            $router->get('/', 'AuthController@index');
            $router->post('/', 'AuthController@create');
            $router->patch('/', 'AuthController@update');
            $router->delete('/{id}', 'AuthController@destroy');
        });

        $router->group(['prefix' => 'backup'], function () use ($router) {
            $router->get('/save', 'BackupController@save');
            $router->get('/', 'BackupController@show');
            $router->get('/{id}', 'BackupController@export');
            $router->get('/rollback/{id}', 'BackupController@rollback');
            $router->delete('/{id}', 'BackupController@destroy');
        });

        $router->group(['prefix' => 'fee'], function () use ($router) {
            $router->get('/{year}', 'MembershipFeeController@show');
            $router->get('/payment/{id}', 'MembershipFeeController@payment');
        });
    });

    $router->post('/auth/login', 'AuthController@authenticate');
});

$router->get('/{any:.*}', function () {
    return view('main');
});
