<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['namespace' => 'Api'], function () {
	
    Route::post('/izone', 'GameController@test');
	
    Route::get('/api_test', 'ApiTestController@apiTest'); // 會員註冊
    Route::match(['get', 'post'], '/qishu_test', 'ApiTestController@qishuTest'); // 會員註冊
    Route::post('/gamekindlist', 'GameController@gamekindlist');
    Route::post('/gamelistegt', 'GameController@gamelistegt'); //egt gamelist
    Route::post('/gamekindbrandlist', 'GameController@gamekindbrandlist'); // 
    Route::post('/list', 'GameController@list'); // 
    Route::post('/register', 'RegisterController@Register'); // 會員註冊
    Route::post('/registercolumn', 'RegisterController@RegisterColumn'); // 會員註冊欄位
    Route::post('/bulletinlist', 'AnnouncementController@getAnnouncement'); // 公告
    Route::post('/eventlist', 'ActivityController@getActivityList'); // 優惠活動列表
    Route::get('/token', 'TokenController@token'); // 登入
    Route::post('/gamelist', 'GameController@gamelist'); //
    Route::post('/gamelistegtkind', 'GameController@gamelistegtkind'); //egtkind
    Route::post('/enter_game_free', 'GameController@enter_game_free');
	Route::post('/full_game_list', 'GameController@game_full_game_list');
    Route::post('/get_machine_sub_category_list', 'GameController@getMachineSubCategoryList');
    Route::post('/get_machine_game_list', 'GameController@getMachineGameList');
    // 代理遊戲相關
    
	// 限制實機最大遊玩數
    Route::post('/is_arrival_max_client_count', 'GameController@isArrivalMaxClientCount');
    Route::post('/checkout', 'OrderController@checkout');

});
Route::group(['namespace' => 'Api', 'middleware' => ['CheckLang']], function () {
    Route::post('/register', 'RegisterController@Register'); // 會員註冊
    Route::post('/login', 'LoginController@Login'); // 登入
    Route::post('/logingame', 'LoginController@LoginGame'); // 登入
    Route::post('/loginForMachineFreeGame', 'LoginController@login_for_machine_free_game'); // 登入
    Route::post('/clock', 'LoginController@syncClock'); // syncClock
    
    Route::post('/checkinvitationcode', 'InvitationCodeController@checkInvitationCode'); // 驗證邀請碼
    Route::post('/mobileoperator', 'MyPageController@getMobileOperator'); // 取得有開啟電信資料
    Route::post('/banklist', 'TransactionController@bankList'); // 銀行列表
    //
    Route::match(['get', 'post'], '/spider_test', 'SpiderPyController@test'); // 測試
    Route::match(['get', 'post'], '/admin_do_award', 'SpiderPyController@adminDoAward'); // 手動派獎
    Route::match(['get', 'post'], '/spider_py', 'SpiderPyController@doSpiderPy'); // 
    Route::match(['get', 'post'], '/do_self_open', 'SpiderPyController@doSelfOpen'); // 自開彩開獎
    Route::match(['get', 'post'], '/get_lotus_lose_qishu', 'SpiderPyController@getLotusLoseQishu'); // 補開獎
    Route::match(['get', 'post'], '/set_bet_award', 'SpiderPyController@setBetAward'); // 手動派獎
    Route::post('/set_lotus_qishu', 'SpiderPyController@setLotusQishu'); // 產出每日期數

    // redis
    Route::get('/redis_test', 'RedisActionController@test'); // redis
    Route::get('/redis_all_keys', 'RedisActionController@get_all_keys'); // redis
    Route::get('/redis_get_values', 'RedisActionController@get_values'); // redis
    Route::get('/redis_setAdminHomeCnt', 'RedisActionController@setAdminHomeCnt'); // redis
    Route::get('/setMissQishu', 'RedisActionController@setMissQishu'); // redis
    // report-game_bet
    Route::get('/crontab_report/set_today_game_bet', 'CrontabReportController@set_today_game_bet');
    Route::get('/crontab_report/set_yesterday_game_bet', 'CrontabReportController@set_yesterday_game_bet');
    Route::get('/crontab_report/set_daily_game_bet', 'CrontabReportController@set_daily_game_bet');
    Route::get('/crontab_report/set_monthly_game_bet', 'CrontabReportController@set_monthly_game_bet');
    // report-balance
    Route::get('/crontab_report/set_today_balance', 'CrontabReportController@set_today_balance');
    Route::get('/crontab_report/set_yesterday_balance', 'CrontabReportController@set_yesterday_balance');
    Route::get('/crontab_report/set_daily_balance', 'CrontabReportController@set_daily_balance');
    Route::get('/crontab_report/set_monthly_balance', 'CrontabReportController@set_monthly_balance');


    Route::get('/crontab_report/set_sport_result', 'CrontabSportController@set_sport_result');  // 撈體育賽果
    Route::get('/crontab_report/set_sport_odds', 'CrontabSportController@set_sport_odds');    // 撈體育賠率
    Route::get('/crontab_report/set_sport_live_data', 'CrontabSportController@set_sport_live_data');    // 撈體育賽事結果
    Route::get('/crontab_report/set_ball_team', 'CrontabSportController@set_ball_team');    // 取得球隊資料
    Route::get('/crontab_report/set_league', 'CrontabSportController@set_league');    // 取得聯盟資料
    Route::get('/crontab_report/modify_record', 'CrontabSportController@modify_record');    // 取得比赛删除&修改时间记录 

    //派獎
    Route::get('/award/selfsport', 'SelfSportAwardController@selfSportAward');  // 體育派獎

    //損益
    Route::post('/setprofitloss', 'TransactionController@setprofitloss');  // 損益

    Route::group(['middleware' => ['FrontPermission']], function () {
		
        Route::post('/signout', 'LoginController@signout'); // 登出

        Route::post('/announcement', 'AnnouncementController@getAnnouncement'); // 公告

        Route::post('/activity', 'ActivityController@getActivityList'); // 優惠活動列表

		//娛樂城錢錢進EGT
		Route::post('/transfer_to_egt', 'TransferController@transfer_to_egt');

        // 站內信
		Route::post('/sitemessagenew', 'SiteMessageController@send_new_message'); // 發新信給站方
        Route::post('/sitemessagetotal', 'SiteMessageController@getSiteMessageTotalFront'); // 取得未讀站內信數量
        Route::post('/sitemessagelist/{type}', 'SiteMessageController@getSiteMessageList'); // 取得站內信列表
        Route::post('/sitemessagestatus', 'SiteMessageController@upMessageStatus'); // 更新站內信狀態為已讀
        Route::post('/sitemessagedelete', 'SiteMessageController@deleteMessage'); // 刪除站內信

        // 客服中心
        Route::post('/customerservicelist', 'CustomerServiceController@customerServiceList'); // 客服中心列表
        Route::post('/sendcustomerservice', 'CustomerServiceController@sendCustomerService'); // 聯繫客服
        Route::post('/customerservicedelete', 'CustomerServiceController@customerServiceDelete'); // 刪除客服中心信件
        Route::post('/senddeposit', 'CustomerServiceController@sendDeposit'); // 詢問入款帳號

        // 會員中心
        Route::post( '/profile', 'MyPageController@profile'); // 會員資料
        Route::get( '/userfile', 'MyPageController@userfile'); // 會員TOKEN
        Route::post('/rewardpoint', 'MyPageController@rewardPoint'); // 點數記錄
        Route::post('/cashpoint', 'MyPageController@cashPoint'); // 現金記錄
        Route::post('/cashdelete', 'MyPageController@delCashAllData'); // 刪除現金記錄所有資料
        Route::post('/preferential', 'MyPageController@preferentialList'); // 取得優惠/輪轉券列表
        Route::post('/money', 'MyPageController@getUserMoney'); // 取得會員餘額
        Route::post('/transaction_record', 'MyPageController@transaction_record'); // 取得交易紀錄
        Route::post('/my_wallet_brand_list', 'GameController@myWalletBrandList');

        // 存款/提款
		Route::post('/deposit/demo', 'DepositController@demo'); // 綠界儲值demo
		Route::post('/deposit/notic', 'DepositController@notic'); // 綠界儲值demo
        Route::post('/deposit_type_list', 'TransactionController@deposit_type_list'); // 儲值列表
        Route::post('/deposit_bank_list', 'TransactionController@deposit_bank_list'); // 銀行列表
        Route::post('/deposit', 'TransactionController@deposit'); // 存款
        Route::post('/depositg', 'TransactionController@depositg'); // 存款g
        Route::post('/withdrawal', 'TransactionController@withdrawal'); // 提款
        Route::post('/depositlist', 'TransactionController@depositList'); // 存款單列表
        Route::post('/withdrawallist', 'TransactionController@withdrawalList'); // 提款單列表
        Route::post('/deldeposit', 'TransactionController@deldeposit'); // 刪除存款單
        Route::post('/transform', 'TransactionController@transform'); // 點數轉現金
        Route::post('/delwithdrawal', 'TransactionController@delWithdrawal'); // 刪除提款單

        // 下注相關
        Route::post('/bet', 'BetController@bet'); // 下注
        Route::post('/betlist', 'BetController@betList'); // 下注列表
        Route::post('/betlistdata', 'BetListDataController@betListdata'); // 下注列表(new)
        Route::post('/deletebet', 'BetController@deleteBet'); // 刪除下注單
        Route::post('/gamelist1', 'BetController@gameList'); // 遊戲列表
        Route::post('/gameinfo', 'BetController@gameInfo'); // 遊戲資訊
        Route::post('/gameresult', 'BetController@gameResult'); // 賽果
        Route::post('/esbladdergameresult', 'BetController@esbLadderGameResult'); // ESB梯子-賽果
        Route::post('/esbladderlongdragon', 'BetController@esbLadderLongDragon'); // ESB梯子-長龍
        Route::post('/esbpandagameresult', 'BetController@esbPandaGameResult'); // ESB熊貓-賽果
        Route::post('/esbpandasummark', 'BetController@esbPandaSumMark'); // ESB熊貓-種類加總
        Route::post('/esbpandalongdragonmark', 'BetController@esbPandaLongDragonMark'); // ESB熊貓-長龍(圖案)
        Route::post('/esbpandalongdragoncolor', 'BetController@esbPandaLongDragonColor'); // ESB熊貓-長龍(顏色)
        Route::post('/gamemenu', 'BetController@gameMenu'); // 遊戲列表

        // 遊戲資訊
        Route::post('/enter_game', 'GameController@enter_game'); //進入遊戲
        Route::post('/gameinfodata', 'GameInfoController@gameInfo'); // 遊戲資訊
        Route::post('/gamemenudata', 'GameInfoController@gameMenu'); // 遊戲列表
        Route::post('/gamewanfadtldata', 'GameInfoController@gameWanfaDtl'); // 遊戲玩法

        // 輪轉券數量
        Route::post('/rotation', 'DiscountController@discountData'); // 輪轉盤資料
        Route::post('/hasdiscount', 'DiscountController@hasDiscount'); // 玩輪轉盤
        Route::post('/playrotation', 'DiscountController@playDiscount'); // 玩輪轉盤
        Route::post('/preferentialtotal', 'DiscountController@getUserDiscountTotal'); // 輪轉券數量


        // 蓮花遊戲
        Route::post('/gameinfolo', 'LotusgmGameController@getLotusgmGameInfo'); // 遊戲資訊
        Route::post('/betlo', 'LotusgmGameController@betLotusgm'); // 蓮花遊戲下注

        // 自營體育
        Route::post('/sportmenu', 'SelfSportController@sportMenu'); // 體育列表
        Route::post('/sportinfo', 'SelfSportController@sportInfo'); // 體育遊戲內容資訊
        Route::post('/sportbet', 'SelfSportController@sportBet'); // 體育遊戲下注
        Route::post('/sportparlaysdiscount', 'SelfSportController@sportParlaysDiscount'); // 體育串關優惠
        Route::post('/betconfig', 'SelfSportController@betConfig'); // 下注設定
    });
	
});