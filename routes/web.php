<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/api/hello', function () {
    return 'hello word';
});

// 认证路由
$router->post('/api/register', 'AuthController@register');
$router->post('/api/login', 'AuthController@login');

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/api/user', 'AuthController@me');
    $router->post('/api/logout', 'AuthController@logout');
    $router->post('/api/refresh', 'AuthController@refresh');
});

$router->group(['prefix' => 'api/seckill'], function () use ($router) {
    $router->post('', 'SeckillController@seckill');
    $router->get('orders', 'SeckillController@getSuccessfulSeckillOrders');
});
