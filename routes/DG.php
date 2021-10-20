<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'DG', 'namespace' => 'Api'], function () {
    Route::post('/test', function (){
        return 123;
    });
    Route::post('/createUser', 'DGController@signUp');
    Route::post('/login', 'DGController@login');
    Route::post('/getOriginGameUrl', 'DGController@getOriginGameUrl');
    Route::post('/getFreeGameUrl', 'DGController@getFreeGameUrl');
    Route::post('/transaction', 'DGController@transaction');
    Route::post('/getPoint', 'DGController@getPoint');
});