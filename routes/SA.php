<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'SA', 'namespace' => 'Api'], function () {
    Route::post('/login', 'SAController@login');
    Route::post('/getFreeGameUrl', 'SAController@getFreeGameUrl');
    Route::post('/getOriginGameUrl', 'SAController@getOriginGameUrl');
    Route::post('/regUserInfo', 'SAController@regUserInfo');
    Route::post('/transaction', 'SAController@transaction');
    Route::post('/getPoint', 'SAController@getPoint');
});
