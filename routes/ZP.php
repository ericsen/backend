<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ZP', 'namespace' => 'Api'], function () {
    Route::post('/getOrder', 'ZspeedController@getOrder');
});
