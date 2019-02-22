<?php

use Illuminate\Support\Facades\Route;
use Yaro\Jarboe\Facades\Jarboe;
use Yaro\Jarboe\Http\Controllers\AdminsController;
use Yaro\Jarboe\Http\Controllers\AdminsRolesController;

Route::group(Jarboe::routeGroupOptions(), function () {
    Route::group([
        'prefix' => 'admin-panel',
    ], function () {
        Jarboe::crud('admins', AdminsController::class);
        Jarboe::crud('roles-and-permissions', AdminsRolesController::class);
    });
});
