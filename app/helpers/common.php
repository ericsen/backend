<?php

// if param is empty give default value
function setEmptyDef(&$val, $defVal = '')
{
    if (empty($val)) {
        return $defVal;
    }
    return $val;
}
// if param is empty give default value
function setNullDef(&$val, $defVal = '')
{
    if (!isset($val)) {
        return $defVal;
    }
    return $val;
}
// 數字格式
function NFormat($amount, $decimals = 0, $colorType = 0)
{
    if ($colorType != 0) {
        if ($amount < 0) {
            $color = 'style="color:#D85E62";';
        } elseif ($amount > 0) {
            $color = 'style="color:#2BCF5C";';
        } else {
            $color = "";
        }
        $str = '<span ' . $color . '>' . number_format($amount, $decimals, '.', ',') . '</span>';
        return $str;
    }
    return number_format($amount, $decimals, '.', ',');
}
 
/**
 * 數字格式-無條件捨去
 *
 * @param decimal $amount    數字
 * @param integer $decimals  小數點後x位
 * @param integer $isTag     是否加千分號(0否1是)
 * @return void
 */
function NFormatFloor($amount, $decimals = 0, $isTag = 0)
{
    $result = $amount;
    $pow = pow(10, $decimals);
    $result = floor($amount*$pow)/$pow;

    $tag = ($isTag)?',':'';
    $result = number_format($result, $decimals, '.', $tag);

    //
    return $result;
}

// 
if (!function_exists('array_key_first')) {
    function array_key_first(array $arr)
    {
        foreach ($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }
}

// 分頁
function myPaginate(&$data)
{
    $result = [
        'page'     => 1, // 頁數
        'per_page' => 20, // 每頁筆數
        'row'      => 0, // 第x筆開始
    ];
    if (!empty($data['page']) && $data['page'] > 0) {
        $result['page'] = $data['page'];
    }
    if (!empty($data['per_page']) && $data['per_page'] > 0) {
        $result['per_page'] = $data['per_page'];
    }
    $result['row'] = ($result['page'] - 1) * $result['per_page'];
    //
    return $result;
}

function transUrl($url)
{
    $url = trim($url);
    if ($url == '') return '';

    $result = $url;

    $result = "/" . $url . "/";
    $result = str_replace("//", "/", $result);
    $result = str_replace("/", "／", $result);

    return $result;
}

// check request is ajax
function isAjax()
{
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        return 1;
    }
    return 0;
}

/**
 * Undocumented function
 *
 * @param int $code 代碼(200成功，其他都算失敗)
 * @param string $message
 * @param array $data
 * @return void
 */
function jsonResponse($code, $message = '', $data = [])
{
    $result = [
        'code'    => $code,
        'message' => $message,
        'data'    => $data,
    ];
    //
    return json_encode($result);
}


function jsonResponseExit($code, $message = '', $data = [])
{
    echo jsonResponse($code, $message, $data);
    exit();
}


function myValidatorMessage()
{
    if (Session::has('validatorMessage') && Session::get('validatorMessage') != '') {
        $validatorMessage = Session::get('validatorMessage');
        Session::forget('validatorMessage');
        return $validatorMessage;
    }
    return '';
}

// 處理 myMessage
function MsgToString($validatorMessage)
{
    return implode("<br>", $validatorMessage);
}

// 日期是否合法
if (!function_exists('isDate')) {
    function isDate($sDate)
    {
        $myDate = explode("-", $sDate, 3);
        if (count($myDate) == 3) {
            list($yy, $mm, $dd) = $myDate;
        } else {
            return false;
        }

        if (strlen($yy) <> 4 || strlen($mm) <> 2 || strlen($dd) <> 2) {
            return false;
        }

        if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd)) {
            return checkdate($mm, $dd, $yy);
        }
        return false;
    }
}

