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


use App\Model\Popo\PopoMapper;
use App\Model\Util\HttpStatus;
use Laravel\Lumen\Routing\Router;

/** @var Router $router */
$router->get('/', function () use ($router) {
    return response()->json(PopoMapper::jsonResponse(HttpStatus::OK, '', [
        'version' => $router->app->version()
    ])->serialize(), HttpStatus::OK);
});
$router->post('/', ['middleware' => ['c.jwt.auth'], function () use ($router) {
    return response()->json(PopoMapper::jsonResponse(HttpStatus::OK, '', [
        'version' => $router->app->version()
    ])->serialize(), HttpStatus::OK);
}]);

$router->group(['namespace' => 'Student', 'prefix' => '/student', 'middleware' => ['registered.role']], function () use ($router) {
    $router->group(['prefix' => '/auth'], function () use ($router) {
        $router->post('/refresh', ['middleware' => ['c.jwt.refresh'], 'uses' => 'Auth@postRefresh', 'as' => 'student.auth.refresh.post']);
        $router->group(['middleware' => ['guest']], function () use ($router) {
            $router->post('/login', ['uses' => 'Auth@postLogin', 'as' => 'student.auth.login.post']);
            $router->post('/register', ['uses' => 'Auth@postRegister', 'as' => 'student.auth.register.post']);
            $router->post('/lost', ['uses' => 'Auth@postLost', 'as' => 'student.auth.lost.post']);
            $router->patch('/recover', ['middleware' => ['valid.auth.recovery'], 'uses' => 'Auth@patchRecover', 'as' => 'student.auth.recover.patch']);
        });
    });
    $router->group(['middleware' => ['c.jwt.auth:student']], function () use ($router) {
        $router->post('/auth/logout', ['uses' => 'Auth@postLogout', 'as' => 'student.auth.logout.post']);
        $router->group(['prefix' => '/letter'], function () use ($router) {
            $router->post('/store', ['uses' => 'LetterController@postStore', 'as' => 'student.letter.store.post']);
        });
    });
});
