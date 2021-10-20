<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'GameService', 'middleware' => ['CheckLang']], function () {
    Route::get('/setlatestqishu', 'GameInfoController@setLatestQishu'); // 設定最新期數的redis資料
    Route::post('/toupdate', 'GameInfoController@toUpdate'); // 更新賽果與相關圖表的redis資料

    Route::post('/gamemenu', 'GameInfoController@gameMenu'); // 遊戲列表
    Route::post('/gameinfo', 'GameInfoController@gameInfo'); // 遊戲資訊
    Route::post('/gamelatestqishu', 'GameInfoController@gameLatestQishu'); // 取得遊戲最新期數
    Route::post('/gamewaittime', 'GameInfoController@gameWaitTime'); // 取得遊戲開始等待時間倒數

    Route::post('/laddergameresult', 'GameInfoController@ladderGameResult'); // 梯子-賽果
    Route::post('/ladderlongdragon', 'GameInfoController@ladderLongDragon'); // 梯子-長龍

    Route::post('/pandagameresult', 'GameInfoController@pandaGameResult'); // 熊貓-賽果
    Route::post('/pandasummark', 'GameInfoController@pandaSumMark'); // 熊貓-種類加總
    Route::post('/pandalongdragonmark', 'GameInfoController@pandaLongDragonMark'); // 熊貓-長龍(圖案)
    Route::post('/pandalongdragoncolor', 'GameInfoController@pandaLongDragonColor'); // 熊貓-長龍(顏色)

    Route::post('/bbhlnumber', 'GameInfoController@bbhlNumber'); // 高低-點數
    Route::post('/bbhlgameresult', 'GameInfoController@bbhlGameResult'); // 高低-賽果
    Route::post('/bbhllongdragon', 'GameInfoController@bbhlLongDragon'); // 高低-長龍
});
