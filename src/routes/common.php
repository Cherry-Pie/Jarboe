<?php

use Illuminate\Support\Facades\Route;
use Yaro\Jarboe\Facades\Jarboe;
use Yaro\Jarboe\Http\Controllers\CommonController;

Route::group(Jarboe::routeGroupOptions(), function () {
    Route::get('reset-panel-settings', CommonController::class .'@resetPanelSettings')->name('reset_panel_settings');
    Route::get('refresh-system-values', CommonController::class .'@refreshSystemValues')->name('refresh_system_values');
});
