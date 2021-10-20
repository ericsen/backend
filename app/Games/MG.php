<?php
namespace App\Games;

use App\Games\IGameInterface;
use App\Games\GameAPI;
use DB;
use Exception;
use App\Models\Api\Betlograw_mg;

//MG舞姬棋牌

class MG implements IGameInterface{

    private static $_brand = 'MG';
	
	//正式資料
	private static $_apiUrl = 'http://qp57prince.lord-fish.com:8866';
	private static $auth_key = '755e3cef6ed02f3a954eb693cff02c19';
	private static $agent = 'rt1017';

	public static function getMethodUrl($method){
		
        $methodUrl = [
            'login' => self::$_apiUrl . '/login',
            'doTransferDepositTask' => self::$_apiUrl . '/doTransferDepositTask',
            'doTransferWithdrawTask' => self::$_apiUrl . '/doTransferWithdrawTask',
            'takeBetLogs' => self::$_apiUrl . '/takeBetLogs',
            'getPoint' => self::$_apiUrl . '/queryUserScore',
        ];

        if (!array_key_exists($method, $methodUrl)) {
            throw new Exception('method url undefine');
        }

        return $methodUrl[$method];
    }

	public static function login($para){
		
        $para['agent'] = self::$agent;

        $result = [];
        $result = self::_curl(__FUNCTION__,$para);
		
		return $result;
    }
	
	public static function transaction($para = []){
		
		$type = $para['type'];
		
		unset($para['type']);
		
		$para['agent'] = self::$agent;
		
		$result = array();
        $result = self::_curl($type , $para);

        return $result;
		
	}
		
	public static function getPoint($para = []){
		
		$para['agent'] = self::$agent;
		
		$result = array();
		
		$result = self::_curl(__FUNCTION__,$para);
		
		return $result;

	}
	
	
	public static function takeBetLogs($para = []){
		
		$para['agent'] = self::$agent;
		
		$logs = self::_curl(__FUNCTION__ , $para);
		
		if( $logs['code'] != 0 || $logs['data']['total'] < 0 ){
			
			return $logs;
		}
		
		// echo '<pre>';
		// print_r($logs);
		// echo '</pre>';
		
		$pages = ceil($logs['data']['total'] / $para['size']);

		$pages--;

		$insert = [];
		
		$count = 0;
		
		if(!empty($logs['data']['bets'])){
			
			$insert = $logs['data']['bets'];
		}
		
		if($pages >= 1){
			
			for($i=1;$i<=$pages;$i++){	
				
				$para['page'] = $i;
				
				$logs = self::_curl(__FUNCTION__ , $para);
				
				if(empty($logs['logs']['bets'])){
			
					break;
					
				}else{
					
					$insert = array_merge($insert, $logs['data']['bets']);
				}
				
				sleep(2); //2sec
				
			}

		}
		
		// echo '<pre>';
		// print_r($insert);
		// echo '</pre>';
		
		foreach($insert as $k => $v){
			
			Betlograw_mg::updateOrCreate([
				'roundId' => $v['roundId']
			],$v);
			
			if($action->wasRecentlyCreated) {
				$count++;
			}
			
		}

		return $count;
	}
	
	private static function make_sign($post = []){
		
		$string = json_encode($post);
		
		return md5($string.self::$auth_key);
		
	}
	
	private static function _curl($method , $para){
		
        $apiUrl = self::getMethodUrl($method);

        $requestLog = [
            'brand' => self::$_brand,
            'method' => $method,
            'url' => $apiUrl,
            'params' => $para,
        ];

        GameAPI::log(self::$_brand, $method, 'request', $requestLog, 'GAME_API');
		
		$headers = [
			'Authorization: '.self::make_sign($para),
			'Content-Type: text/plain'
		];
		
		$post_raw = json_encode($para);
		
        $curl = curl_init();
		
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$post_raw);
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
       
        $apiResult = array();
        $apiResult = curl_exec($curl);

        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($httpcode != '200') {
            GameAPI::log(self::$_brand, $method, 'http error', $httpcode, 'GAME_API');
        }

        if (curl_errno($curl)) {
            $curlError = [
                'brand' => self::$_brand,
                'method' => $method,
                'error' => curl_error($curl),
            ];
            GameAPI::log(self::$_brand, $method, 'curl', $curlError, 'GAME_API');
			throw new \Exception($curlError['error'], $httpcode);
        }

        $result = json_decode($apiResult, true);

        $responseLog = [
            'brand' => self::$_brand,
            'method' => $method,
            'result' => $result,
        ];

        GameAPI::log(self::$_brand, $method, 'response', $responseLog, 'GAME_API');

        return $result;
    }
	
	public static function createUser($fields){}
	
	public static function kickPlayer($fields){}

    public static function getBalance($gameUserName){}
	
}