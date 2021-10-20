<?php

namespace App\Games;

use Illuminate\Support\Facades\DB;
use App\Models\Api\BetLogRaw_9K;
use App\Models\Api\GameUser;
use App\Models\Common\Redis_customer;
use App\Models\Admin\Hg_customer_flow;

class NineK
{
    public $_brand;

    private $_apiUrl;
    private $_apiToken;
    private $_bossId;

    protected $_methodUrl;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 基本資料
        $this->_brand = '9K';
        $this->_apiUrl = 'https://sb-api.9k168.com/api/';
        $this->_apiToken = '4729295aeaa8256ea086c36e1f297cf68697a5e8';
        $this->_bossId = 'CCK_twd';

        // 網址Mapping
        $this->_methodUrl = [
            'createUser' => $this->_apiUrl . $this->_apiToken . '/RegisterUser',
            'changePassword' => $this->_apiUrl . $this->_apiToken . '/ChangePassword',
            'login' => $this->_apiUrl . $this->_apiToken . '/UserLogin',
            'getBalance' => $this->_apiUrl . $this->_apiToken . '/GetUserBalance',
            'transaction' => $this->_apiUrl . $this->_apiToken . '/BalanceTransfer',
            'checkTransaction' => $this->_apiUrl . $this->_apiToken . '/CheckTransfer',
            'syncBet' => $this->_apiUrl . $this->_apiToken . '/BetList',
        ];
    }

    /**
     * 遊戲登入
     *
     * @param [string] $userId 會員編號
     * @param [array] $gameCode 遊戲代碼 (Option)
     * @param [array] $platform 操作平台 (Option)
     *
     * @return array
     */
    public function login($userId, $gameCode = null, $platform = null)
    {
        $gameUser = $this->getGameUser($userId);
        if (is_array($gameUser)) {
            return $gameUser;
        }

        // 準備參數
        $params = [
            'MemberAccount' => $gameUser->Username,
            'MemberPassword' => $gameUser->Password,
        ];
        if (!is_null($gameCode)) {
            $params['GameCode'] = $gameCode;
        }
        if (!is_null($platform)) {
            $params['Platform'] = $platform;
        }

        return $this->runCURL(__FUNCTION__, $this->prepare($params));
    }

    /**
     * 建立會員
     *
     * @param [string] $userId 會員編號
     *
     * @return array
     */
    public function createUser($userId)
    {
        // 在CCK中建立會員資料
        $gUser = new GameUser;
        $gUser->Brand = $this->_brand;
        $gUser->UserId = $userId;
        $gUser->Username = 'sys_' . $userId . '_' . GameUser::randStr(10 - strlen($userId));
        $gUser->Password = GameUser::randStr(8);
        $gUser->save();

        // 在9K中建立會員
        // 準備參數
        $params = [
            'MemberAccount' => $gUser->Username,
            'MemberPassword' => $gUser->Password,
        ];
        $result = $this->runCURL(__FUNCTION__, $this->prepare($params));
        if ($result['success'] < 0) {
            // API 新增失敗
            $gUser->delete();

            $result['data']['params'] = $params;
            $result['data']['instance'] = $gUser;
        }

        return $result;
    }

    /**
     * 變更會員密碼
     *
     * @param [string] $userId 會員編號
     * @param [string] $password 會員密碼
     *
     * @return array
     */
    public function changePassword($userId, $password)
    {
        $gameUser = $this->getGameUser($userId);
        if (is_array($gameUser)) {
            return $gameUser;
        }

        // 準備參數
        $params = [
            'MemberAccount' => $gameUser->Username,
            'MemberPassword' => $gameUser->Password,
        ];
        $result = $this->runCURL(__FUNCTION__, $this->prepare($params));
        if ($result['success'] < 0) {
            // API 新增失敗
            $result['data'] = $gameUser;
        } else {
            $gameUser->Password = $password;
            $gameUser->save();
        }

        return $result;
    }

    /**
     * 取會員餘額
     *
     * @param [string] $userId 會員編號
     *
     * @return array
     */
    public function getBalance($userId)
    {
        $gameUser = $this->getGameUser($userId);
        if (is_array($gameUser)) {
            return $gameUser;
        }

        // 準備參數
        $params = [
            'MemberAccount' => $gameUser->Username,
        ];

        return $this->runCURL(__FUNCTION__, $this->prepare($params));
    }

    /**
     * 玩家充值
     *
     * @param [string] $userId 玩家編號
     *
     * @return array
     */
    public function depositAll($userId)
    {
        // 從資料庫抓取會員目前在娛樂城的money 以後會改成從redis取
        $member_data = DB::table('hg_customer')->find($userId);
        $member_money = intval($member_data->money);

        // 要進入遊戲 先扣除會員的娛樂城money
        $flow = new Hg_customer_flow();
        $money_change = $flow->update_flow($userId, 1, 50, $member_money, 23, '');
        //如果娛樂城扣錢失敗
        if ($money_change['code'] != 200) {
            $money_change['data']['transaction'] = 0;
            return $money_change;
        }

        return $this->transaction($userId, $member_money);
    }

    /**
     * 玩家提款
     *
     * @param [string] $userId 玩家編號
     *
     * @return array
     */
    public function withdrawAll($userId)
    {
        $result = $this->getBalance($userId);
        $member_money = intval($result['data']['GetUserBalance']['Balance']);

        $result = $this->transaction($userId, -$member_money);
        if ($result['success'] < 0) {
            $result['data']['transaction'] = 0;
            return $result;
        }

        $flow = new Hg_customer_flow();
        $money_change = $flow->update_flow($userId, 1, 51, $member_money, 23, '');
        //如果娛樂城加錢失敗
        if ($money_change['code'] != 200) {
            $money_change['data']['transaction'] = 0;

            $this->transaction($userId, $member_money);
        } else
            $money_change['data']['transaction'] = 1;

        return $money_change;
    }

    /**
     * 轉帳
     *
     * @param [string] $userId 會員編號
     * @param [string] $amount 轉帳金額
     * @param [string] $tno CCK這邊的交易單號 (Option)
     *
     * @return array
     */
    public function transaction($userId, $amount, $tno = null)
    {
        $gameUser = $this->getGameUser($userId);
        if (is_array($gameUser)) {
            return $gameUser;
        }

        // 準備參數
        $params = [
            'MemberAccount' => $gameUser->Username,
            'Balance' => $amount,
        ];
        if (!is_null('tno')) {
            $params['TradeNo'] = $tno;
        }
        $result = $this->runCURL(__FUNCTION__, $this->prepare($params));
        if ($result['success'] < 0) {
            // API 新增失敗
            $result['data'] = $gameUser;
            $result['data']['transaction'] = 0;

            $flow = new Hg_customer_flow();
            $flow->update_flow($userId, 1, 51, $amount, 23, '');
        } else
            $result['data']['transaction'] = 1;

        return $result;
    }

    /**
     * 查詢轉帳狀態
     *
     * @param [string] $userId 會員編號
     * @param [string] $tid 9K的的交易單號
     * @param [string] $tno CCK這邊的交易單號 (Option)
     *
     * @return array
     */
    public function checkTransaction($userId, $tid, $tno)
    {
        $gameUser = $this->getGameUser($userId);
        if (is_array($gameUser)) {
            return $gameUser;
        }

        // 準備參數
        $params = [
            'MemberAccount' => $gameUser->Username,
            'TransactionID' => $tid,
        ];
        if (!is_null($tno)) {
            $params['TradeNo'] = $tno;
        }

        return $this->runCURL(__FUNCTION__, $this->prepare($params));
    }

    /**
     * 同步客戶投注資料
     *
     * @param [string] $start 查詢起始時間
     * @param [string] $end 查詢結束時間
     *
     * @return array
     */
    public function syncBet($start, $end)
    {
        // 準備參數
        $params = [
            'StartTime' => $start,
            'EndTime' => $end,
        ];

        $params['Page'] = 0;
        $counter = [
            'createdNumber' => 0,
            'updatedNumber' => 0,
        ];
        do {
            $params['Page'] += 1;
            $result = $this->runCURL(__FUNCTION__, $this->prepare($params));
            if (!array_key_exists('data', $result)) {
                return $result;
            }

            // 存到CCK資料庫
            foreach ($result['data']['BetList'] as $bet) {
                $savedBet = BetLogRaw_9K::updateOrCreate(['WagerID' => $bet['WagerID']], $bet);
                if ($savedBet->wasRecentlyCreated) {
                    $counter['createdNumber'] += 1;
                } else {
                    $counter['updatedNumber'] += 1;
                }
            }
        } while ($result['data']['PageInfo']['ThisPage'] < $result['data']['PageInfo']['TotalPage']);

        return [
            'http_code' => '200',
            'success' => '0',
            'msg' => 'success',
            'data' => [
                'counter' => $counter,
            ],
        ];
    }

    /**
     * 取得玩家帳號資訊
     *
     * @param [string] $userId 在CCK裡的UserID
     *
     * @return array
     */
    private function getGameUser($userId)
    {
        $gameUser = GameUser::getUserObj($this->_brand, $userId);

        if (is_null($gameUser)) {
            $result = $this->createUser($userId);
            $gameUser = GameUser::getUserObj($this->_brand, $userId);
            if (is_null($gameUser)) {
                return $result;
            }
        }

        return $gameUser;
    }

    /**
     * 將參數轉成9K要的規格
     *
     * @param [array] $data WebAPI參數
     *
     * @return array
     */
    private function prepare($data)
    {
        $data['BossID'] = $this->_bossId;

        return $data;
    }

    /**
     * 執行cURL進行呼叫Web API
     *
     * @param [string] $funcName 執行方法
     * @param [array] $params 參數
     *
     * @return array
     */
    private function runCURL($funcName, $params)
    {
        $apiUrl = $this->_methodUrl[$funcName];

        $requestLog = [
            'brand' => $this->_brand,
            'method' => $funcName,
            'url' => $apiUrl,
            'params' => $params,
        ];
        GameAPI::log($this->_brand, $funcName, 'request', $requestLog, 'GAME_API');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $result = json_decode(curl_exec($curl), true);

        // 檢查Http錯誤
        $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $result['http_code'] = $http_status_code;
        if (curl_errno($curl)) {
            $curlError = [
                'brand' => $this->_brand,
                'method' => $funcName,
                'error' => curl_error($curl),
            ];

            GameAPI::log($this->_brand, $funcName, 'curl', $curlError, 'GAME_API');
            throw new \Exception($curlError['error'], $http_status_code);
        }

        $responseLog = [
            'brand' => $this->_brand,
            'method' => $funcName,
            'result' => $result,
        ];
        // response log
        GameAPI::log($this->_brand, $funcName, 'response', $responseLog, 'GAME_API');

        return $result;
    }
}
