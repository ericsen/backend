<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'resetpwd','namespace' => 'Api'], function () {
    Route::post('reset','ResetPwdController@reset');
});