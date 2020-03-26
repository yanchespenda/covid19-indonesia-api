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

use duzun\hQuery;

/* $router->get('/tester', function () use ($router) {
    // $getHelper = \App\Helpers\OdpPdpDKIJakarta::getInstance()->init()->runOdp()->getOdp();
    $getHelper = \App\Helpers\ProvinsiJawaDIYogyakarta::getInstance()->init()->run()->get();

    return response()->json([
        'status' => true,
        'message' => 'Success',
        'data' => @$getHelper
    ], 200);
}); */

$router->get('/', function () use ($router) {
    return response()->json([
        'message' => 'Hi, selamat datang di API Covid 19 Indonesia',
    ], 200);
});

$router->get('/kasus', 'MainController@dataKasus');

$router->group(['prefix' => 'nasional'], function () use ($router) {
    $router->get('/', 'MainController@dataNasionalSummary');
    $router->get('/positif', 'MainController@dataNasionalPositif');
    $router->get('/sembuh', 'MainController@dataNasionalSembuh');
    $router->get('/meninggal', 'MainController@dataNasionalMeninggal');
});

$router->group(['prefix' => 'provinsi'], function () use ($router) {
    $router->get('/', 'MainController@dataProvinsiSummary');
    $router->get('/positif', 'MainController@dataProvinsiPositif');
    $router->get('/sembuh', 'MainController@dataProvinsiSembuh');
    $router->get('/meninggal', 'MainController@dataProvinsiMeninggal');
    $router->get('/odp', 'MainController@dataProvinsiOdp');
    $router->get('/pdp', 'MainController@dataProvinsiPdp');
});


