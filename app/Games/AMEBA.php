<?php
namespace App\Games;

use App\Games\AMEBA_encryption\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\Api\GameUser;

class AMEBA
{
    public static $brand = "AMEBA";
    private static $id = 5049;
    private static $area = "AMEBA";//AMEBA or AMEBA-TEST

    public static function setParams($token = array(),$method){
        $methodUrl = [
            'ams' => '/ams/api',
            'dms' => '/dms/api',
        ];
        $site_url = config('AMEBA.'.self::$area.'.url') . $methodUrl[$method];
        $secret_key = config('AMEBA.'.self::$area.'.key');
        $token = array_merge($token,[ 'site_id' => self::$id ]);

        $auth_jwt = JWT::encode($token, $secret_key, 'HS256');

        $headers = array(
            'Content-Length:0 ',
            'Content-type: application/json',
            'Authorization: Bearer ' . $auth_jwt
        );
        //return $site_url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $site_url);
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);

        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        $output = json_decode(curl_exec($ch), true);
        $output = array_merge($output,[ 'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE)]);
        return $output;
    }

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

    public static function getPoint($cid){
        $userId = AMEBA::checkGameUser($cid);
        $token = [
            'action' => 'get_balance',
            'account_name' => $userId['username'],
        ];
        return AMEBA::setParams($token, 'ams');
    }
}
?>