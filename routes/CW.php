<?php
use App\Http\Controllers\Api\CWController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'CW', 'namespace' => 'Api'], function () {
	Route::post('/login', 'CWController@login');
	Route::post('/getToken', 'CWController@getToken');
	Route::get('/startToken', 'CWController@startToken');
	Route::post('/getFreeGameUrl', 'CWController@getFreeGameUrl');
	Route::post('/getGameUrl', 'CWController@getGameUrl');
	Route::post('/transaction', 'CWController@transaction');
	Route::post('/syncreport', 'CWController@syncreport');
	Route::post('/getPoint', 'CWController@getPoint');
});

