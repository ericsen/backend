<?php

namespace App\Games;

use App\Games\IGameInterface;
use App\Games\GameAPI;
use Exception;
use TripleDES;

class ALLBET implements IGameInterface
{
    private static $_apiConf = [];
    
    /**
     * @todo 以下變數之後將會改成從config拿
     */
    private static $_desKey = 'E7aitnDOlJ2krptb/ahGENN1KcdUGmQE';
    private static $_md5Key = '37IpxBwflt8zafjYhy7ehGYifGv1B963p2TWFnK3Wk4=';
    private static $_apiUrl = 'https://api3.abgapi.net/';
    // private static $_apiUrl = 'https://platform.abgapi.net/';
    public static $_brand = 'allbet';
    public static $_propertyId = '0904423';
    public static $_agent = '1re5vyn';
    
    public static $_handicaps_info = [];
	
	private static function _getMethodUrl($method)
    {
        $methodUrl = [
            'forwardGameTrial' => self::$_apiUrl . '/forward_game_trial',
            'queryGametable' => self::$_apiUrl . '/query_gametable',
            'queryAgentHandicaps' => self::$_apiUrl . '/query_handicap',
            'createUser' => self::$_apiUrl . '/check_or_create ',
            'forwardGame' => self::$_apiUrl . '/forward_game',
            'transaction' => self::$_apiUrl . '/agent_client_transfer',
            'getBalance' => self::$_apiUrl.'/get_balance'
        ];

        if (!array_key_exists($method, $methodUrl)) {
            throw new Exception('method url undefine');
        }

        return $methodUrl[$method];
    }
	
	public static function queryAgentHandicaps()
    {	
		$params = [
			'agent' => self::$_agent,
            'random' => mt_rand()
        ];

        $result = self::_curl(__FUNCTION__, self::_encodeRequest($params));
	
        return $result;
    }
	
	public static function createUser($fields = [])
    {
        /**
         * $array: 目標陣列
         * $offset: 目標元素的起始索引
         * $length: 目標元素的數量
         * $target_type: 目標類型 (0='普通盤口', 1='vip盤口')
         */
        $_handicaps_name_selector = function ($array, $offset, $length, $target_type) {
            $_filter = function ($hadicap) use ($target_type) {
                return $hadicap['handicapType'] == $target_type;
            };
            $_reducer = function ($carry, $item) {
                return $carry .= $item['name'];
            };
            return
                array_reduce(
                    array_slice(
                        array_filter(
                            $array['data']['handicaps'],
                            $_filter
                        ),
                        $offset,
                        $length
                    ),
                    $_reducer
                );
        };

        $params = [
            'random' => mt_rand(),
            'client' => $fields['client'],
            'password' => $fields['password'],
            'orHallRebate' => 0,
			'agent' => self::$_agent
        ];
		
        $handicap_req = [
            'random' => $params['random'],
        ];
		
        $handicaps_info = self::queryAgentHandicaps($handicap_req);
		
        $params['orHandicapNames'] = $_handicaps_name_selector($handicaps_info, 0, 1, 0);
		
        $params['vipHandicapNames'] = $_handicaps_name_selector($handicaps_info, 0, 1, 1);
		
        $result = self::_curl(__FUNCTION__, self::_encodeRequest($params));
		
        return $result;

    }
	
    /**
     * 3DES 加密
     *
     * @param array $req api param.
     * @return [string] http request 之 data 欄位.
     */
    private static function _3DESEncrypt($reqs)
    {
        return TripleDES::encryptText($reqs, self::$_desKey);
    }

    /**
     * 簽章
     *
     * @param [string] $data return by _3desEncrypt().
     * @return md5 signature.
     */
    private static function _sign($data)
    {
        return base64_encode(md5($data . self::$_md5Key, TRUE));
    }

    private static function _curl($method, $params)
    {
        $apiUrl = self::_getMethodUrl($method);

        $requestLog = [
            'brand' => self::$_brand,
            'method' => $method,
            'url' => $apiUrl,
            'params' => $params,
        ];
        GameAPI::log(self::$_brand, $method, 'request', $requestLog, 'GAME_API');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $data = json_decode(curl_exec($curl), TRUE);
        $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl)) {
            $curlError = [
                'brand' => self::$_brand,
                'method' => $method,
                'error' => curl_error($curl),
            ];

            GameAPI::log(self::$_brand, $method, 'curl', $curlError, 'GAME_API');
            throw new \Exception($curlError['error'], $http_status_code);
        }

        $result = [
            'code' => $http_status_code,
            'message' => $data['message'],
            'data' => $data
        ];

        $responseLog = [
            'brand' => self::$_brand,
            'method' => $method,
            'result' => $result,
        ];

        // response log
        GameAPI::log(self::$_brand, $method, 'response', $responseLog, 'GAME_API');

        return $result;
    }

    public static function _encodeRequest($fields)
    {
        $data = self::_3DESEncrypt(http_build_query($fields));
        $params = [
            'data' => $data,
            'sign' => self::_sign($data),
            'propertyId' => self::$_propertyId
        ];
        return $params;
    }

    /**
     * 取得桌台列表
     * 
     */
    public static function queryGametable($fields)
    {
        $params = array_merge($fields, [
            'agent' => self::$_agent
        ]);
        return self::_curl(__FUNCTION__, self::_encodeRequest($params));
    }

    public static function forwardGame($fields)
    {
        return self::_curl(__FUNCTION__, self::_encodeRequest($fields));
    }

    /**
     * 取得遊戲試玩URL
     */
    public static function forwardGameTrial($fields)
    {
        return self::_curl(__FUNCTION__, self::_encodeRequest($fields));
    }

    public static function login($fields){
		
		return;
		
    }

    public static function kickPlayer($fields)
    {
    }

    public static function getBalance($fields)
    {
        $result = self::_curl(__FUNCTION__, self::_encodeRequest($fields));
        return $result;
    }

    public static function transaction($fields)
    {
        $params = array_merge($fields, [
            'agent' => self::$_agent
        ]);
        $result = self::_curl(__FUNCTION__, self::_encodeRequest($params));
        return $result;
    }
}
