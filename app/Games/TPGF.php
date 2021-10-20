<?php
namespace App\Games;

use Illuminate\Support\Facades\DB;
use App\Models\Api\GameUser;

use Exception;

class TPGF

{
    public static $brand = "TPGF";
    private static $_serverUrl;
    private static $_webloUrl;
    public static $_id = "194";
    private static $_area = "TPG";//TPG or TPGTEST

    public static function _getMethodUrl($method)
    {
        self::$_serverUrl = config('TPG.'.self::$_area.'.serverUrl');
        self::$_webloUrl = config('TPG.'.self::$_area.'.webloUrl');

        $methodUrl = [
            'GetGameToken' => self::$_serverUrl . '/game/GetGameToken',
            'GameLauncher' => self::$_webloUrl . '/game/direct2Game',
            'FundTransfer' => self::$_serverUrl . '/game/FundTransfer',
            'GetPlayerGameBalance' => self::$_serverUrl . '/game/GetPlayerGameBalance',
            'NewGetBatchTxnHistory' => self::$_serverUrl . '/NewGetBatchTxnHistory',
        ];

        if (!array_key_exists($method, $methodUrl)) {
            throw new Exception('method url undefine');
        }

        return $methodUrl[$method];
    }

    public static function getGameToken($playerName,$displayName,$currency,$loginIp)
    {
        $apiUrl = self::_getMethodUrl('GetGameToken');

        $requestLog = [
            'operatorId' => self::$_id,
            'playerName' => $playerName,
            'displayName' => $displayName,
            'currency' => $currency,
            'loginIp' => $loginIp,
        ];

        $apiUrl = $apiUrl.'?'.http_build_query($requestLog);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);// 將獲取的訊息以文件流的形式返回，而不是直接輸出
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);// 啟用時會將服務器服務器返回的「Location:」放在header中遞歸的返回給服務器

        $data = json_decode(curl_exec($curl), TRUE);

        $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $message = $data['message'];

        return $message;
    }

    public static function postUrl($apiUrl, $params)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $data = json_decode(curl_exec($curl), TRUE);

        return $data;
    }

    public static function getUrl($apiUrl, $params)
    {

        $apiUrl = $apiUrl.'?'.http_build_query($params);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        $data = json_decode(curl_exec($curl), TRUE);
        return $data;
    }

    public static function returnData($code, $message, $data = []){
        $returnData = [
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];
        return $returnData;
    }

    public static function getPoint($params, $apiUrl){

        $data = TPG::getUrl($apiUrl, $params);    
        return $data;
    }

    public static function checkGameUser( $userId)
    {
        // 檢查 gameuser 是否存在
        $gameUser = GameUser::getUserObj(self::$brand, $userId);
        $playerName = '';
        $password = '';
        if (is_null($gameUser)) {
            // create user
            DB::connection('game')->beginTransaction();

            //$length = 16 - strlen($username);
            //$randStr = GameUser::randStr($length);
            //$randStr = uniqid($userId % 10);
            $playerName = uniqid($userId % 10);
            $password = GameUser::randStr(8);

            // create db user
            $data = [
                'Brand' => self::$brand,
                'UserId' => $userId,
                'Username' => $playerName,
                'Password' => $password
            ];
            DB::connection('game')->table('G_GameUsers')->insert($data);
            DB::connection('game')->commit();
            // DB::connection('game')->rollBack();
        } else {
            $playerName = $gameUser['Username'];
            $password = $gameUser['Password'];
        }

        $result = [
            'username' => $playerName,
            'password' => $password
        ];

        return $result;
    }
}
?>