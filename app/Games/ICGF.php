<?php

namespace App\Games;

use Illuminate\Support\Facades\DB;
use App\Models\Api\BetLogRaw_ICGF;
use App\Models\Api\GameUser;
use App\Models\Common\Redis_customer;
use App\Models\Admin\Hg_customer_flow;
use Illuminate\Http\Request;
use Exception;


class ICGF
{
    private $_brand;

    private $_version;
    private $_apiUrl;
    private $_platformId;
    private $_tenantId;
    private $_tenantKey;

    private $request;
    private $_method;
    protected $_methodUrl;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        // 基本資料
        $this->_brand = 'ICGF';
        $this->_apiUrl = 'https://admin-stage.iconic-gaming.com/service/';
        

        $this->request = $request;

        // 網址Mapping
        $this->_methodUrl = [
            'createUser' => 'https://admin-stage.iconic-gaming.com/service/createuser',
            'getLoginUrl' => 'https://launcher-stage.iconic-gaming.com/play/cm00019?openExternalBrowser=1&platform=619&hideCurrency=true&hideRoundId=true&lang=zh&hideHome=true&hideHistory=true',
            'kickPlayer' => 'https://admin-stage.iconic-gaming.com/service/kickuser',
            'deposit' => 'https://admin-stage.iconic-gaming.com/service/api/v1/players/deposit',
            'withdraw' => 'https://admin-stage.iconic-gaming.com/service/withdraw',
            'getBalance' => 'https://admin-stage.iconic-gaming.com/service/getbalance',
            'syncBet' => 'https://admin-stage.iconic-gaming.com/service/betlist',
            
        ];
        // // 語系Mapping
        // $this->_lang = [
        //     'zh_tw' => 'TWN',
        //     //'zh_cn' => 'CN',
        //     //'jp' => 'JPN',
        // ];
    }

    /**
     * 遊戲登入
     *
     * @param [string] $userId 玩家編號
     * @param [string] $gameCode 遊戲代碼
     * @param [number] $odds 玩家賠率
     * @param [string] $backUrl Callback URL
     *
     * @return string
     */
    public function getLoginUrl($userId, $gameCode = null,  $backUrl = '')
    {
        $gameUser = $this->getGameUser($userId);
        if (is_array($gameUser)) {
            return $gameUser;
        }

        // 準備參數
        // $params = [
        //     'username' => $gameUser->Username,
        //     'logintime' => round(microtime(true) * 1000),
            
        // ];
        // if (!is_null($gameCode)) {
        //     $params['gameid'] = $gameCode;
        // }
        // if (!is_null($backUrl)) {
        //     $params['backurl'] = '?openExternalBrowser=1&platform=619&hideCurrency=true&hideRoundId=true&lang=zh&hideHome=true&hideHistory=true';
        // }
        //$paramsString = http_build_query($this->prepare($params));
        //$paramsString = ?openExternalBrowser=1&platform=619&hideCurrency=true&hideRoundId=true&lang=zh&hideHome=true&hideHistory=true;
        //$gameCode = $request->gameCode;
        //$loginUrl = 'https://launcher-stage.iconic-gaming.com/play/'. $gameCode .'?openExternalBrowser=1&platform=619&hideCurrency=true&hideRoundId=true&lang=zh&hideHome=true&hideHistory=true';
        return response()->json([
            'code' => '200',
            'message' => 'Success',
            'data' => [
                'loginUrl' => 'https://launcher-stage.iconic-gaming.com/play/cm00019?openExternalBrowser=1&platform=619&hideCurrency=true&hideRoundId=true&lang=zh&hideHome=true&hideHistory=true',
            ],
        ]);
        //return "{$this->_methodUrl[__FUNCTION__]}?{$paramsString}";
        // return $this->runCURL(__FUNCTION__, $this->prepare($params), 'get');
    }

    /**
     * 建立玩家
     *
     * @param [string] $userId 玩家編號
     * @param [number] $type 玩家類別；0：正式玩家、1：測試玩家
     *
     * @return array
     */
    public function createUser($userId, $type = 1)
    {
        // 在CCK中建立ICG玩家
        $gUser = new GameUser;
        $gUser->Brand = $this->_brand;
        $gUser->UserId = $userId;
        $gUser->Username = 'sys_' . $userId . '_' . GameUser::randStr(10 - strlen($userId));
        $gUser->Password = GameUser::randStr(8);
        $gUser->save();

        // 在icg中建立玩家
        // 準備參數
        $params = [
            'username' => $gUser->Username,
            // 'usertype' => $type,
            'usertype' => 0,
            'countrycode' => 'TWN',
            'currencycode' => 'TWD',
        ];
        // if ($this->request->has('lang')) {
        //     $params['countrycode'] = array_key_exists($this->request->get('lang'), $this->_lang) ? $this->_lang[$this->request->get('lang')] : 'TWN';
        // }
        // if ($this->request->has('currency')) {
        //     $params['currencycode'] = $this->request->get('currency');
        // }
        $result = $this->runCURL(__FUNCTION__, $this->prepare($params));
        if ($result['data']['status'] != 1) {
            // API 新增失敗
            $gUser->delete();

            $result['data']['params'] = $params;
            $result['data']['instance'] = $gUser;
        }

        return $result;
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
            'username' => $gameUser->Username,
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
        $money_change = $flow->update_flow($userId, 1, 50, $member_money, 24, '');
        //如果娛樂城扣錢失敗
        if ($money_change['code'] != 200) {
            $money_change['data']['transaction'] = 0;
            return $money_change;
        }

        return $this->deposit($userId,  $member_money);
    }

    /**
     * 玩家充值
     *
     * @param [string] $userId 玩家編號
     * @param [number] $amount 金額
     * @param [string] $orderid 充值訂單ID
     * @param [string] $currencycode 幣值 (Option)
     *
     * @return array
     */
    public function deposit($userId, $amount, $orderid = '', $currencycode = 'TWD')
    {
        $gameUser = $this->getGameUser($userId);
        if (is_array($gameUser)) {
            return $gameUser;
        }

        // 準備參數
        $params = [
            'username' => $gameUser->Username,
            'amount' => $amount,
            'orderid' => GameUser::randStr(8),
            'deposittime' => round(microtime(true) * 1000),
            'currencycode' => $currencycode,
        ];
        $result = $this->runCURL(__FUNCTION__, $this->prepare($params));
        if ($result['data']['status'] != 1) {
            // API 新增失敗
            $result['data']['transaction'] = 0;
            $result['data']['params'] = $params;

            $flow = new Hg_customer_flow();
            $money_change = $flow->update_flow($userId, 1, 51, $amount, 24, '');
        } else
            $result['data']['transaction'] = 1;

        return $result;
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
        $member_money = intval($result['data']['available_balance']);

        $result = $this->withdraw($userId,  $member_money);
        if ($result['data']['status'] != 1) {
            $result['data']['transaction'] = 0;
            return $result;
        }

        $flow = new Hg_customer_flow();
        $money_change = $flow->update_flow($userId, 1, 51, $member_money, 24, '');
        //如果娛樂城加錢失敗
        if ($money_change['code'] != 200) {
            $money_change['data']['transaction'] = 0;
            
            $this->deposit($userId,  $member_money);
        } else
            $money_change['data']['transaction'] = 1;

        return $money_change;
    }

    /**
     * 玩家提款
     *
     * @param [string] $userId 玩家編號
     * @param [number] $amount 金額
     * @param [string] $orderid 充值訂單ID
     * @param [string] $currencycode 幣值 (Option)
     *
     * @return array
     */
    public function withdraw($userId, $amount, $orderid = '', $currencycode = 'TWD')
    {
        $gameUser = $this->getGameUser($userId);
        if (is_array($gameUser)) {
            return $gameUser;
        }

        // 準備參數
        $params = [
            'username' => $gameUser->Username,
            'amount' => $amount,
            'orderid' => GameUser::randStr(8),
            'withdrawtime' => round(microtime(true) * 1000),
            'currencycode' => $currencycode,
        ];
        $result = $this->runCURL(__FUNCTION__, $this->prepare($params));
        if ($result['data']['status'] != 1) {
            // API 新增失敗
            $result['data']['transaction'] = 0;
            $result['data']['params'] = $params;
        } else
            $result['data']['transaction'] = 1;

        return $result;
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
            'username' => $gameUser->Username,
        ];

        return $this->runCURL(__FUNCTION__, $this->prepare($params));
    }

    /**
     * 同步玩家投注資料和追號資料
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
            'starttime' => $start,
            'endtime' => $end,
            'pagesize' => 1000,
        ];
        $syncFunctions = ['syncBet', 'syncOfficialBet', 'syncPrebuy', 'syncOfficialPrebuy'];

        $counter = [
            'createdNumber' => 0,
            'updatedNumber' => 0,
        ];
        foreach ($syncFunctions as $syncFunction) {
            $params['pagenumber'] = 0;
            do {
                $params['pagenumber'] += 1;
                $result = $this->runCURL($syncFunction, $this->prepare($params));
                if ($result['code'] != 200) {
                    $result['syncFunction'] = $syncFunction;
                    return $result;
                }

                // 存到CCK資料庫
                foreach ($result['rows'] as $row) {
                    $saved = BetLogRaw_KK::updateOrCreate(['bet_id' => $row['bet_id']], $row);
                    if ($saved->wasRecentlyCreated) {
                        $counter['createdNumber'] += 1;
                    } else {
                        $counter['updatedNumber'] += 1;
                    }
                }
            } while ($result['current'] < $result['pages']);
        }

        return [
            'http_code' => '200',
            'code' => '1',
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
     * 將參數轉成KK要的規格，其中傳入的參數需要以DES加密
     *
     * @param [array] $data WebAPI參數
     *
     * @return array
     */
    private function prepare($data)
    {
        $data['platformid'] = $this->_platformId;
        $params = [
            'version' => $this->_version,
            'id' => $this->_tenantId,
        ];

        // DES加密
        $des = new DES(utf8_encode($this->_tenantKey), 'DES-ECB', DES::OUTPUT_BASE64);
        $cipherText = $des->encrypt(json_encode($data));
        // 將密文進行重新編碼。Base64 -> Utf8 -> URL
        $params['data'] = urlencode(utf8_encode($cipherText));

        return $params;
    }

    /**
     * 執行cURL進行呼叫Web API
     *
     * @param [string] $apiUrl 請求網址
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
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($params)),
        ]);
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
            throw new Exception($curlError['error'], $http_status_code);
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