//BBIN注單玩法轉換
function BBINWagerDetail($GameType, $WagerDetail)
{
    $result = [];

    switch ($GameType) {
            // 百家樂
            // 下注類型,賠率,下注金額,派彩金額*下注類型2,賠率2,下注金額2,派彩金額2
            // 1,1:0.95,5000.00,4750.00*2,1:1,5000.00,-5000.00*3,1:8,5000.00,-5000.00*4,1:11,5000.00,-5000.00*5,1:11,5000.00,-5000.00*6,1:0.54,5000.00,-5000.00*7,1:1.5,5000.00,7500.00*12,1:5,5000.00,-5000.00*13,1:20,5000.00,-5000.00
        case 3001:
            $bet = [
                1 => trans("admin.game_bet_bbin_3001.WagerDetail_1"), // 莊
                2 => trans("admin.game_bet_bbin_3001.WagerDetail_2"), // 閒
                3 => trans("admin.game_bet_bbin_3001.WagerDetail_3"), // 和
                4 => trans("admin.game_bet_bbin_3001.WagerDetail_4"), // 莊對
                5 => trans("admin.game_bet_bbin_3001.WagerDetail_5"), // 閒對
                6 => trans("admin.game_bet_bbin_3001.WagerDetail_6"), // 大
                7 => trans("admin.game_bet_bbin_3001.WagerDetail_7"), // 小
                12 => trans("admin.game_bet_bbin_3001.WagerDetail_12"), // 任意對子
                13 => trans("admin.game_bet_bbin_3001.WagerDetail_13") // 完美對子
            ];
            break;
            // HiLo
            // 下注類型,賠率,下注金額,派彩金額*下注類型2,賠率2,下注金額2,派彩金額2
            // 1,0.94,5000.00,4700.00*2,11.06,5000.00,-5000.00*3,1.32,5000.00,-5000.00*4,1.90,5000.00,-5000.00*5,1.90,5000.00,9500.00*6,1.90,5000.00,-5000.00*7,0.94,5000.00,-5000.00*8,0.94,5000.00,4700.00*9,0.66,5000.00,3300.00*10,1.32,5000.00,-5000.00
        case 3021:
            $bet = [
                1 => trans("admin.game_bet_bbin_3021.WagerDetail_1"), // 高
                2 => trans("admin.game_bet_bbin_3021.WagerDetail_2"), // 相同
                3 => trans("admin.game_bet_bbin_3021.WagerDetail_3"), // 低
                4 => trans("admin.game_bet_bbin_3021.WagerDetail_4"), // 2/3/4/5
                5 => trans("admin.game_bet_bbin_3021.WagerDetail_5"), // 6/7/8/9
                6 => trans("admin.game_bet_bbin_3021.WagerDetail_6"), // J/Q/K/A
                7 => trans("admin.game_bet_bbin_3021.WagerDetail_7"), // 紅
                8 => trans("admin.game_bet_bbin_3021.WagerDetail_8"), // 黑
                9 => trans("admin.game_bet_bbin_3021.WagerDetail_9"), // 單
                10 => trans("admin.game_bet_bbin_3021.WagerDetail_10") // 雙
            ];
            break;
        default:
            break;
    }

    $details = explode('*', $WagerDetail);
    foreach ($details as $v) {
        $tmp = explode(',', $v);
        $result[] = [
            'bet_content' => $bet[$tmp[0]],
            'odds'        => $tmp[1],
            'bet_money'   => $tmp[2],
            'issue_money' => $tmp[3]
        ];
    }

    return $result;
}

//注單狀態轉換為BBIN注單結果
function statusToBBINResult($status)
{
    $stbr = [
        0 => 'X',   //未結算、待開獎
        1 => 'W',   //成功/贏、中獎
        2 => 'L',   //輸、未中獎
        3 => 'D',   //和局
        4 => 'C'    //註銷
    ];
    if (isset($stbr[$status])) {
        return $stbr[$status];
    }
    return false;
}
function LotusConfig($game_id = 0)
{
    $gameConfig = [
        "3001" => [ // 單雙
            "GR_table" => 'hg_game_result_lotus_odd_even',
            "gameTime" => 60,    // 每局1分鐘
            "finalize" => 10,   // 封盤時間(秒)
            'maxCh'    => 1440, // 最大局數
        ],
        "3002" => [
            "gameTime" => 60,    // 每局1分鐘
            "finalize" => 10,   // 封盤時間(秒)
        ],
        "3003" => [
            "gameTime" => 50,    // 每局50秒
            "finalize" => 10,   // 封盤時間(秒)
        ],
        "3006" => [
            "gameTime" => 180,    // 每局3分鐘
            "finalize" => 10,   // 封盤時間(秒)
        ],
        "3004" => [ // 百家樂1
            "GR_table" => 'hg_game_result_lotus_baccarat1',
            "gameTime" => 50,    // 每局50秒
            "finalize" => 10,   // 封盤時間(秒)
            'maxCh'    => 1728, // 最大局數
        ],
        "3005" => [ // 百家樂2
            "GR_table" => 'hg_game_result_lotus_baccarat2',
            "gameTime" => 50,    // 每局50秒
            "finalize" => 10,   // 封盤時間(秒)
            'maxCh'    => 1728, // 最大局數
        ]
    ];
    if ($game_id == 0) {
        return $gameConfig;
    }
    if (isset($gameConfig[$game_id])) {
        return $gameConfig[$game_id];
    }
    return [];
}
// Lotus 取得當前局數等相關資料
function getLotusQishu($gid, $time = 0)
{
    $result = [];
    $nowTime = \setEmptyDef($time, time());
    $startTime = strtotime(date('Y-m-d 00:00:00', $nowTime));
    $LC = LotusConfig($gid);

    $gameTime = $LC['gameTime']; // 一局x秒
    $maxCh = $LC['maxCh']; // 最大局數
    $finalize = $LC['finalize']; // 封盤秒數

    // 當前局數
    $nowCh = floor(($nowTime - $startTime) / $gameTime) + 1;
    if ($nowCh > $maxCh) {
        $nowCh = 1;
    }
    // 當前局數開始時間
    $sdate = $startTime + floor(($nowTime - $startTime) / $gameTime) * $gameTime;
    $edate = $sdate + $gameTime;

    // 下一局局號
    $ch_next = ($nowCh + 1) > $maxCh ? 1 : $nowCh + 1;

    // 封盤時間
    $finalize2 = $edate - $finalize;


    $result = [
        'qishu'         => date('Ymd', $sdate) . str_pad($nowCh, 4, "0", STR_PAD_LEFT),
        'open_time'     => date('Y-m-d H:i:s', $sdate),
        'open_time2'    => $sdate,
        'rd'            => $nowCh,
        'close_time'    => date('Y-m-d H:i:s', $edate),
        'close_time2'   => $edate,
        'finalize_time' => $finalize, // 封盤秒數
        'finalize'      => date('Y-m-d H:i:s', $finalize2),
        'finalize2'     => $finalize2,
        'count_down'    => $edate - $nowTime,
        'rd_next'       => $ch_next,
        // 'sdate'     => date('YmdHis', $sdate),
    ];
    // echo '<pre>';print_r($result);
    //
    return $result;
}

