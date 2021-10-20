<?php

namespace App\Games;

use Illuminate\Support\Facades\DB;
use App\Models\Api\BetLogRaw_VSL;
use App\Models\Api\GameUser;
use App\Models\Common\Redis_customer;
use App\Models\Admin\Hg_customer_flow;
use Illuminate\Http\Request;
use SoapClient;
use Exception;

class VSL
{
    private $_brand;

    private $_partnerId;
    private $_partnerPassword;
    private $_accountPrefix;

    private $request;
    private $webService;
    protected $_methodName;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        // 基本資料
        $this->_brand = 'VSL';
        $this->_partnerId = 385;
        $this->_partnerPassword = '937135';
        $this->_accountPrefix = 'LKA';

        $this->request = $request;
        $this->webService = new SoapClient("http://api_live_v_tw.vsl39.net/VApiWs.asmx?wsdl");

        // 網址Mapping
        $this->_methodName = [
            'createUser' => 'CreatePlayerAccount',
            'setAllowBet' => 'SetAllowBet',
            'kickPlayer' => 'KickOutPlayer',
            'login' => 'GetLoginUrl',
            'transaction' => 'DepositWithdrawRef',
            'getBalance' => 'GetPlayerBalance',
            'syncBet' => 'GetBetTransaction',
        ];
    }

    /**
     * 遊戲登入
     *
     * @param [string] $userId 會員編號
     * @param [array] $lang 語系，預設簡中
     *
     * @return array
     */
    public function login($userId, $lang = 'zh-Hans')
    {
        $gameUser = $this->getGameUser($userId);
        if (is_array($gameUser)) {
            return $gameUser;
        }

        // 準備參數
        $params = [
            'userName' => $gameUser->Username,
            'password' => $gameUser->Password,
            'lang' => $lang,
        ];

        // 執行Web Service
        return $this->doWebService(__FUNCTION__, $params);
    }

    /**
     * 建立玩家
     *
     * @param [string] $userId 玩家編號
     * @param [number] $type 玩家類別；0：正式玩家、1：測試玩家
     *
     * @return array
     */
    public function createUser($userId)
    {
        // 在CCK中建立玩家資料
        $userName = $userId . GameUser::randStr(15 - strlen($userId));
        $gUser = new GameUser;
        $gUser->Brand = $this->_brand;
        $gUser->UserId = $userId;
        $gUser->Username = $this->_accountPrefix . $userName;
        $gUser->Password = GameUser::randStr(8);
        $gUser->save();

        // 在VSL中建立玩家
        // 準備參數
        $params = [
            'userName' => $userName,
            'password' => $gUser->Password,
            'firstName' => $userName,
            'lastName' => 'CCK',
        ];
        if ($this->request->has('currency')) {
            $params['currencyCode'] = $this->request->get('currency');
        }

        // 執行Web Service
        $result = $this->doWebService(__FUNCTION__, $params);
        if ($result['message'] > 0) {
            // API 新增失敗
            $gUser->delete();

            $result['data']->params = $params;
            $result['data']->instance = $gUser;
        }

        return $result;
    }

    /**
     * 遊戲登出
     *
     * @param [string] $userId 玩家編號
     * @param [string] $isAllow 是否允許
     *
     * @return array
     */
    public function setAllowBet($userId, $isAllow = true)
    {
        $gameUser = $this->getGameUser($userId);
        if (is_array($gameUser)) {
            return $gameUser;
        }

        // 準備參數
        $params = [
            'userName' => $gameUser->Username,
            'isAllowBet' => $isAllow,
        ];

        // 執行Web Service
        return $this->doWebService(__FUNCTION__, $params);
    }

    /**
     * 遊戲登出
     *
     * @param [string] $userId 玩家編號
     *
     * @return array
     */
    public function kickPlayer($userId)
    {
        $gameUser = $this->getGameUser($userId);
        if (is_array($gameUser)) {
            return $gameUser;
        }

        // 準備參數
        $params = [
            'userName' => $gameUser->Username,
        ];

        // 執行Web Service
        return $this->doWebService(__FUNCTION__, $params);
    }

    /**
     * 取玩家餘額
     *
     * @param [string] $userId 玩家編號
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
            'userName' => $gameUser->Username,
        ];

        // 執行Web Service
        return $this->doWebService(__FUNCTION__, $params);
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
        $money_change = $flow->update_flow($userId, 1, 50, $member_money, 28, '');
        //如果娛樂城扣錢失敗
        if ($money_change['code'] != 200) {
            $money_change['data']['transaction'] = 0;
            return $money_change;
        }

        return $this->transaction($userId,  $member_money);
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
        $member_money = intval($result['data']->balance);

        $result = $this->transaction($userId,  -$member_money);
        if ($result['data']->DepositWithdrawRefResult != 0) {
            return $result;
        }

        $flow = new Hg_customer_flow();
        $money_change = $flow->update_flow($userId, 1, 51, $member_money, 28, '');
        //如果娛樂城加錢失敗
        if ($money_change['code'] != 200) {
            $money_change['data']['transaction'] = 0;

            $result = $this->transaction($userId,  $member_money);
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
            'userName' => $gameUser->Username,
            'amount' => $amount,
        ];
        if (!is_null('tno')) {
            $params['clientRefTransId'] = $tno;
        }

        // 執行Web Service
        $result = $this->doWebService(__FUNCTION__, $params);
        if ($result['data']->DepositWithdrawRefResult != 0) {
            // API 新增失敗
            $result['data']->transaction = 0;
            $result['data']->params = $params;

            $flow = new Hg_customer_flow();
            $flow->update_flow($userId, 1, 51, $amount, 28, '');
        } else
            $result['data']->transaction = 1;

        return $result;
    }

    /**
     * 同步玩家投注資料
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
            'fromDate' => $start,
            'toDate' => $end,
        ];

        $params['fromRowNo'] = 0;
        $counter = [
            'createdNumber' => 0,
            'updatedNumber' => 0,
        ];
        do {
            $result = $this->doWebService(__FUNCTION__, $params);
            if (!array_key_exists('data', $result)) {
                return $result;
            }

            // 存到CCK資料庫
            foreach ($result['data']->trans->Trans->TransactionDetail as $bet) {
                $savedBet = BetLogRaw_VSL::updateOrCreate(['FetchId' => $bet->FetchId], (array) $bet);
                if ($savedBet->wasRecentlyCreated) {
                    $counter['createdNumber'] += 1;
                } else {
                    $counter['updatedNumber'] += 1;
                }

                $params['fromRowNo'] = $bet->FetchId + 1;
            }
        } while ($result['data']->totalRows == 100);

        return [
            'code' => '200',
            'success' => '1',
            'message' => 'success',
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

            // 允許下單
            $this->setAllowBet($userId);
        }

        return $gameUser;
    }

    /**
     * 將參數轉成VSL要的規格
     *
     * @param [array] $data WebAPI參數
     *
     * @return array
     */
    private function prepare($data)
    {
        $data['partnerId'] = $this->_partnerId;
        $data['partnerPassword'] = $this->_partnerPassword;

        return $data;
    }

    /**
     * 呼叫Web Service
     *
     * @param [string] $funcName 執行方法名稱
     * @param [array] $params 參數
     *
     * @return array
     */
    private function doWebService($funcName, $params)
    {
        $serviceName = $this->_methodName[$funcName];
        try {
            $params = array($this->prepare($params));

            // request log
            $requestLog = [
                'brand' => $this->_brand,
                'method' => $funcName,
                'params' => $params,
            ];
            GameAPI::log($this->_brand, $funcName, 'request', $requestLog, 'GAME_API');

            // do web service
            $result = $this->webService->__soapCall($serviceName, $params);

            // response log
            $responseLog = [
                'brand' => $this->_brand,
                'method' => $funcName,
                'result' => $result,
            ];
            GameAPI::log($this->_brand, $funcName, 'response', $responseLog, 'GAME_API');

            return [
                'code' => 200,
                'message' => isset($result->{"{$serviceName}Result"}) ? $result->{"{$serviceName}Result"} : $result->errorCode,
                'data' => $result,
            ];
        } catch (Exception $e) {
            return [
                'code' => -1,
                'message' => $e->getMessage(),
            ];
        }
    }
}
