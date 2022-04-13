<?php

namespace srun\base;

interface User
{
    public function userBalance($user_name);

    public function userView($account);

    public function userDelete($user_name);

    public function userStatusControl($user_name, int $user_available = 1);

    public function getOnlineTotal();

    public function onlineEquipment($user_name = null, $user_ip = null, $user_mac = null);

    public function maxOnlineNum($user_name, $num);

    public function addUser($user_name, string $user_real_name = '', string $user_password = '123456', int $products_id = 1, int $group_id = 1, string $mgr_name_create = 'SrunMeeting', array $other = []);

    public function addUserCumt($user_name, string $user_real_name = '', string $user_password = '123456', int $products_id = 1, int $group_id = 1, string $mgr_name_create = 'SrunMeeting', array $other = []);

}