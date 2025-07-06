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
|*/

// ========== 无需认证的路由 ==========

// 用户注册
$router->post('/api/register', 'AuthController@register');

// 用户登录
$router->post('/api/login', 'AuthController@login');

// ========== 需要认证的路由 ==========
$router->group(['middleware' => 'auth'], function () use ($router) {
    // 获取当前用户信息
    $router->get('/api/user_info', 'AuthController@getUserInfo');
    
    // 用户退出登录
    $router->post('/api/logout', 'AuthController@logout');
    
    // 刷新令牌
    $router->post('/api/refresh', 'AuthController@refresh');

    // 商品接口分组
    $router->group(['prefix' => 'api/products'], function () use ($router) {
        // 获取商品列表
        $router->get('list', 'ProductController@index');
        
        // 创建商品
        $router->post('store', 'ProductController@store');
        
        // 获取单个商品详情
        $router->get('show/{id}', 'ProductController@show');
        
        // 更新商品信息
        $router->put('update/{id}', 'ProductController@update');
        
        // 删除商品
        $router->delete('delete/{id}', 'ProductController@destroy');
    });
});

// 未分组的秒杀接口，todo 需确认是否需要认证
$router->group(['prefix' => 'api/seckill'], function () use ($router) {
    // 参与秒杀
    $router->post('', 'SeckillController@seckill');
    
    // 获取秒杀成功订单
    $router->get('orders', 'SeckillController@getSuccessfulSeckillOrders');
});
