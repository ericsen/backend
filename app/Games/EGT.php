<?php

namespace App\Games;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Api\BetLogRaw_9K;
use App\Models\Common\Redis_customer;
use App\Models\Admin\Hg_customer_flow;
use Exception;

class EGT
{
    public $_brand;

    private $_apiUrl;
    private $_machineId;

    protected $_methodUrl;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        // 基本資料
        $this->_brand = 'EGT';
        $this->_apiUrl = 'http://125.227.200.196:8090/egt';
    }

    /**
     * 取得遊戲資訊
     *
     * @param int $mid    遊戲編號
     *
     * @return array
     */
    public function getInfo(int $mid)
    {
        $result = DB::table('g_gamelist')
            ->where('GameCode', "SLOT-EGT-$mid")
            ->first();

        return $result;
    }

    /**
     * 變更遊戲狀態
     *
     * @param int $mid    遊戲編號
     * @param int $status 遊戲狀態
     *
     * @return array
     */
    public function setStatus(int $cid, int $mid, int $status)
    {
        DB::table('g_gamelist')
            ->where('GameCode', "SLOT-EGT-$mid")
            ->update(['GameTech' => $cid, 'Status' => $status]);

        return [
            'db' => DB::table('g_gamelist')->where('GameCode', "SLOT-EGT-$mid")->first(),
            'data' => [
                'mid' => $mid,
                'status' => $status == 1 ? 'On' : ($status == 2 ? 'Busy' : 'Off')
            ]
        ];
    }

    /**
     * 玩家轉帳
     *
     * @param string $userId    玩家編號
     * @param number $type      轉帳類型 (0：CCK -> EGT；1：EGT -> CCK)
     * @param number $amount    金額
     *
     * @return array
     */
    public function transaction($userId, $type, $amount)
    {
        $flow = new Hg_customer_flow();
        $money_change = $flow->update_flow($userId, 1, $type == 1 ? 51 : 50, $amount, 31, '');
        //如果娛樂城扣錢失敗
        if ($money_change['code'] != 200) {
            $money_change['data']['transaction'] = 0;
            return $money_change;
        }

        return [
            'code' => 200,
            'data' => [
                'userId' => $userId,
                'type' => $type,
                'amount' => $amount,
                'transaction' => 1,
            ]
        ];
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
            $result = []; //$this->runCURL(__FUNCTION__, $params);
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
}
