<?php
	use Illuminate\Support\Facades\Route;

	//GASH
Route::group(['namespace' => 'Api', 'prefix' => '{Name}', 'where' => ['Name' => 'GGASH']], function () {
	Route::post('/transaction', 'GashController@transaction');
	Route::post('/request', 'GashController@request');
	Route::post('/lookat', 'GashController@lookat');
	Route::post('/returnUrl', 'GashController@returnUrl');
	Route::post('/settle', 'GashController@settle');
});