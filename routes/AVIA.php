<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'AVIA', 'namespace' => 'Api'], function () {
	Route::post('/login', 'AVIAController@login');
	Route::post('/getToken', 'AVIAController@getToken');
	Route::post('/getFreeGameUrl', 'AVIAController@getFreeGameUrl');
	Route::post('/getOriginGameUrl', 'AVIAController@getOriginGameUrl');
	Route::post('/transaction', 'AVIAController@transaction');
	Route::post('/syncreport', 'AVIAController@syncreport');
	Route::post('/getPoint', 'AVIAController@getPoint');
});