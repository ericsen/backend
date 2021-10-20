<?php

namespace App\Payment\Gash;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\Hg_customer_flow;
use Illuminate\Support\Facades\Redis;
use SoapClient;
// use App\Payment\Gash\Trans;
include("Common_2.php");
class GashPaymentMain
{
	public function Transaction(string $token) 
	{
		if ($token=='error') return '缺乏身份驗證資訊，請重新登入。';

		//引用
		// error_reporting(E_ALL);
		// ini_set('display_errors', 1);
		$result = DB::table('sessions')
				->join('hg_customer', 'sessions.user_id', '=', 'hg_customer.id')
				->where('sessions.payload', $token)
				->select('hg_customer.id', 'hg_customer.account')
				->first();

		abort_unless($result, 401, '[CSN] Token無效');

		// return $result->account;
		// return [$result, $result->toSql()];
		$p = config('Gash.password.p');
		$k = config('Gash.password.k');
		$v = config('Gash.password.v');
		//商家密碼
		// $p = "E8yHPLkMJWD";
		// // 商家密鑰 I
		// $k = "tUjNA9FK9ApUM2rIyEmZV8XxQ6yqkVT/";
		// // 商家密鑰 II
		// $v = "2y3hadbaggk=";
		// Redis::set('password', $p);


		$trans = new Trans(null);	
		// 交易訊息代碼
		$trans->nodes["MSG_TYPE"] = "0100"; 
		// 交易處理代碼 
		$trans->nodes["PCODE"] = "300000"; // 一般交易請使用 300000, 月租交易請使用 303000, 月租退租請使用 330000
		// 商家遊戲代碼
		$trans->nodes["CID"] = "C009160001429";
		// 商家訂單編號
		$trans->nodes["COID"] = "CP" . date("YmdHis");
		// 幣別 ISO Alpha Code
		$trans->nodes["CUID"] = "TWD";
		// 付款代收業者代碼 
		$trans->nodes["PAID"] = "COPGAM05"; // 此範例為台灣大哥大一般型, 月租交易請使用 TELTCC02
		// 交易金額
		$trans->nodes["AMOUNT"] = "0";
		// 商家接收交易結果網址
		$trans->nodes["RETURN_URL"] = "http://".$_SERVER['HTTP_HOST']."/api/GGASH/returnUrl";
		//http://localhost:20080/PHP/SampleCode_Transaction.php
		// 是否指定付款代收業者
		$trans->nodes["ORDER_TYPE"] = "M"; // 請固定填 M
		// 交易備註 ( 此為選填 )
		$trans->nodes["MEMO"] = "測試交易"; // 請填寫此筆交易之備註內容
		$trans->nodes["MID"] = "M1000916"; 
		// 樂點卡ERP商品代碼 ( 此為選填 )
		// $trans->nodes["ERP_ID"] = "J990001";
		// 商家商品名稱 ( 此為選填 )
		$trans->nodes["PRODUCT_NAME"] = "測試商品名稱";
		// 商家商品代碼 ( 此為選填 )
		$trans->nodes["PRODUCT_ID"] = "TEST-Item001";
		// 玩家帳號 ( 此為選填 )
		$trans->nodes["USER_ACCTID"] = $result->id; 
		
		// 以商家密碼、商家密鑰 I , II ( 已於 Common.php 內設定 ) 取得 ERQC
		// $erqc = $trans->GetERQC( config('Gash.password.p'), config('Gash.password.k'), config('Gash.password.v') );
		$erqc = $trans->GetERQC( $p, $k, $v );
		// 商家交易驗證壓碼
		$trans->nodes["ERQC"] = $erqc;
		
		// 取得送出之交易資料
		$data = $trans->GetSendData();
		$temp_list=array($data,$trans->nodes["CID"]);
		return json_encode($temp_list);
		return 'COID: ';
	}

	public function ReturnUrl ($request)
	{
		$p = config('Gash.password.p');
		$k = config('Gash.password.k');
		$v = config('Gash.password.v');

		Redis::set('GASH', json_encode($request));
		$trans = new Trans( $request );
		Redis::set('GASH_trans', json_encode($trans));
		$isSuccess = ($trans->nodes["RCODE"] == "0000");
		$isCorrect = false;
		
		if ( $isSuccess ) {		
			// 檢核 GPS 交易驗證壓碼
			$isCorrect = ($trans->VerifyERPC($k, $v));		
		}

		header('Location: http://10.0.20.104:8110');
		exit;
		// return json_encode($request);
	}

