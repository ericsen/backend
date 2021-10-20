<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

// 9K
// Route::resource('/9K', 'NineKController');
Route::group(['prefix' => '9K', 'namespace' => 'Api'], function () {
    Route::post('/createUser', 'NineKController@createUser');
    Route::post('/changePWD', 'NineKController@changePassword');
    Route::post('/login', 'NineKController@login');
    Route::post('/getOriginGameUrl', 'NineKController@getGameUrl');
    Route::post('/getPoint', 'NineKController@getBalance');
    Route::post('/transaction', 'NineKController@transaction');
    Route::post('/checkTransaction', 'NineKController@checkTransaction');
    Route::post('/syncReport', 'NineKController@syncReport');
});

// KK
Route::group(['prefix' => 'KK', 'namespace' => 'Api'], function () {
    Route::post('/createUser', 'KKController@createUser');
    Route::post('/login', 'KKController@login');
    Route::post('/kickPlayer', 'KKController@kickPlayer');
    Route::post('/getOriginGameUrl', 'KKController@getGameUrl');
    Route::post('/getPoint', 'KKController@getBalance');
    Route::post('/transaction', 'KKController@transaction');
    Route::post('/syncReport', 'KKController@syncReport');
});

// VSL
Route::group(['prefix' => 'VSL', 'namespace' => 'Api'], function () {
    Route::post('/createUser', 'VSLController@createUser');
    Route::post('/setAllowBet', 'VSLController@setAllowBet');
    Route::post('/login', 'VSLController@login');
    Route::post('/kickPlayer', 'VSLController@kickPlayer');
    Route::post('/getOriginGameUrl', 'VSLController@getGameUrl');
    Route::post('/getPoint', 'VSLController@getBalance');
    Route::post('/transaction', 'VSLController@transaction');
    Route::post('/syncReport', 'VSLController@syncReport');
});

// CSN實機遊戲
Route::group(['namespace' => 'Api', 'prefix' => '{gameName}', 'where' => ['gameName' => 'EGT|FFT|FISH']], function () {
    Route::post('/login', 'RMGController@login');
    Route::post('/list', 'RMGController@list');
    Route::post('/getOriginGameUrl', 'RMGController@getGameUrl')->defaults('urlParamName', 'originGameUrl');
    Route::post('/getFreeGameUrl', 'RMGController@getGameUrl')->defaults('urlParamName', 'freeGameUrl');
    Route::post('/getInfo', 'RMGController@getInfo');
    Route::post('/getProfile', 'RMGController@getProfile');
    Route::post('/setStatus', 'RMGController@setStatus');
    Route::post('/transaction', 'RMGController@transaction');
    Route::post('/exchange', 'RMGController@exchange');
    Route::post('/syncReport', 'RMGController@syncReport');
});
