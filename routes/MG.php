<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'MG', 'namespace' => 'Api'], function () {
	Route::post('/login', 'MGController@login');
	Route::post('/getToken', 'MGController@getToken');
	Route::post('/getFreeGameUrl', 'MGController@getFreeGameUrl');
	Route::post('/getOriginGameUrl', 'MGController@getOriginGameUrl');
	Route::post('/transaction', 'MGController@transaction');
	Route::post('/syncreport', 'MGController@syncreport');
	Route::post('/getPoint', 'MGController@getPoint');
});