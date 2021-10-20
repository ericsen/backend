<?php
namespace App\Games;

use Illuminate\Support\Facades\DB;
use App\Models\Api\GameUser;

class RTG
{
    public static $site_url = "https://cms.rtgintegrations.com/api";
    public static $brand ="RTG";
    private static $username = 'CCKprodapi';
    private static $password = 'UZXf8tvkk6ceVhy1';


    public static function returnData($code, $message, $data = []){
        $returnData = [
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];
        return $returnData;
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

    //獲得token
    public static function getToken(){
        $apiUrl = RTG::$site_url.'/start/token';
        $params = [
            'username' => self::$username,
            'password' => self::$password,
        ];
        $apiUrl = $apiUrl.'?'.http_build_query($params);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        $data = json_decode(curl_exec($curl), TRUE);

        $token = $data['token'];
        //$token = str_replace('Bearer', '', $token);
        //$token = trim($token);
        return $token;
    }

    //啟用token
    public static function startToken(){
        $token = RTG::getToken();
        

        $apiUrl = RTG::$site_url."/start";
        $curl = curl_init();
        $header[] = 'Authorization: '. $token;
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        $data = json_decode(curl_exec($curl), TRUE);
        $data = array_merge($data,['token' => $token]);
        return $data;
    }

    //產生使用者
    public static function createUser($cid, $currency){
        $data = RTG::startToken();
        $token = $data['token'];
        $agentId = $data['agentId'];
        $userId = RTG::checkGameUser($cid);

        $params = [
            'agentId' => $agentId,
            'username' => $userId['username'],
            'currency' => $currency
        ];

        $dataRAW = json_encode($params);
        $apiUrl = RTG::$site_url."/player";
        $apiUrl = $apiUrl.'?'.http_build_query($params);
        $curl = curl_init();
        $header = [
            'Authorization: '. $token,
            'Content-Type: application/json',
        ];
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataRAW);
        
        $data = json_decode(curl_exec($curl), TRUE);
        return $data;
    }

    public static function getUrl($params,$apiUrl){

        $data = RTG::startToken();
        $token = $data['token'];
        $dataRAW = json_encode($params);
        $params['agentId'] = $data['agentId'];

        $curl = curl_init();
        $header = [
            'Authorization: ' . $token,
            'Content-Type: application/json',
        ];
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POST, true);
        //curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dataRAW);
        // curl_exec($curl);
        // $data = json_decode(curl_exec($curl), TRUE);
        // $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        return $curl;
    }
}