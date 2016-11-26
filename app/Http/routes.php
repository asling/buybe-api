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

$app->get('/', function () use ($app) {
    return $app->version().'2333';
});
$app->get("/api/v1/categories","ApiV1Controller@getWholeCategory");
$app->get("/api/v1/products","ApiV1Controller@getProductsInfo");
$app->get("/api/v1/productsInSpecial","ApiV1Controller@getProductsInSpecial");
$app->get("/api/v1/product","ApiV1Controller@getProductDetail");
$app->get("/api/test/jsonp","ExampleController@jsonp_test");
$app->get("/api/v1/tae","ApiV1Controller@getProductTAE");
