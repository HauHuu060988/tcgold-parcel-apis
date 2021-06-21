<?php
$router->GROUP(['namespace' => 'Backend\v1', 'prefix' => 'api/backend/v1/'], function () use ($router) {
    $router->GET('/test-api', 'UserController@testApi');
    /*
    |--------------------------------------------------------------------------
    |
    |--------------------------------------------------------------------------
    */
    $router->GET('/parcels/{id}', ['as' => 'getParcel', 'uses' => 'ParcelController@getParcel']);
    $router->POST('/parcels/', ['as' => 'createParcel', 'uses' => 'ParcelController@createParcel']);
    $router->PUT('/parcels/{id}', ['as' => 'updateParcel', 'uses' => 'ParcelController@updateParcel']);
    $router->DELETE('/parcels/{id}', ['as' => 'deleteParcel', 'uses' => 'ParcelController@deleteParcel']);
    $router->GET('/parcels', ['as' => 'calculateParcel', 'uses' => 'ParcelController@calculateParcel']);
});
