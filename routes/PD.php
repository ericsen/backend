<?php
use App\Http\Controllers\Api\PDController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'PD', 'namespace' => 'Api'], function () {
	Route::post('/login', 'PDController@login');
	Route::post('/getToken', 'PDController@getToken');
	Route::get('/startToken', 'PDController@startToken');
	Route::post('/getFreeGameUrl', 'PDController@getFreeGameUrl');
	Route::post('/getGameUrl', 'PDController@getGameUrl');
	Route::post('/transaction', 'PDController@transaction');
	Route::post('/syncreport', 'PDController@syncreport');
	Route::post('/getPoint', 'PDController@getPoint');
});

