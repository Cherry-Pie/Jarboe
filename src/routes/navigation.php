<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('jarboe.admin_panel.prefix') .'/admin-panel',
    'middleware' => 'jarboe',
], function () {

    Route::get('navigation', 'Yaro\\Jarboe\\Http\\Controllers\\NavigationController@show');
    Route::post('navigation/create', 'Yaro\\Jarboe\\Http\\Controllers\\NavigationController@createNode');
    Route::post('navigation/update', 'Yaro\\Jarboe\\Http\\Controllers\\NavigationController@updateNode');
    Route::patch('navigation/update', 'Yaro\\Jarboe\\Http\\Controllers\\NavigationController@patchNode');
    Route::post('navigation/delete', 'Yaro\\Jarboe\\Http\\Controllers\\NavigationController@deleteNode');
    Route::post('navigation/move', 'Yaro\\Jarboe\\Http\\Controllers\\NavigationController@moveNode');

});