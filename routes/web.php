<?php
// use Illuminate\Support\Str;
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

// $router->get('/key', function(){
//     return Str::random(32);
//     // return str::random(32);
// });

$router->group(['middleware' => 'auth','prefix' => 'api'], function ($router) 
// $router->group(['prefix' => 'api'], function ($router) 
{
    $router->get('user', 'UserController@user');
    $router->get('refresh', 'UserController@refresh');
    $router->post('logout', 'UserController@logout');

});

$router->group(['prefix' => 'api'], function () use ($router) 
{
    $router->post('register', 'UserController@register');
    $router->post('login', 'UserController@login');

});