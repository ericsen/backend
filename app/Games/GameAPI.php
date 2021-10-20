<?php

namespace App\Games;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use File;
use App\Models\GameProvider;
use App\Models\GameUser;
use App\Models\GameList;

/**
 * 遊戲平台共用
 */
class GameAPI
{
    /**
     * 亂數字串
     *
     * @param int    $length 字串長度
     * @param string $chars  亂數字串
     *
     * @return string
     */
    public static function randStr($length, $chars = 'abcdefhijkmnprstwxyz2345678')
    {
        $hash = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }

    /**
     * API Log
     *
     * @param string $brand   遊戲商名稱
     * @param string $method  api method
     * @param string $type    log 類型
     * @param array  $content log content
     *
     * @return void
     */
    public static function log($brand, $method, $type, $content, $filePrefix = 'API')
    {
        $phpSapiName = (php_sapi_name() == 'cli') ? 'cli' : 'web';
        // 檔案路徑
        $logPrefix = $filePrefix . '_' . $phpSapiName;
        $log_file_path = storage_path('/logs/' . $logPrefix . '_' . date("Y-m-d") . '.log');

        // 記錄當時的時間
        $log_info = [
            'time' => date("Y-m-d H:i:s"),
            'brand' => $brand,
            'method' => $method,
            'type' => $type,
            'log' => $content,
        ];

        // 記錄 JSON 字串
        $log_info_json = json_encode($log_info, JSON_UNESCAPED_UNICODE) . "\r\n";

        // 記錄 Log
        File::append($log_file_path, $log_info_json);
    }

    /**
     * 錯誤訊息
     *
     * @param int    $code 錯誤代碼
     * @param string $msg  錯誤訊息
     *
     * @return json
     */
    public static function errorMsg($code, $msg)
    {
        // 1001 => '參數錯誤'

        $apiReturn = [
            'result' => 'error',
            'code' => $code,
            'msg' => $msg,
        ];

        return response()->json($apiReturn);
    }

    /**
     * 機器人警示通知
     *
     * @param string $msg 訊息內容
     *
     * @return void
     */
    public static function botNotice($msg)
    {
        Redis::LPUSH('BOT_NOTICE', $msg);
    }

    /**
     * 檢查遊戲商是否有開啟
     *
     * @param string  $brand  遊戲商代號
     * @param boolean $return 是否回傳
     *
     * @return void
     */
    public static function checkProvider($brand, $return = false)
    {
        $result = GameProvider::select('Enable')
            ->where('Code', $brand)
            ->first();

        $status = 'N';
        if (!is_null($result)) {
            $status = $result->Enable;
        }

        if (!$return && $status == 'N') {
            die('brand not support');
        }

        if ($return) {
            return $status;
        }
    }

    /**
     * Redis 阻擋重複訂單請求
     *
     * @param string $orderId 訂單號
     *
     * @return string
     */
    public static function checkOrderId($orderId)
    {
        $incr = 0;
        $incr = Redis::INCR($orderId);
        Redis::EXPIRE($orderId, 10);

        return $incr;
    }

    /**
     * 取遊戲會員帳號
     *
     * @param string $brand 遊戲商
     *
     * @return string
     */
    public static function getUserList($brand)
    {
        $result = DB::connection('game')
            ->table('G_GameUsers')
            ->select('Username')
            ->where('Brand', $brand)
            ->get();

        $gameUser = [];
        foreach ($result as $obj) {
            $gameUser[$obj->Username] = $obj->Username;
        }

        return $gameUser;
    }

    /**
     * 取遊戲商會員帳號
     *
     * @param string $brand  遊戲商代號
     * @param string $userId 會員ID
     *
     * @return object
     */
    public static function getGameUser($brand, $userId)
    {
        try {
            // get user
            $gameUser =  GameUser::where('UserId', $userId)
                ->where('Brand', $brand)
                ->first();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('sql error:', [$e->getMessage()]);
            throw new HttpResponseException(self::errorMsg(999, 'system error'));
        }

        return $gameUser;
    }

