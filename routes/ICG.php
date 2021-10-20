<?php
use App\Http\Controllers\Api\ICGController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ICG', 'namespace' => 'Api'], function () {
	Route::post('/login', 'ICGController@login');
	Route::post('/getToken', 'ICGController@getToken');
	Route::post('/getFreeGameUrl', 'ICGController@getFreeGameUrl');
	Route::post('/getGameUrl', 'ICGController@getGameUrl');
	Route::post('/transaction', 'ICGController@transaction');
	Route::post('/syncreport', 'ICGController@syncreport');
	Route::post('/getPoint', 'ICGController@getPoint');
});

