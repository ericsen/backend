<?php
use App\Http\Controllers\Api\JCController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'JC', 'namespace' => 'Api'], function () {
	Route::post('/login', 'JCController@login');
	Route::post('/getToken', 'JCController@getToken');
	Route::get('/startToken', 'JCController@startToken');
	Route::post('/getFreeGameUrl', 'JCController@getFreeGameUrl');
	Route::post('/getGameUrl', 'JCController@getGameUrl');
	Route::post('/getInfo', 'JCController@getInfo');
	Route::post('/setStatus', 'JCController@setStatus');
	Route::post('/transaction', 'JCController@transaction');
	Route::post('/syncreport', 'JCController@syncreport');
	Route::post('/getPoint', 'JCController@getPoint');
});

