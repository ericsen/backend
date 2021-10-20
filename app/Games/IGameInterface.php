<?php

namespace App\Games;

/**
 * GameAPI interface
 * 接入遊戲時統一用的 function 命名
 */
interface IGameInterface
{
    /**
     * 建立使用者
     *
     * @param [type] $fields api params
     *
     * @return void
     */
    public static function createUser($fields);

    /**
     * 遊戲登入
     *
     * @param [type] $fields api params
     *
     * @return void
     */
    public static function login($fields);

    /**
     * 遊戲登出
     *
     * @param [type] $fields api params
     *
     * @return void
     */
    public static function kickPlayer($fields);

    /**
     * 取會員餘額
     *
     * @param [type] $gameUserName api params
     *
     * @return void
     */
    public static function getBalance($gameUserName);

    /**
     * 轉帳
     *
     * @param [type] $fields api params
     *
     * @return void
     */
    public static function transaction($fields);
}
