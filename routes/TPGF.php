<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'TPGF','namespace' => 'Api'], function () {
	// Route::post('/testurl', 'TPGController@testurl');
	// Route::post('/api_test2', 'TPGController@apiTest'); //test
	// Route::post('/getGameToken', 'TPGController@getGameToken');
	// Route::post('/gameLauncher', 'TPGController@gameLauncher');	
	// Route::post('/fundTransfer', 'TPGController@fundTransfer');
	// Route::post('/getPlayerGameBalance', 'TPGController@getPlayerGameBalance');

	Route::post('/login', 'TPGController@getGameToken');
	Route::post('/getToken', 'TPGController@getGameToken');
	Route::post('/getFreeGameUrl', 'TPGController@getFreeGameUrl');
	Route::post('/getOriginGameUrl', 'TPGController@getOriginGameUrl');
	Route::post('/transaction', 'TPGController@fundTransfer');
	Route::post('/syncreport', 'TPGController@syncReport');
	Route::post('/getPoint', 'TPGController@getPlayerGameBalance');
});