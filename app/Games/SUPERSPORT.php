<?php
namespace App\Games;

use App\Games\IGameInterface;
use App\Games\GameAPI;
use Exception;
use DB;
use App\Models\Api\Betlograw_supersport;

//SUPER體育

class SUPERSPORT implements IGameInterface{

    private static $_brand = 'super';
	private static $_apiUrl = 'http://apiball.king588.net/api';
	
	private static $up_account = 'F531';
	private static $up_passwd = 'RJ5868gK';
	
	private static $api_encrypt_key = 'WGI@X9ENgpo138jL';
	private static $api_encrypt_iv = 'm%2qQ7L&wfafUj&b';
	
	public static function getMethodUrl($method){
		
        $methodUrl = [
            'createUser' => self::$_apiUrl . '/account',
            'login' => self::$_apiUrl . '/login',
            'transaction' => self::$_apiUrl . '/points',
            'get_bet_report' => self::$_apiUrl . '/report',
            'get_point' => self::$_apiUrl . '/points'
        ];

        if (!array_key_exists($method, $methodUrl)) {
            throw new Exception('method url undefine');
        }

        return $methodUrl[$method];
    }

    public static function login($fields){
		
        $params = [
            'account' => self::encrypt($fields['username']),
            'passwd' => self::encrypt($fields['password']),
            'responseFormat' => 'json',
            'lang' => $fields['lang']
        ];

        $result = [];
        $result = self::_curl(__FUNCTION__,$params);
		
		return $result;
    }
	
    public static function createUser($fields){
		
        $params = [
            'act' => 'add',
            'up_account' => self::encrypt(self::$up_account),
            'up_passwd' => self::encrypt(self::$up_passwd),
            'account' => self::encrypt($fields['username']),
            'passwd' => self::encrypt($fields['password']),
            'nickname' => $fields['nickname'],
            'level' => $fields['level']
        ];

        $result = array();
        $result = self::_curl(__FUNCTION__ , $params);

        return $result;
    }
	
	public static function get_point($para = []){
		
		$send = [
			'act' => 'search',
			'up_account' => self::encrypt(self::$up_account),
			'up_passwd' => self::encrypt(self::$up_passwd),
			'account' => self::encrypt($para['username'])
		];
		
		$result = array();
        $result = self::_curl(__FUNCTION__ , $send);
		
		return $result;
		
	}
	
	public static function transaction($para = []){
		
		$send = [
			'act' => $para['type'],
			'up_account' => self::encrypt(self::$up_account),
			'up_passwd' => self::encrypt(self::$up_passwd),
			'account' => self::encrypt($para['username']),
			'point' => $para['amount'],
			'track_id' => $para['track_id']
		];
		
		$result = array();
        $result = self::_curl(__FUNCTION__ , $send);

        return $result;
		
	}
	
	public static function get_last_tow_days_login_member(){
		
		$result = [];
		
		$yesterday_timestamp = strtotime('yesterday midnight');
		
		$members = DB::table('hg_customer')->where('latest_time_login', '>=' ,$yesterday_timestamp)->get();
		
		//print_r($members);
		
		if($members->isEmpty()){
			
			return $result;
			
		}else{
			
			$members_uids = $members->pluck('id')->toArray();
			
			//print_r($members_uids);
			
			$members_uids = array_flip($members_uids);
			
			//print_r($members_uids);
			
		}
		
		$supersport_players = DB::table('g_gameusers')->where('Brand', 'SUPER')->get();
		
		//print_r($supersport_players);
		
		if($supersport_players->isEmpty()){
			
			return $result;
		
		}else{
			
			foreach($supersport_players as $v){
				
				if (array_key_exists($v->UserId, $members_uids)){
					
					$result[] = $v->Username;
					
				}

			}
			
			//print_r($result);
			
		}
		
		return $result;
		
	}
	
	public static function get_bet_report($para = []){
		
		$players = self::get_last_tow_days_login_member();
		
		if(empty($players)){
			
			return null;
			
		}else{

			$account_string = implode (",", $players);

		}
		
		$para['act'] = 'detail';
		$para['level'] = 1;
		$para['account'] = self::encrypt($account_string);
		
		$result = [];
        $result = self::_curl(__FUNCTION__ , $para);

		if($result['code'] != 999){
			
			return $result['msg'];
			
		}else{
			
			$insert = $result['data'];
			
			$count = 0;
			
			foreach($insert as $k => $v){
			
				$action = Betlograw_supersport::updateOrCreate([
					'sn' => $v['sn']
				],$v);
				
				if($action->wasRecentlyCreated) {
					$count++;
				}

			}

			return $count;
			
		}
		
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

        $curl = curl_init();
		//array_push($header, 'Content-Type: application/x-www-form-urlencoded');
		//curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
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
	
	//加密
	private static function encrypt($string){

		$data = openssl_encrypt($string, "AES-128-CBC", self::$api_encrypt_key, OPENSSL_RAW_DATA, self::$api_encrypt_iv);
		$data = base64_encode($data);
		return $data;
		
	}
	
	//解密
	private static function decode($string){
		
		$data = openssl_decrypt(base64_decode($string), 'AES-128-CBC', self::$api_encrypt_key, OPENSSL_RAW_DATA, self::$api_encrypt_iv);
		return $data;
		
	}
	
	public static function kickPlayer($fields){}

    public static function getBalance($gameUserName){}
	
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	