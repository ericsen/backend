<?php

namespace App\Games;

use Illuminate\Support\Facades\DB;
use App\Models\Api\GameUser;

use Exception;

class BAC
{
    public static $brand = "BAC";

    public static function _getMethodUrl($method)
    {
        $url_f = config('BAC.url') . '/baccarat';
        $url_b = config('BAC.url-b');

        $methodUrl = [
            'b_login' => $url_b . '/auth/login',
            'f_url' => $url_f,
            'b_cashIn' => $url_b . '/user/cashIn',
            'b_cashOut' => $url_b . '/user/cashOut',
            'b_getPoints' => $url_b . '/user/getPoints',
        ];

        if (!array_key_exists($method, $methodUrl)) {
            throw new Exception('method url undefine');
        }

        return $methodUrl[$method];
    }

    public static function getToken($token)
    {
        $apiUrl = self::_getMethodUrl('b_login');
        $params = [
            'token' => $token
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        $data = json_decode(curl_exec($curl), true);
        return $data['jwtToken'];
    }

    public static function getApiUrl($url, $header, $params =[])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));

        return $curl;
    }
}
