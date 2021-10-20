<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['namespace' => 'Admin'], function () {
    Route::get('/', 'LoginController@index');
    Route::get('/index', 'LoginController@index');
    Route::get('/changeLang', 'LoginController@changeLang');
    Route::post('/login', 'LoginController@login');
    Route::get('/logout', 'LoginController@logout');
    Route::get('/jjtest', 'LoginController@test');
});

// Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['CheckAdmin']], function () {
Route::group(['namespace' => 'Admin', 'middleware' => ['CheckAdmin']], function () {
    // system
    Route::get('/home', 'HomeController@index');
    Route::get('/top', 'HomeController@top');
    Route::get('/left', 'HomeController@left');
    Route::get('/main', 'HomeController@main');
    Route::get('/error', 'ErrorController@index');
    // home top cnt
    Route::post('/home/getCntData', 'HomeController@getCntData');
    // 
    Route::post('/home/getHomeReportGameBet', 'HomeController@getHomeReportGameBet');

    // Excel
    Route::get('/export_file/customer', 'ExcelController@customer');
    Route::get('/export_file/deposit', 'ExcelController@deposit');
    Route::get('/export_file/withdraw', 'ExcelController@withdraw');

    Route::group(['middleware' => ['RolePermission']], function () {
        //
        Route::get('/reset_pwd', 'ResetPwdController@index');
        Route::post('/reset_pwd/doResetPwd', 'ResetPwdController@doResetPwd');
        // Route::match(['get','post'], '/admin_list', 'AdminListController@index');

        // admin_list
        Route::get('/admin_list', 'AdminListController@index');
        Route::get('/admin_list/add', 'AdminListController@add');
        Route::post('/admin_list/doAdd', 'AdminListController@doAdd');
        Route::get('/admin_list/edit/{id}', 'AdminListController@edit');
        Route::post('/admin_list/doEdit/{id}', 'AdminListController@doEdit');
        Route::post('/admin_list/doDel/{id}', 'AdminListController@doDelete');

        // agent_list
        Route::get('/agent_list/{id?}', 'AgentListController@index');
        Route::get('/agent_list/all/{id?}', 'AgentListController@agent_list_all');
        Route::get('/agent_list/add/{id}', 'AgentListController@add');
        Route::post('/agent_list/doAdd/{id}', 'AgentListController@doAdd');
        Route::get('/agent_list/edit/{id}', 'AgentListController@edit');
        Route::post('/agent_list/doEdit/{id}', 'AgentListController@doEdit');
       
        Route::get('/agent_list/subaccount/{agent_id}', 'AgentListController@subaccount_index');
        Route::get('/agent_list/subaccount/add/{agent_id}', 'AgentListController@subaccount_add');
        Route::post('/agent_list/subaccount/doAdd/{agent_id}', 'AgentListController@subaccount_doAdd');
        Route::get('/agent_list/subaccount/edit/{agent_id}/{id}', 'AgentListController@subaccount_edit');
        Route::post('/agent_list/subaccount/doEdit/{agent_id}/{id}', 'AgentListController@subaccount_doEdit');

        Route::get('/agent_list/domain/{agent_id}', 'AgentDomainController@index');
        Route::get('/agent_list/domain/add/{agent_id}', 'AgentDomainController@add');
        Route::post('/agent_list/domain/doAdd/{agent_id}', 'AgentDomainController@doAdd');
        Route::get('/agent_list/domain/edit/{agent_id}/{id}', 'AgentDomainController@edit');
        Route::post('/agent_list/domain/doEdit/{agent_id}/{id}', 'AgentDomainController@doEdit');
        Route::post('/agent_list/domain/doDel/{agent_id}/{id}', 'AgentDomainController@doDel');

        // customer_list
        Route::get('/customer', 'CustomerController@index');
        Route::get('/customer/add', 'CustomerController@add');
        Route::post('/customer/doAdd', 'CustomerController@doAdd');
        Route::get('/customer/edit/{id}', 'CustomerController@edit');
        Route::post('/customer/doEdit/{id}', 'CustomerController@doEdit');
        Route::get('/customer/issueRecover', 'CustomerController@issueRecover');
        Route::get('/customer/issueRecover2', 'CustomerController@issueRecover2');
        Route::post('/customer/doIssueRecover', 'CustomerController@doIssueRecover');
        Route::post('/customer/doIssueRecover2', 'CustomerController@doIssueRecover2');
        Route::get('/customer/customerDetail', 'CustomerController@customerDetail');
        // Route::post('/customer/dayStatistics', 'CustomerController@dayStatistics');
        // Route::post('/customer/monthStatistics', 'CustomerController@monthStatistics');
        Route::get('/customer/cashRecord', 'CustomerController@cashRecord');
        Route::get('/customer/pointRecord', 'CustomerController@pointRecord');
        Route::get('/customer/dayStatistics', 'CustomerController@dayStatistics');
        Route::get('/customer/monthStatistics', 'CustomerController@monthStatistics');
        Route::get('/customer/questionRecord', 'CustomerController@questionRecord');
        Route::get('/customer/betRecord', 'CustomerController@betRecord');
        Route::post('/customer/getRecord', 'CustomerController@getRecord');
        Route::post('/customer/getUserRecord', 'CustomerController@getUserRecord');
        Route::post('/customer/questionRecordLList', 'CustomerController@questionRecordLList');
        Route::post('/customer/betRecordLList', 'CustomerController@betRecordLList');
        Route::post('/customer/sportDetail', 'CustomerController@sportDetail');
        Route::post('/customer/gameinfo', 'CustomerController@gameInfo');
        Route::get('/customer/site_message_add', 'SiteMessageController@add2');
        Route::post('/customer/site_message_doAdd', 'SiteMessageController@doAdd2');
        Route::post('/customer/doUpMemo', 'CustomerController@doUpMemo');
        Route::get('/customer/moneyCheck', 'CustomerController@moneyCheck');
        Route::get('/customer/questionList', 'CustomerController@questionList');



        // customer_register
        Route::get('/customer_register', 'CustomerRegisterController@index');
        Route::get('/customer_register/edit/{id}', 'CustomerRegisterController@edit');
        Route::post('/customer_register/doEdit/{id}', 'CustomerRegisterController@doEdit');

        // game vendor
        Route::get('/game_vendor', 'GameVendorController@index');
        Route::get('/game_vendor/edit/{id}', 'GameVendorController@edit');
        Route::post('/game_vendor/doEdit/{id}', 'GameVendorController@doEdit');
        Route::get('/game_vendor/log', 'GameVendorController@log');

        // game kind
        Route::get('/game_kind', 'GameKindController@index');
        Route::get('/game_kind/edit/{id}', 'GameKindController@edit');
        Route::post('/game_kind/doEdit/{id}', 'GameKindController@doEdit');
        Route::get('/game_kind/log', 'GameKindController@log');

        // game info
        Route::get('/game_info', 'GameInfoController@index');
        Route::get('/game_info/edit/{id}', 'GameInfoController@edit');
        Route::post('/game_info/doEdit/{id}', 'GameInfoController@doEdit');
        Route::get('/game_info/log', 'GameInfoController@log');

        // game limit
        Route::get('/game_limit', 'GameLimitController@index');
        Route::post('/game_limit/doEdit', 'GameLimitController@doEdit');

         // game fee
         Route::get('/game_fee', 'GameFeeController@index');
         Route::post('/game_fee/doEdit', 'GameFeeController@doEdit');
 

        // announcement
        Route::get('/announcement', 'AnnouncementController@index');
        Route::get('/announcement/add', 'AnnouncementController@add');
        Route::post('/announcement/doAdd', 'AnnouncementController@doAdd');
        Route::get('/announcement/edit/{id}', 'AnnouncementController@edit');
        Route::post('/announcement/doEdit/{id}', 'AnnouncementController@doEdit');

        // activity
        Route::get('/activity', 'ActivityController@index');
        Route::get('/activity/add', 'ActivityController@add');
        Route::post('/activity/doAdd', 'ActivityController@doAdd');
        Route::get('/activity/edit/{id}', 'ActivityController@edit');
        Route::post('/activity/doEdit/{id}', 'ActivityController@doEdit');

        // site_message_sample
        Route::get('/site_message_sample', 'SiteMessageSampleController@index');
        Route::get('/site_message_sample/add', 'SiteMessageSampleController@add');
        Route::post('/site_message_sample/doAdd', 'SiteMessageSampleController@doAdd');
        Route::post('/site_message_sample/doAdd2', 'SiteMessageSampleController@doAdd2');
        Route::get('/site_message_sample/edit/{id}', 'SiteMessageSampleController@edit');
        Route::post('/site_message_sample/doEdit/{id}', 'SiteMessageSampleController@doEdit');
        Route::post('/site_message_sample/doDel/{id}', 'SiteMessageSampleController@doDel');

        // site_message
        Route::get('/site_message', 'SiteMessageController@index');
        Route::get('/site_message/add', 'SiteMessageController@add');
        Route::post('/site_message/doAdd', 'SiteMessageController@doAdd');
        Route::get('/site_message/edit/{id}', 'SiteMessageController@edit');
        Route::post('/site_message/doEdit/{id}', 'SiteMessageController@doEdit');
        Route::post('/site_message/doDel/{id}', 'SiteMessageController@doDel');
        Route::post('/site_message/get_account_and_sample/{agent_id}', 'SiteMessageController@get_account_and_sample');

        // customer_question
        Route::get('/customer_question', 'CustomerQuestionController@index');
        Route::get('/customer_question/edit/{id}', 'CustomerQuestionController@edit');
        Route::post('/customer_question/doEdit/{id}', 'CustomerQuestionController@doEdit');
        Route::post('/customer_question/updateEdit/{id}', 'CustomerQuestionController@updateEdit');

        // customer_question_sample
        Route::get('/customer_question_sample', 'CustomerQuestionSampleController@edit');
        Route::post('/customer_question_sample/doEdit', 'CustomerQuestionSampleController@doEdit');

        // bank
        Route::get('/bank', 'BankController@index');
        Route::get('/bank/add', 'BankController@add');
        Route::post('/bank/doAdd', 'BankController@doAdd');
        Route::get('/bank/edit/{id}', 'BankController@edit');
        Route::post('/bank/doEdit/{id}', 'BankController@doEdit');

        // customer_deposit
        Route::get('/customer_deposit', 'CustomerDepositController@index');
        Route::post('/customer_deposit/doSend/{id}', 'CustomerDepositController@doSend');

        // customer_withdraw
        Route::get('/customer_withdraw', 'CustomerWithdrawController@index');
        Route::post('/customer_withdraw/doSend/{id}', 'CustomerWithdrawController@doSend');
        Route::post('/customer_withdraw/doBatchSend', 'CustomerWithdrawController@doBatchSend');

        // discount_setting
        Route::get('/discount_setting', 'DiscountSettingController@index');
        Route::get('/discount_setting/add', 'DiscountSettingController@add');
        Route::post('/discount_setting/doAdd', 'DiscountSettingController@doAdd');
        Route::get('/discount_setting/edit/{id}', 'DiscountSettingController@edit');
        Route::post('/discount_setting/doEdit/{id}', 'DiscountSettingController@doEdit');
        Route::post('/discount_setting/doDel/{id}', 'DiscountSettingController@doDel');

        // customer_discount
        Route::get('/customer_discount', 'CustomerDiscountController@index');
        Route::post('/customer_discount/doSend/{id}', 'CustomerDiscountController@doSend');

        // game_bet
        Route::get('/game_bet', 'GameBetController@index');
        Route::post('/game_bet/doCancelBet/{id}', 'GameBetController@doCancelBet');

        // game_sport_bet
        Route::get('/game_sport_bet', 'GameSportBetController@index');
        Route::post('/game_sport_bet/doCancelBet/{id}', 'GameSportBetController@doCancelBet');
        Route::get('/game_sport_bet/detail/{id}', 'GameSportBetController@detail');

        // game_bet_bbin
        Route::get('/game_bet_bbin', 'GameBetBBINController@index');

        // game_result power ladder
        Route::get('/gr_power_ladder', 'GrPowerLadderController@index');
        Route::post('/gr_power_ladder/doCancelBet', 'GrPowerLadderController@doCancelBet');
        Route::get('/gr_power_ladder/award/{id}', 'GrPowerLadderController@award');
        Route::post('/gr_power_ladder/doAward/{id}', 'GrPowerLadderController@doAward');
        Route::post('/gr_power_ladder/doSupplementAward', 'GrPowerLadderController@doSupplementAward');

        // game_result keno ladder
        Route::get('/gr_keno_ladder', 'GrKenoLadderController@index');
        Route::post('/gr_keno_ladder/doCancelBet', 'GrKenoLadderController@doCancelBet');
        Route::get('/gr_keno_ladder/award/{id}', 'GrKenoLadderController@award');
        Route::post('/gr_keno_ladder/doAward/{id}', 'GrKenoLadderController@doAward');
        Route::post('/gr_keno_ladder/doSupplementAward', 'GrKenoLadderController@doSupplementAward');

        // game_result power ball
        Route::get('/gr_power_ball', 'GrPowerBallController@index');
        Route::post('/gr_power_ball/doCancelBet', 'GrPowerBallController@doCancelBet');
        Route::get('/gr_power_ball/award/{id}', 'GrPowerBallController@award');
        Route::post('/gr_power_ball/doAward/{id}', 'GrPowerBallController@doAward');
        Route::post('/gr_power_ball/doSupplementAward', 'GrPowerBallController@doSupplementAward');

        // game_result lotus odd_even
        Route::get('/gr_lotus_odd_even', 'GrLotusOddEvenController@index');
        Route::post('/gr_lotus_odd_even/doCancelBet', 'GrLotusOddEvenController@doCancelBet');
        Route::get('/gr_lotus_odd_even/award/{id}', 'GrLotusOddEvenController@award');
        Route::post('/gr_lotus_odd_even/doAward/{id}', 'GrLotusOddEvenController@doAward');
        Route::post('/gr_lotus_odd_even/doSupplementAward', 'GrLotusOddEvenController@doSupplementAward');

        // game_result lotus dragon_tiger
        Route::get('/gr_lotus_dragon_tiger', 'GrLotusDragonTigerController@index');
        Route::post('/gr_lotus_dragon_tiger/doCancelBet', 'GrLotusDragonTigerController@doCancelBet');

        // game_result lotus dragon_tiger3
        Route::get('/gr_lotus_dragon_tiger_three', 'GrLotusDragonTigerThreeController@index');
        Route::post('/gr_lotus_dragon_tiger_three/doCancelBet', 'GrLotusDragonTigerThreeController@doCancelBet');

        // game_result lotus baccarat
        // Route::get('/gr_lotus_baccarat/{game_type}', 'GrLotusBaccaratController@index');
        // Route::post('/gr_lotus_baccarat/{game_type}/doCancelBet', 'GrLotusBaccaratController@doCancelBet');
        // Route::get('/gr_lotus_baccarat/{game_type}/award/{id}', 'GrLotusBaccaratController@award');
        // Route::post('/gr_lotus_baccarat/{game_type}/doAward/{id}', 'GrLotusBaccaratController@doAward');
        // Route::post('/gr_lotus_baccarat/{game_type}/doSupplementAward', 'GrLotusBaccaratController@doSupplementAward');

        // game_result lotus ladder
        Route::get('/gr_lotus_ladder', 'GrLotusLadderController@index');
        Route::post('/gr_lotus_ladder/doCancelBet', 'GrLotusLadderController@doCancelBet');

        // game_result esb ladder
        Route::get('/gr_esb_ladder', 'GrEsbLadderController@index');
        Route::post('/gr_esb_ladder/doCancelBet', 'GrEsbLadderController@doCancelBet');
        Route::get('/gr_esb_ladder/award/{id}', 'GrEsbLadderController@award');
        Route::post('/gr_esb_ladder/doAward/{id}', 'GrEsbLadderController@doAward');
        Route::post('/gr_esb_ladder/doSupplementAward', 'GrEsbLadderController@doSupplementAward');

        // game_result esb panda
        Route::get('/gr_esb_panda', 'GrEsbPandaController@index');
        Route::post('/gr_esb_panda/doCancelBet', 'GrEsbPandaController@doCancelBet');
        Route::get('/gr_esb_panda/award/{id}', 'GrEsbPandaController@award');
        Route::post('/gr_esb_panda/doAward/{id}', 'GrEsbPandaController@doAward');
        Route::post('/gr_esb_panda/doSupplementAward', 'GrEsbPandaController@doSupplementAward');

        // game_result ladder 1006
        Route::get('/gr_ladder_1006', 'GrLadder1006Controller@index');
        Route::post('/gr_ladder_1006/doCancelBet', 'GrLadder1006Controller@doCancelBet');
        Route::get('/gr_ladder_1006/award/{id}', 'GrLadder1006Controller@award');
        Route::post('/gr_ladder_1006/doAward/{id}', 'GrLadder1006Controller@doAward');
        Route::post('/gr_ladder_1006/doSupplementAward', 'GrLadder1006Controller@doSupplementAward');

        // game_result ladder 1007
        Route::get('/gr_ladder_1007', 'GrLadder1007Controller@index');
        Route::post('/gr_ladder_1007/doCancelBet', 'GrLadder1007Controller@doCancelBet');
        Route::get('/gr_ladder_1007/award/{id}', 'GrLadder1007Controller@award');
        Route::post('/gr_ladder_1007/doAward/{id}', 'GrLadder1007Controller@doAward');
        Route::post('/gr_ladder_1007/doSupplementAward', 'GrLadder1007Controller@doSupplementAward');

        // game_result esb bbhl
        Route::get('/gr_esb_bbhl', 'GrEsbBbhlController@index');
        Route::post('/gr_esb_bbhl/doCancelBet', 'GrEsbBbhlController@doCancelBet');
        Route::get('/gr_esb_bbhl/award/{id}', 'GrEsbBbhlController@award');
        Route::post('/gr_esb_bbhl/doAward/{id}', 'GrEsbBbhlController@doAward');
        Route::post('/gr_esb_bbhl/doSupplementAward', 'GrEsbBbhlController@doSupplementAward');

        // customer report
        Route::get('/customer_report', 'CustomerReportController@index');

        // report_daily_balance
        Route::get('/report_daily_balance', 'ReportDailyBalanceController@index');

        // report_monthly_balance
        Route::get('/report_monthly_balance', 'ReportMonthlyBalanceController@index');

        // report_daily_game_bet
        Route::get('/report_daily_game_bet', 'ReportDailyGameBetController@index');

        // report_customer_count
        Route::get('/report_customer_count', 'ReportCustomerCountController@index');

        // report_monthly_game_bet
        Route::get('/report_monthly_game_bet', 'ReportMonthlyGameBetController@index');

        // report_detail
        Route::get('/report_daily_balance/agentdetail', 'ReportDetailController@getAgentDetail'); // 代理細報
        Route::get('/report_daily_balance/memberdetail', 'ReportDetailController@getMemberDetail'); // 會員細報
        Route::get('/report_daily_balance/getDeposit', 'ReportDetailController@getDeposit'); // 入款
        Route::get('/report_daily_balance/getWithdraw', 'ReportDetailController@getWithdraw'); // 出款
        Route::get('/report_daily_balance/getBetMoney', 'ReportDetailController@getBetMoney'); // 下注額

        // report_game_bet_detail
        Route::get('/report_daily_game_bet/agentdetail', 'ReportDetailGameBetController@getAgentDetail'); // 代理細報
        Route::get('/report_daily_game_bet/memberdetail', 'ReportDetailGameBetController@getMemberDetail'); // 會員細報

        // sport_market
        Route::get('/sport_market', 'SportMarketController@index');
        Route::get('/sport_market/award/{id}', 'SportMarketController@award');
        Route::post('/sport_market/doAward/{id}', 'SportMarketController@doAward');
        Route::post('/sport_market/doSupplementAward', 'SportMarketController@doSupplementAward');
        Route::get('/sport_market/getLeagueList', 'SportMarketController@getLeagueList');
        Route::post('/sport_market/doCancelMarket', 'SportMarketController@doCancelMarket');
        Route::get('/sport_market/add', 'SportMarketController@add');
        Route::post('/sport_market/doAdd', 'SportMarketController@doAdd');
        Route::get('/sport_market/getTeamList', 'SportMarketController@getTeamList');
        Route::get('/sport_market/odds_add/{id}', 'SportMarketController@oddsAdd');
        Route::post('/sport_market/odds_add/{id}', 'SportMarketController@oddsAdd');
        Route::post('/sport_market/odds_do_add/{market_id}', 'SportMarketController@oddsDoAdd');
        Route::get('/sport_market/odds_setting/{id}', 'SportMarketController@oddsSetting');
        Route::post('/sport_market/doSetting/{id}', 'SportMarketController@doSetting');
        Route::get('/sport_market/auto_odds_setting/{id}', 'SportMarketController@autoOddsSetting');
        Route::post('/sport_market/autoDoSetting/{id}', 'SportMarketController@autoDoSetting');
        Route::get('/sport_market/manual_odds_setting_all', 'SportMarketController@manualSettingAll');
        Route::post('/sport_market/manualDoSettingAll', 'SportMarketController@manualDoSettingAll');

        // sport_odds
        Route::get('/sport_odds', 'SportOddsController@index');
        Route::get('/sport_odds/edit/{id}', 'SportOddsController@edit');
        Route::post('/sport_odds/doEdit/{id}', 'SportOddsController@doEdit');
        Route::get('/sport_odds/getLeagueList', 'SportOddsController@getLeagueList');

        // sport_config_odds
        Route::get('/sport_config_odds', 'SportConfigOddsController@index');
        Route::get('/sport_config_odds/add', 'SportConfigOddsController@add');
        Route::post('/sport_config_odds/doAdd', 'SportConfigOddsController@doAdd');
        Route::get('/sport_config_odds/edit/{id}', 'SportConfigOddsController@edit');
        Route::post('/sport_config_odds/doEdit/{id}', 'SportConfigOddsController@doEdit');
        Route::post('/sport_config_odds/doDel/{id}', 'SportConfigOddsController@doDel');

        // sport_config_odds_auto_all
        Route::get('/sport_config_odds_auto_all', 'SportConfigOddsAutoController@index');
        Route::post('/sport_config_odds_auto_all/doEdit/{id}', 'SportConfigOddsAutoController@doEdit');

        // sport_parlays_discount
        Route::get('/sport_parlays_discount', 'SportParlaysDiscountController@index');
        Route::post('/sport_parlays_discount/doEdit/{id}', 'SportParlaysDiscountController@doEdit');

        // sport_league
        Route::get('/sport_league', 'SportLeagueController@index');
        Route::get('/sport_league/add', 'SportLeagueController@add');
        Route::post('/sport_league/doAdd', 'SportLeagueController@doAdd');
        Route::get('/sport_league/edit/{id}', 'SportLeagueController@edit');
        Route::post('/sport_league/doEdit/{id}', 'SportLeagueController@doEdit');
        Route::post('/sport_league/doDel/{id}', 'SportLeagueController@doDel');

        // sport_ball_team
        Route::get('/sport_ball_team', 'SportBallTeamController@index');
        Route::get('/sport_ball_team/getLeagueList', 'SportBallTeamController@getLeagueList');
        Route::get('/sport_ball_team/edit/{id}', 'SportBallTeamController@edit');
        Route::post('/sport_ball_team/doEdit/{id}', 'SportBallTeamController@doEdit');
        Route::get('/sport_ball_team/add', 'SportBallTeamController@add');
        Route::post('/sport_ball_team/doAdd', 'SportBallTeamController@doAdd');
        Route::post('/sport_ball_team/doDel/{id}', 'SportBallTeamController@doDel');

        // deposit_feedback
        Route::get('/deposit_feedback', 'DepositFeedbackController@index');
        Route::post('/deposit_feedback/doEdit', 'DepositFeedbackController@doEdit');

        Route::get('get_chart_data', 'HomeController@GetChartData');
    });
});
