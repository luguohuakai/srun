<?php

namespace src;

interface SrunBase
{
    public function req($path, array $data = [], string $method = 'get', bool $access_token = true, array $header = []);

    public function financialRechargeWallet($user_name, $amount, $order_no);

    public function userBalance($user_name);

    public function userView($account);

    public function addGroup($group_name, $parent_name = '/');

    public function userExist($user_name): bool;

    public function userRight($user_name, $pwd): bool;

    public function userDelete($user_name);

    public function usersPackages($user_name);

    public function addUser($user_name, string $user_real_name = '', string $user_password = '123456', int $products_id = 1, int $group_id = 1, string $mgr_name_create = 'SrunMeeting', array $other = []);

    public function addUserCumt($user_name, string $user_real_name = '', string $user_password = '123456', int $products_id = 1, int $group_id = 1, string $mgr_name_create = 'SrunMeeting', array $other = []);

    public function accessToken();

    public function postSso($url, $post_data);

    public function ssoLogin($auth_addr, $secret, $user_name, $ip, $ac_id = null): string;

    public function ssoDrop($auth_addr, $secret, $user_name, $ip, $ac_id = null): string;

    public function sso(string $username, string $ip, $ac_id = null, $drop = null): string;

    public function getAuthMsg($rs): array;

    public function keyHandleLog($msg, bool $next = false, string $file = '');

    public function getOnlineTotal();

    public function onlineEquipment($user_name = null, $user_ip = null, $user_mac = null);

    public function userStatusControl($user_name, int $user_available = 1);

    public function maxOnlineNum($user_name, $num);
}