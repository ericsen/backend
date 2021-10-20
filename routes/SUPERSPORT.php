<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'SUPER', 'namespace' => 'Api'], function () {
	Route::post('/login', 'SuperSportController@login');
	Route::post('/getToken', 'SuperSportController@getToken');
	Route::post('/getFreeGameUrl', 'SuperSportController@getFreeGameUrl');
	Route::post('/getOriginGameUrl', 'SuperSportController@getOriginGameUrl');
	Route::post('/transaction', 'SuperSportController@transaction');
	Route::post('/syncreport', 'SuperSportController@syncreport');
	Route::post('/getPoint', 'SuperSportController@getPoint');
});