	public function Lookat()
	{
		// $data=""
		// $trans = new Trans( (json_decode($data)) );
		// $settleresult = Settle($coid);
		// $data = Redis::get('GASH_Result2');
		// $trans = new Trans( json_encode($data) );
		// $obj = json_decode($data);
		// $obj->nodes->MSG_TYPE = "0500";
		// $ans = response()->json($obj->nodes->MSG_TYPE);
		// return $data;

		$flow = new Hg_customer_flow();
		$settleResult =true;
		$money_change = $flow->update_flow(371, 1, $settleResult==true ? 55 : 56, $settleResult==true ? 300:0);
		//回傳結果 使用Redis紀錄
		//失敗
		if ($money_change['code'] != 200) {
			$money_change['message'] = '交易失敗';
			$money_change['data']['transaction'] = 0;
			return $money_change;
		} else {
		//成功
			$success= [
				'message' => 'success',
				'code' => 200,
				'data' => [
					'userId' => 371,
					'type' => $settleResult == true ? 55 : 56,
					'amount' => $settleResult == true ? 300:0,
					'transaction' => 1,
				]
			];
			return $success;
		}
		return $data;
	}

	public function Request($request) {
		// 取得回傳結果
		$transData = $request;
	
		// 解析回傳結果
		// $trans = new Trans( $request );
		Redis::set('GASH_Request', $transData);
		return $transData;
	}

	public function Settle($coid) {	
		$p = config('Gash.password.p');
		$k = config('Gash.password.k');
		$v = config('Gash.password.v');

		$trans = new Trans( null );
		$COID_Number='';	
		// 交易訊息代碼
		$trans->nodes["MSG_TYPE"] = "0500"; // 請款請使用 0500
		// 交易處理代碼 
		$trans->nodes["PCODE"] = "300000"; // 一般交易請使用 300000, 月租交易請使用 303000, 月租退租請使用 330000
		// 商家遊戲代碼
		$trans->nodes["CID"] = "C009160001429";
		// 商家訂單編號
		// $trans->nodes["COID"] = "CP20210728092712";
		$trans->nodes["COID"] = $coid;
		// 幣別 ISO Alpha Code
		$trans->nodes["CUID"] = "TWD";
		// 付款代收業者代碼 
		$trans->nodes["PAID"] = "COPGAM05"; // 此範例為台灣大哥大一般型, 月租交易請使用 TELTCC02
		// 交易金額
		$trans->nodes["AMOUNT"] = "0";		
		// 以商家密碼、商家密鑰 I , II ( 已於 Common.php 內設定 ) 取得 ERQC
		$erqc = $trans->GetERQC( $p, $k, $v );
		// 商家請款驗證壓碼
		$trans->nodes["ERQC"] = $erqc;		
		// 取得送出之交易資料
		$data = $trans->GetSendData();		
		
		// 取得送出查單之交易資料
		// $transData = $_POST["data"];
		$transData = $data;
		// 設定請款服務位置
		$serviceURL = "https://stage-api.eg.gashplus.com/CP_Module/settle.asmx?wsdl";
		
		// 進行請款
		$client = new SoapClient($serviceURL);
		$result =  $client->getResponse( array( "data" => $transData ) );
		// 取得結果
		$transData = $result->getResponseResult;
		// 解析回傳結果
		$trans_Result = new Trans( $transData );
		Redis::set('GASH_Result2', json_encode($trans_Result));
		
		$isSuccess = ($trans_Result->nodes["RCODE"] == "0000");
		$isCorrect = false;		
		if ( $isSuccess ) {
			// 檢核 GPS 請款驗證壓碼
			$isCorrect = ( $trans_Result->VerifyERPC( $k, $v ) );

			if($isCorrect) {
				$settleResult = Settle($trans->nodes["COID"]);
				$flow = new Hg_customer_flow();
				$money_change = $flow->update_flow($trans->nodes["USER_ACCTID"], 1, $settleResult ? 55 : 56, $settleResult ? $trans->nodes["AMOUNT"]:0);
				//回傳結果 使用Redis紀錄
				//失敗
				if ($money_change['code'] != 200) {
					$money_change['message'] = '交易失敗'.$trans->nodes["RCODE"];
					$money_change['data']['transaction'] = 0;
					Redis::set('update_flow_data', json_encode($money_change));
				} else {
				//成功
					$success= [
						'message' => $trans->nodes["RCODE"],
						'code' => 200,
						'data' => [
							'userId' => $trans->nodes["USER_ACCTID"],
							'type' => $settleResult == true ? 55 : 56,
							'amount' => $settleResult == true ? $trans->nodes["AMOUNT"]:0,
							'transaction' => 1,
						]
					];
					Redis::set('update_flow_data', json_encode($success));
				}
			}			
		}

		return $isSuccess?'true':'false';
	}
}