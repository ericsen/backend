<?php
use App\Http\Controllers\Api\ICGFController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'ICGF', 'namespace' => 'Api'], function () {
	Route::post('/login', 'ICGFController@login');
	Route::post('/getToken', 'ICGFController@getToken');
	Route::post('/getFreeGameUrl', 'ICGFController@getFreeGameUrl');
	Route::post('/getGameUrl', 'ICGFController@getGameUrl');
	Route::post('/transaction', 'ICGFController@transaction');
	Route::post('/syncreport', 'ICGFController@syncreport');
	Route::post('/getPoint', 'ICGFController@getPoint');
});