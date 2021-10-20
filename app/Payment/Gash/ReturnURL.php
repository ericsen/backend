<?php
	use App\Models\Api\hg_customer_flow;
	// ===============================================================================
	/* 
	 * Note:
	 * PHP Analyze Result Sample Code
	 * 
	 */
	// ===============================================================================

	include( "Common_2.php" );
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

	// 取得回傳結果
	$transData = $_POST["data"];

	// 解析回傳結果
	$trans = new Trans( $transData );
	echo $trans->nodes["RCODE"];
	$isSuccess = (($trans->nodes["RCODE"] == "0000")||($trans->nodes["RCODE"] == "2002"));
	$isCorrect = false;
	
	if ( $isSuccess ) {		
		// 檢核 GPS 交易驗證壓碼
		$isCorrect = ( $trans->VerifyERPC( $k, $v ) );		
	}

	$flow = new Hg_customer_flow();
	$money_change = $flow->update_flow($trans->nodes["USER_ACCTID"], 1, $isCorrect == true ? 55 : 56, $isCorrect == true ? $trans->nodes["AMOUNT"]:0);
	//如果娛樂城扣錢失敗
	if ($money_change['code'] != 200) {
		$money_change['data']['transaction'] = 0;
		return $money_change;
	}

	return [
		'code' => 200,
		'data' => [
			'userId' => $trans->nodes["USER_ACCTID"],
			'type' => $isCorrect == true ? 55 : 56,
			'amount' => $isCorrect == true ? $trans->nodes["AMOUNT"]:0,
			'transaction' => 1,
		]
	];
?>
<html> 
<head> 
<title>ReturnURL</title> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>

Test GPS'CP transaction is get result <br>
GPS 交易結果 : <?php echo ( ( $isSuccess ) ? "成功" : "失敗" ); ?> <br>
玩家付款結果 : <?php echo ( ( $trans->nodes["PAY_STATUS"] == "S" ) ? "成功" : "失敗" ); ?> <br>
GPS 交易驗證壓碼檢核結果 : <?php echo ( ( $isCorrect ) ? "正常" : "異常or N/A" ); ?> <br>
交易金額 : <?php echo $trans->nodes["AMOUNT"]; ?> <br>
<?php echo $trans->nodes["RCODE"]=='0000'?'正常':$trans->nodes["RCODE"]; ?> <br>
<pre><?php echo print_r(($trans->nodes)); ?></pre>
	
</body>
</html>