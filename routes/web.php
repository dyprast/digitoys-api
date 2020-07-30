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
    return 'Digitoys';
});

$router->group(['prefix' => '/admin', 'namespace' => 'Admin'], function ($router) {
    $router->post('/authenticate',     'AdminController@authenticate');

    $router->group(['middleware' => 'admin'], function ($router) {

        $router->group(['prefix' => '/manage'], function ($router) {
            $router->get('/',                   'AdminController@get');
            $router->get('/{id}',               'AdminController@show');
            $router->post('/create',            'AdminController@create');
            $router->put('/update/{id}',        'AdminController@update');
            $router->delete('/delete/{id}',     'AdminController@delete');
        });

        $router->group(['prefix' => '/main-distributor'], function ($router) {
            $router->get('/',                   'MainDistributorController@get');
            $router->get('/{id}',               'MainDistributorController@show');
            $router->post('/create',            'MainDistributorController@create');
            $router->put('/update/{id}',        'MainDistributorController@update');
            $router->delete('/delete/{id}',     'MainDistributorController@delete');
        });

        $router->group(['prefix' => '/sub-distributor'], function ($router) {
            $router->get('/',                   'SubDistributorController@get');
            $router->get('/{id}',               'SubDistributorController@show');
            $router->post('/create',            'SubDistributorController@create');
            $router->put('/update/{id}',        'SubDistributorController@update');
            $router->delete('/delete/{id}',     'SubDistributorController@delete');
        });

        $router->group(['prefix' => '/categories'], function ($router) {
            $router->get('/',                   'CategoryController@get');
            $router->post('/create',            'CategoryController@create');
            $router->put('/update/{id}',        'CategoryController@update');
            $router->delete('/delete/{id}',     'CategoryController@delete');
        });

        $router->group(['prefix' => '/products'], function ($router) {
            $router->get('/',                   'ProductController@get');
            $router->post('/create',            'ProductController@create');
            $router->put('/update/{id}',        'ProductController@update');
            $router->delete('/delete/{id}',     'ProductController@delete');
        });

        $router->group(['prefix' => '/news'], function ($router) {
            $router->get('/',                   'NewsController@get');
            $router->post('/create',            'NewsController@create');
            $router->put('/update/{id}',        'NewsController@update');
            $router->delete('/delete/{id}',     'NewsController@delete');
        });

        $router->group(['prefix' => '/notification-message'], function ($router) {
            $router->get('/',                   'NotificationMessageController@get');
            $router->post('/create',            'NotificationMessageController@create');
            $router->put('/update/{id}',        'NotificationMessageController@update');
            $router->delete('/delete/{id}',     'NotificationMessageController@delete');
        });

        $router->group(['prefix' => '/regions'], function ($router) {
            $router->get('/',                   'RegionController@get');
            $router->post('/create',            'RegionController@create');
            $router->put('/update/{id}',        'RegionController@update');
            $router->delete('/delete/{id}',     'RegionController@delete');
        });

        $router->group(['prefix' => '/sub-regions'], function ($router) {
            $router->get('/',                   'SubRegionController@get');
            $router->post('/create',            'SubRegionController@create');
            $router->put('/update/{id}',        'SubRegionController@update');
            $router->delete('/delete/{id}',     'SubRegionController@delete');
        });

        $router->group(['prefix'    => '/order'], function ($router) {
            $router->get('/',                       'OrderController@get');
            $router->post('/create',                'OrderController@create');
            $router->put('/update/{id}',            'OrderController@update');
            $router->delete('/delete/{id}',         'OrderController@delete');
        });

        $router->group(['prefix'    => '/transaction'], function ($router) {
            $router->get('/',                       'TransactionController@get');
            $router->post('/create',                'TransactionController@create');
            $router->put('/update/{id}',            'TransactionController@update');
            $router->delete('/delete/{id}',         'TransactionController@delete');
        });
    });
});


$router->group(['prefix' => '/main-distributor', 'namespace' => 'MainDistributor'], function ($router) {
    $router->post('/authenticate',     'MainDistributorController@authenticate');

    $router->group(['middleware' => 'main_distributor'], function ($router) {
        $router->group(['prefix'    => '/products'], function ($router) {
            $router->get('/',                   'ProductController@get');
        });

        $router->group(['prefix'    => '/regions'], function ($router) {
            $router->get('/',                   'RegionController@get');
        });

        $router->group(['prefix'    => '/sub-regions'], function ($router) {
            $router->get('/',                   'SubRegionController@get');
        });

        $router->group(['prefix'    => '/cart'], function ($router) {
            $router->get('/',                       'CartController@get');
            $router->post('/create',                'CartController@create');
            $router->put('/update/{id}',            'CartController@update');
            $router->delete('/delete/{id}',         'CartController@delete');
        });

        $router->group(['prefix'    => '/order'], function ($router) {
            $router->get('/',                       'OrderController@get');
            $router->put('/accept/{id}',            'OrderController@accept');
        });

        $router->group(['prefix'    => '/checkout'], function ($router) {
            $router->get('/',                       'CheckoutController@get');
        });
    });
});


$router->group(['prefix' => '/sub-distributor', 'namespace' => 'SubDistributor'], function ($router) {
    $router->post('/authenticate',     'SubDistributorController@authenticate');

    $router->group(['middleware' => 'sub_distributor'], function ($router) {
        $router->group(['prefix'    => '/products'], function ($router) {
            $router->get('/',                   'ProductController@get');
        });

        $router->group(['prefix'    => '/regions'], function ($router) {
            $router->get('/',                   'RegionController@get');
        });

        $router->group(['prefix'    => '/sub-regions'], function ($router) {
            $router->get('/',                   'SubRegionController@get');
        });

        $router->group(['prefix'    => '/cart'], function ($router) {
            $router->get('/',                       'CartController@get');
            $router->post('/create',                'CartController@create');
            $router->put('/update/{id}',            'CartController@update');
            $router->delete('/delete/{id}',         'CartController@delete');
        });

        $router->group(['prefix'    => '/checkout'], function ($router) {
            $router->get('/show/{id}',              'CheckoutController@show');
            $router->post('/create',                'CheckoutController@create');
            $router->delete('/delete/{id}',         'CheckoutController@delete');
        });
    });
});
