<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ALLBET', 'namespace' => 'Api'], function () {
    Route::post('/login', 'ALLBETController@login');
    Route::post('/getFreeGameUrl', 'ALLBETController@getFreeGameUrl');
    Route::post('/getOriginGameUrl', 'ALLBETController@getOriginGameUrl');
    Route::post('/transaction', 'ALLBETController@transaction');
    Route::post('/getPoint', 'ALLBETController@getPoint');
});
