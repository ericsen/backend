<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'AMEBA','namespace' => 'Api'], function () {
	// Route::get('/test', 'AMEBAController@test');
	// Route::post('/getGameHttp', 'AMEBAController@getGameHttp');
	// Route::post('/getDemoGame', 'AMEBAController@getDemoGame');
	// Route::get('/getGameList', 'AMEBAController@getGameList');
	// Route::get('/deposit', 'AMEBAController@deposit');
	// Route::post('/fundTransfer', 'AMEBAController@fundTransfer');
	// Route::post('/getBalance', 'AMEBAController@getBalance');

	Route::post('/login', function(){
		return [
			'code' => 200,
			'message' => 'OK',
			'data' => ['token' => ''],
		];
	});
	Route::post('/getFreeGameUrl', 'AMEBAController@getDemoGame');
	Route::post('/getOriginGameUrl', 'AMEBAController@getGameHttp');
	Route::post('/transaction', 'AMEBAController@fundTransfer');
	Route::post('/syncreport', 'AMEBAController@syncReport');
	Route::post('/getPoint', 'AMEBAController@getBalance');
});