<?php

namespace App\Games;

use App\Games\GameAPI;
use Carbon\Carbon;

class DG
{
    const AGENT_ACCOUNT = 'DG00830200';
    const API_kEY = '897cb85ca9d24c2b8eb7482f2633095d';
    const API_URL = 'http://api.dg99web.com';

    private static function sign($content)
    {
        return md5($content);
    }

    private static function curl($params, $url)
    {
        $jsonStrData = json_encode($params);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonStrData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonStrData)
        ));
        $data = curl_exec($curl);
        curl_close($curl);
        return json_decode($data);
    }

    private static function getGameUrl($gameInfo, $lang, $mode = 1)
    {
        return $gameInfo->list[$mode] . $gameInfo->token . "&language=$lang";
    }

    private static function getApiUrl($category, $methodSegment)
    {
        return self::API_URL . "/$category/$methodSegment/" . self::AGENT_ACCOUNT;
    }

    private static function langMap($lang){
        $langs = [
            'zh_cn' => 'cn',
            'zh_tw' => 'tw',
        ];
        return $langs[$lang];
    }

    // private static function retryLogin($loginInfo){
        // $retryResult = [];
        // $registerInfo  = self::signUp([
            // 'username' => $loginInfo['username'],
            // 'password' => $loginInfo['password'],
            // 'currency' => 'TWD'
        // ]);
        // if($registerInfo->codeId == 0){
            // $retryResult = self::login($loginInfo);
        // }
        // return $retryResult;
    // }

    public static function signUp($fields)
    {
        $randStr = GameAPI::randStr(1);
        $params = [
            'token' => self::sign(self::AGENT_ACCOUNT . self::API_kEY . $randStr),
            'random' => $randStr,
            'data' => 'B',
            'member' => [
                'username' => $fields['username'],
                'password' => self::sign($fields['password']),
                'currencyName' => 'TWD',
                'winLimit' => 10000
            ]
        ];
        $dg_res = self::curl($params, self::getApiUrl('user','signup'));
        return $dg_res;
    }

    public static function login($fields, $isFormatToUrl = false)
    {
        $randStr = GameAPI::randStr(1);
		
        $params = [
            'token' => self::sign(self::AGENT_ACCOUNT . self::API_kEY . $randStr),
            'random' => $randStr,
            //'lang' => self::langMap($fields['lang']),
            'lang' => $fields['lang'],
            'domains' => '1',
            'member' => [
                'username' => $fields['username'],
                'password' => self::sign($fields['password']),
            ]
        ];
		
        $loginRes = self::curl($params, self::getApiUrl('user','login'));
		
        // if($loginRes->codeId == 102){
            // $loginRes = self::retryLogin($fields);
        // }
		
        $result = $isFormatToUrl ? self::getGameUrl($loginRes, $params['lang']) : $loginRes;

        return $result;
    }

    public static function getFreeGameUrl($fields)
    {
        $randStr = GameAPI::randStr(1);
        $params = [
            'token' => self::sign(self::AGENT_ACCOUNT . self::API_kEY . $randStr),
            'random' => $randStr,
            'lang' => self::langMap($fields['lang']),
            'domains' => '1',
        ];
        $freeRes = self::curl($params, self::getApiUrl('user','free'));
        return self::getGameUrl($freeRes, $params['lang']);;
    }

    public static function transaction($fields){
        $randStr = GameAPI::randStr(1);
        $params = [
            'token' => self::sign(self::AGENT_ACCOUNT . self::API_kEY . $randStr),
            'random' => $randStr,
            'data' => 'IN'.Carbon::now()->format('yymdhis').$fields['username'],
            'member' => $fields
        ];
        $transaction_res = self::curl($params, self::getApiUrl('account', 'transfer'));
        return $transaction_res;
    }

    public static function getPoint($fields){
        $randStr = GameAPI::randStr(1);
        $params = [
            'token' => self::sign(self::AGENT_ACCOUNT . self::API_kEY . $randStr),
            'random' => $randStr,
            'member' => $fields
        ];

        $get_point_res = self::curl($params, self::getApiUrl('user', 'getBalance'));
        return $get_point_res;
    }
}
