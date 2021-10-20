<?php

use App\Http\Controllers\Api\RTGController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'RTG','namespace' => 'Api'], function () {
    // Route::post('/test', 'RTGController@test01');
    // Route::post('/getToken', 'RTGController@getToken');
    // Route::post('/startToken', 'RTGController@startToken');
    // Route::post('/createUser', 'RTGController@createUser');
    // Route::post('/checkUser', 'RTGController@checkUser');
    // Route::post('/GetBalance', 'RTGController@GetBalance');

    Route::post('/login', 'RTGController@checkUser');
    Route::post('/getOriginGameUrl', 'RTGController@getOriginGameUrl');
    Route::post('/getFreeGameUrl', 'RTGController@getFreeGameUrl');
    Route::post('/transaction', 'RTGController@transaction');
    Route::post('/syncreport', 'RTGController@syncReport');
    Route::post('/getPoint', 'RTGController@GetBalance');
});