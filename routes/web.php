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

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\V1'], function ($api) {

        $api->get('version', function () {
            return response()->json(['status' => 'success', 'message' => env('APP_VERSION')], 200);
        });

        $api->group(['middleware' => 'auth'], function() use($api) {
            $api->get('version/auth', function () {
                return response()->json(['status' => 'success', 'message' => env('APP_VERSION')], 200);
            });
        
            $api->group(['prefix' => 'auth'], function () use($api){
                $api->post('/logout', 'Auth\LoginController@logout');
            });
        });

        $api->group(['prefix' => 'auth'], function () use($api){
            $api->post('/login', 'Auth\LoginController@login');
        });

        $api->group(['prefix' => 'balance'], function () use($api){
            $api->post('/create', 'BalanceController@store');
        });
    });
});
