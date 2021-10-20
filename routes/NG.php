<?php
use App\Http\Controllers\Api\NGController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'NG', 'namespace' => 'Api'], function () {
	Route::post('/login', 'NGController@login');
	Route::post('/getToken', 'NGController@getToken');
	Route::get('/startToken', 'NGController@startToken');
	Route::post('/getFreeGameUrl', 'NGController@getFreeGameUrl');
	Route::post('/getGameUrl', 'NGController@getGameUrl');
	Route::post('/transaction', 'NGController@transaction');
	Route::post('/syncreport', 'NGController@syncreport');
	Route::post('/getPoint', 'NGController@getPoint');
});

