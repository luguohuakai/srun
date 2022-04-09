<?php

namespace src;

interface SrunBase
{
    public static function req($path, array $data = [], string $method = 'get', bool $access_token = true, array $header = []);

    public static function financialRechargeWallet($user_name, $amount, $order_no);

    public static function userBalance($user_name);

    public static function userView($account);

    public static function addGroup($group_name, $parent_name = '/');

    public static function userExist($user_name): bool;

    public static function userRight($user_name, $pwd): bool;

    public static function userDelete($user_name);

    public static function usersPackages($user_name);

    public static function addUser($user_name, string $user_real_name = '', string $user_password = '123456', int $products_id = 1, int $group_id = 1, string $mgr_name_create = 'SrunMeeting', array $other = []);

    public static function addUserCumt($user_name, string $user_real_name = '', string $user_password = '123456', int $products_id = 1, int $group_id = 1, string $mgr_name_create = 'SrunMeeting', array $other = []);

    public static function accessToken();

    static function postSso($url, $post_data);

    public static function ssoLogin($auth_addr, $secret, $user_name, $ip, $ac_id = null): string;

    public static function ssoDrop($auth_addr, $secret, $user_name, $ip, $ac_id = null): string;

    public static function sso(string $username, string $ip, $ac_id = null, $drop = null): string;

    public static function getAuthMsg($rs): array;

    public static function keyHandleLog($msg, bool $next = false, string $file = '');

    public static function getOnlineTotal();

    public static function onlineEquipment($user_name = null, $user_ip = null, $user_mac = null);

    public static function userStatusControl($user_name, int $user_available = 1);

    public static function maxOnlineNum($user_name, $num);
}