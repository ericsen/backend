<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'BAC', 'namespace' => 'Api'], function () {
    Route::post('/test', 'BACController@test');

    Route::post('/login', function(){
		return [
			'code' => 200,
			'message' => 'OK',
			'data' => ['token' => ''],
		];
	});
    Route::post('/getOriginGameUrl', 'BACController@getOriginGameUrl');
    Route::post('/getFreeGameUrl', function() {
        return [
			'code' => 200,
			'message' => 'OK',
			'data' => ['freeGameUrl' => ''],
		];
    });
    Route::post('/transaction', 'BACController@transaction');
    Route::post('/syncreport', 'BACController@syncReport');
    Route::post('/getPoint', 'BACController@getPoint');
});