function getCurl($url, $params = false, $ispost = 0, $https = 0)
{
    $httpInfo = array();
    $ch = curl_init();
    // curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    // curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if ($https) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
    }
    if ($ispost) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_URL, $url);
    } else {
        if ($params) {
            if (is_array($params)) {
                $params = http_build_query($params);
            }
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
        }
    }

    $response = curl_exec($ch);

    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
    curl_close($ch);
    return $response;
}

// 取得 POWER BALL 遊戲結果
function getPowerBallResult($ballArr)
{
    $result = [];

    $result['sum'] = 0;
    for ($i = 1; $i <= 5; $i++) {
        $result['sum'] += $ballArr['ball_' . $i];
    }
    $result['sum_odd_even'] = ($result['sum'] % 2 == 0) ? 'even' : 'odd';
    $result['sum_under_over'] = ($result['sum'] <= 72) ? 'under' : 'over';
    if ($result['sum'] <= 64) {
        $result['sum_size'] = 's';
    } elseif (65 <= $result['sum'] && $result['sum'] <= 80) {
        $result['sum_size'] = 'm';
    } elseif (81 <= $result['sum']) {
        $result['sum_size'] = 'l';
    }

    $result['powerball_odd_even'] = ($ballArr['powerball'] % 2 == 0) ? 'even' : 'odd';
    $result['powerball_under_over'] = ($ballArr['powerball'] <= 4) ? 'under' : 'over';

    return $result;
}

// 隨機取得 ladder 遊戲結果
function getLadderRandomResult()
{
    $result = [];

    $start_point = ['left', 'right'];
    $odd_even = ['odd', 'even'];

    shuffle($start_point);
    shuffle($odd_even);

    $result['start_point'] = $start_point[0];
    $result['odd_even']    = $odd_even[0];

    if (($result['start_point'] == 'left' && $result['odd_even'] == 'odd') || ($result['start_point'] == 'right' && $result['odd_even'] == 'even')) {
        $result['line_count'] = 4;
    } else {
        $result['line_count'] = 3;
    }

    return $result;
}

// 取得 bbhl 遊戲結果
function getBbhlResult($left_number, $right_number)
{
    $sum = $left_number + $right_number;
    $units_digit = substr($sum, -1);
    $high_low = in_array($units_digit, [1, 2, 3, 4, 5]) ? 'low' : 'high';
    $odd_even = ($units_digit % 2 == 1) ? 'odd' : 'even';

    $result = [
        'left_number'  => $left_number,
        'right_number' => $right_number,
        'high_low'     => $high_low,
        'odd_even'     => $odd_even,
        'units_digit'  => $units_digit,
    ];

    return $result;
}

//3DES util class
class TripleDES {
	private static function pkcs5Pad($text, $blocksize) {
	    $pad = $blocksize - (strlen($text) % $blocksize);
	    return $text . str_repeat(chr($pad), $pad);
	}

	private static function pkcs5Unpad($text) {
	    $pad = ord($text{strlen($text)-1});
	    if ($pad > strlen($text)) return false;
	    if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
	    return substr($text, 0, -1 * $pad);
	}
    /*
    //Depreated for this function
	public static function encryptText($plain_text, $key) {
	    $padded = TripleDES::pkcs5Pad($plain_text, mcrypt_get_block_size(MCRYPT_TRIPLEDES, MCRYPT_MODE_CBC));
		return mcrypt_encrypt(MCRYPT_TRIPLEDES, base64_decode($key), $padded, MCRYPT_MODE_CBC, base64_decode("AAAAAAAAAAA="));
	}
	*/
	//php7 or above need to use this method to encrypt
	public static function encryptText($string, $key)
    {
        $key= base64_decode($key);
		$string = TripleDES::pkcs5Pad($string, 8);
        $data =  openssl_encrypt($string, 'DES-EDE3-CBC', $key,OPENSSL_RAW_DATA | OPENSSL_NO_PADDING,base64_decode("AAAAAAAAAAA="));
        $data =base64_encode($data);
        return $data;
    }
};
