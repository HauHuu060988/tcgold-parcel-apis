<?php
$router->group(['namespace' => 'Backend\v1', 'prefix' => 'api/v1/'], function () use ($router) {
    $router->post('register', ['as' => 'register', 'uses' => 'UserController@register']);

    /*
    |--------------------------------------------------------------------------
    |
    |--------------------------------------------------------------------------
    */

    $router->group(['middleware' => ['authenticate']], function () use ($router) {
        $router->get('parcels/{id}', ['as' => 'getParcel', 'uses' => 'ParcelController@getParcel']);
        $router->post('parcels/', ['as' => 'createParcel', 'uses' => 'ParcelController@createParcel']);
        $router->put('parcels/{id}', ['as' => 'updateParcel', 'uses' => 'ParcelController@updateParcel']);
        $router->delete('parcels/{id}', ['as' => 'deleteParcel', 'uses' => 'ParcelController@deleteParcel']);
        $router->get('parcels', ['as' => 'calculateParcels', 'uses' => 'ParcelController@calculateParcels']);
    });
});