    /**
     * 檢查遊戲商會員帳號是否存在，不存在時自動建立帳號
     *
     * @param string $brand    遊戲商代號
     * @param string $userId   會員ID
     * @param string $username 會員帳號
     * @param string $userIp   會員 IP
     *
     * @return array
     */
    public static function checkGameUser($brand, $userId, $username, $userIp)
    {
        // 檢查 gameuser 是否存在
        $gameUser = GameUser::getUserObj($brand, $userId);
        $playerName = '';
        $password = '';
        if (is_null($gameUser)) {
            // create user
            DB::connection('game')->beginTransaction();

            $length = 16 - strlen($username);
            $randStr = GameUser::randStr($length);
            $playerName = $username . $randStr;
            $password = GameUser::randStr(8);

            // create db user
            $data = [
                'Brand' => $brand,
                'UserId' => $userId,
                'Username' => $playerName,
                'Password' => $password
            ];
            DB::connection('game')->table('G_GameUsers')->insert($data);

            // create api user
            $fields = [
                'username' => $playerName,
                'password' => $password,
                'userIp' => $userIp,
                'brand' => $brand,
            ];

            if (in_array($brand, ['PT', 'IMSB'])) {
                // IMOne 遊戲商
                $gameClass = 'App\Games\IMOne';
            } else {
                // 各家遊戲的 API
                $gameClass = 'App\Games\\'.$brand;
            }

            $createUser = [];
            $createUser = $gameClass::checkUser($fields);

            if ($createUser) {
                DB::connection('game')->commit();
            } else {
                // API 新增失敗
                DB::connection('game')->rollBack();
                Log::error($brand . ' create user error', [$createUser]);
                return GameAPI::errorMsg(1003, 'create user api error');
            }
        } else {
            $playerName = $gameUser->Username;
            $password = $gameUser->Password;
        }

        $result = [
            'username' => $playerName,
            'password' => $password
        ];

        return $result;
    }

    /**
     * 檢查遊戲商的遊戲狀態
     *
     * @param string $brand    遊戲商代碼
     * @param string $gameCode 遊戲編號
     *
     * @return array
     */
    public static function checkGameCode($brand, $gameCode)
    {
        try {
            // get game type
            $gameResult = GameList::select('GameTypeId', 'GameId', 'GameStatus')
                ->where('Brand', $brand)
                ->where('GameCode', $gameCode)
                ->first();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('sql error:', [$e->getMessage()]);
            return GameAPI::errorMsg(999, 'system error');
        }

        if (is_null($gameResult)) {
            return GameAPI::errorMsg(1004, 'game_id not exist');
        }

        if ($gameResult->GameStatus == 'N') {
            // 遊戲停用
            return GameAPI::errorMsg(1005, 'this game is unavailable');
        }

        return $gameResult;
    }

    /**
     * 檢查遊戲商的遊戲狀態
     *
     * @param string $brand  遊戲商代碼
     * @param string $gameId 遊戲編號
     *
     * @return array
     */
    public static function checkGameId($brand, $gameId)
    {
        try {
            // get game type
            $gameResult = GameList::select('GameTypeId', 'GameCode', 'GameStatus')
                ->where('Brand', $brand)
                ->where('GameId', $gameId)
                ->first();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('sql error:', [$e->getMessage()]);
            $msg = GameAPI::errorMsg(999, 'system error');
            throw new HttpResponseException($msg);
        }

        if (is_null($gameResult)) {
            $msg = GameAPI::errorMsg(1004, 'game_id not exist');
            throw new HttpResponseException($msg);
        }

        if ($gameResult->GameStatus == 'N') {
            // 遊戲停用
            $msg = GameAPI::errorMsg(1005, 'this game is unavailable');
            throw new HttpResponseException($msg);
        }

        return $gameResult;
    }
}
