<?php
namespace App\Games;

use App\Games\IGameInterface;
use App\Games\GameAPI;
use Exception;
use App\Models\Api\Betlograw_avia;

//泛亞電競

class AVIA implements IGameInterface{

    private static $_brand = 'AVIA';
	
	//測試資料
	//private static $_apiUrl = 'https://api.avia-gaming.vip';
	//private static $auth_key = '9b358d8549004bd0bb48b3f94ae7e2a5';
	
	//正式資料
	private static $_apiUrl = 'https://api.avia-gaming.com';
	private static $auth_key = 'bc75dc841caf4beca7919b4d9042d6b4';

	public static function getMethodUrl($method){
		
        $methodUrl = [
            'createUser' => self::$_apiUrl . '/api/user/register',
            'login' => self::$_apiUrl . '/api/user/login',
            'transaction' => self::$_apiUrl . '/api/user/transfer',
            'get_bet_log' => self::$_apiUrl . '/api/log/get',
            'get_point' => self::$_apiUrl . '/api/user/balance'
        ];

        if (!array_key_exists($method, $methodUrl)) {
            throw new Exception('method url undefine');
        }

        return $methodUrl[$method];
    }
	
    public static function login($fields){
		
        $params = [
            'UserName' => $fields['username'],
            'CateID' => $fields['CateID'],
            'MatchID' => $fields['MatchID']
        ];

        $result = [];
        $result = self::_curl(__FUNCTION__,$params);
		
		return $result;
    }
	
    public static function createUser($fields){
		
        $params = [
            'UserName' => $fields['username'],
            'Password' => $fields['password'],
            'Currency' => $fields['Currency']
        ];

        $result = array();
        $result = self::_curl(__FUNCTION__ , $params);

        return $result;
    }
	
	public static function get_point($para = []){
		
		$send = [
			'UserName' => $para['username']
		];
		
		$result = array();
        $result = self::_curl(__FUNCTION__ , $send);

        return $result;	
		
	}
	
	public static function transaction($para = []){
		
		$send = [
			'Type' => $para['type'],
			'UserName' => $para['username'],
			'Money' => $para['amount'],
			'ID' => $para['track_id'],
			'Currency' => $para['currency']
		];
		
		$result = array();
        $result = self::_curl(__FUNCTION__ , $send);

        return $result;
		
	}
	
	public static function get_bet_log($para = []){
		
		$logs = self::_curl(__FUNCTION__ , $para);
		
		if($logs['success'] != 1 || empty($logs['info']['list'])){
			
			return json_encode($logs);
			
		}
		
		$pages = ceil($logs['info']['RecordCount'] / $para['PageSize']);
		
		$insert = [];
		
		$insert = $logs['info']['list'];
		
		$count = 0;
		
		if($pages >= 2){
			
			for($i=2;$i<=$pages;$i++){
			
				$para['PageIndex'] = $i;
				
				$logs = self::_curl(__FUNCTION__ , $para);
				
				if(empty($logs['info']['list'])){
			
					break;
					
				}else{
					
					$insert = array_merge($insert, $logs['info']['list']);
					
				}
				
				sleep(2); //2sec
			
			}
			
		}
		
		// echo '<pre>';
		// print_r($insert);
		// echo '</pre>';
		
		foreach($insert as $k => $v){
			
			if(isset($v['Platform'])){
				$v['Platform'] = json_encode($v['Platform']);
			}
			
			if(isset($v['Details'])){
				$v['Details'] = json_encode($v['Details']);
			}
			
			$action = Betlograw_avia::updateOrCreate([
				'OrderID' => $v['OrderID']
			],$v);

			if($action->wasRecentlyCreated) {
				$count++;
			}

		}

		return $count;
		
	}
	
	private static function _curl($method , $params){
		
        $apiUrl = self::getMethodUrl($method);

        $requestLog = [
            'brand' => self::$_brand,
            'method' => $method,
            'url' => $apiUrl,
            'params' => $params,
        ];

        GameAPI::log(self::$_brand, $method, 'request', $requestLog, 'GAME_API');
		
		$headers = [
			'Authorization: '.self::$auth_key,
			'Content-Type: application/x-www-form-urlencoded'
		];
		
        $curl = curl_init();
		
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
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
	
	public static function kickPlayer($fields){}

    public static function getBalance($gameUserName){}
	
}
	
	
	
	
	