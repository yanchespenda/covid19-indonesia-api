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

$router->get('/tester', function () use ($router) {
    
});

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/kasus', 'MainController@dataKasus');
$router->get('/positif', 'MainController@dataProvinsiPositif');
$router->get('/sembuh', 'MainController@dataProvinsiSembuh');
$router->get('/meninggal', 'MainController@dataProvinsiMeninggal');
$router->get('/odp', 'MainController@dataProvinsiOdp');
$router->get('/pdp', 'MainController@dataProvinsiPdp');
