<?php

namespace App\Games;

use Illuminate\Support\Facades\DB;
use App\Models\Api\GameUser;

use Exception;

class WM
{
    public static $site_url = "https://rswb-039.wmapi88.com/api/public/Gateway.php";
    public static $brand = "WM";
    public static $vendorId = 'cckntdapi';
    public static $signature = 'aee5077a0485b24748c58b132adf9762';

    public static function checkGameUser($userId)
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

    public static function WMUser($userId)
    {
        $sql = "select * from hg_customer where id = '$userId'";
        $datas = DB::select($sql);
        return $datas[0];
    }

    public static function returnData($code, $message, $data = [])
    {
        $returnData = [
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];
        return $returnData;
    }

    public static function getUrl($params)
    {
        $params = array_merge($params, [
            'vendorId' => WM::$vendorId,
            'signature' => WM::$signature,
        ]);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, WM::$site_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));

        return $curl;
    }

    public static function getPoint($cid){
        $gameUser = WM::checkGameUser($cid);
        $params = [
            'cmd' => 'GetBalance',
            'user' => $gameUser['username'],
        ];
        return WM::getUrl($params);
    }
}
