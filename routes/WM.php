<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'WM','namespace' => 'Api'], function () {
    Route::post('/testurl', 'WMController@apiTest');

    Route::post('/login', 'WMController@login');
    Route::post('/getOriginGameUrl', 'WMController@getOriginGameUrl');
    Route::post('/getFreeGameUrl', function() {
        return [
			'code' => 200,
			'message' => 'OK',
			'data' => ['freeGameUrl' => ''],
		];
    });
    Route::post('/transaction', 'WMController@transaction');
    Route::post('/syncreport', 'WMController@syncReport');
    Route::post('/getPoint', 'WMController@getPoint');
});