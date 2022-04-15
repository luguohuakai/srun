<?php

namespace srun\base;

interface User
{
    public function add($user_name, string $user_real_name = '', string $user_password = '123456', int $products_id = 1, int $group_id = 1, string $mgr_name_create = 'SrunApi', array $other = []);

    public function addCumt($user_name, string $user_real_name = '', string $user_password = '123456', int $products_id = 1, int $group_id = 1, string $mgr_name_create = 'SrunApi', array $other = []);

    public function addVisitor($user_name, string $user_real_name = '', string $user_password = '123456', string $mgr_name_create = 'SrunApi', array $other = []);

    public function eventVisitor($user_name, $event_id, string $user_real_name = '', string $user_password = '123456');

    public function addInviteVisitor($user_name, $invite_code, string $user_real_name = '', string $user_password = '123456', string $mgr_name_create = 'SrunApi');

    public function update($user_name, string $user_real_name = '', $email = null, $cert_num = null, $phone = null, $address = null);

    public function view($user_name);

    public function search($value, int $type = 1);

    public function superSearch($param = []);

    public function detail($user_name = null, $add_time = null, $user_ip = null, $per_page = null, $page = null);

    public function resetPassword($user_name, $old_password, $new_password);

    public function superResetPassword($user_name, $new_password);

    public function forgetResetPassword($user_name, $verify_code, $new_password, $way);

    public function code($user_name, $way);

    public function delete($user_name);

    public function sendCode($user_name, $phone = null);

    public function bindingPhone($user_name, $phone, $verify_code);

    public function onlineEquipment($user_name = null, $user_ip = null, $user_mac = null);

    public function onlineDrop($user_name, $drop_type, $rad_online_id);

    public function batchOnlineDrop($user_name);

    public function statusControl($user_name, int $user_available = 1);

    public function statusControlBatch($type, $group_id, $product, int $user_available = 1);

    public function getPassword($user_name);

    public function validate($user_name, $password);

    public function validateManager($username, $password);

    public function resetPasswordManager($username, $password, $new_password);

    public function auth($domain, $auth_type, $type);

    public function authAsync($user_ip, $user_mac, $os_name, $nas_ip, $auth_type);

    public function maxOnlineNum($user_name, $num);

    public function macs($user_name);

    public function createMac($user_name, $mac_address, $device_name, $device_type);

    public function deleteMac($user_name, $mac_address);

    public function updateMac($user_name, $old_address, $new_address, $device_name = null, $device_type = null);

    public function updateVlan($user_name, $type, $old_vlan, $new_vlan);

    public function createMacAuth($user_name, $mac_address);

    public function deleteMacAuth($user_name, $mac_address);

    public function listMacAuth($user_name);

    public function updateMacAuth($user_name, $device_name, $mac_address, $new_address = null);

    public function getOnlineTotal();

    public function createInvite($user_name, $expire_at, $effective_at = null, $num = null, $generate_num = null);

    public function viewInvite($user_name, $code, $page = null, $per_page = null);

    public function useInvite($code, $used_by);

    public function disabledInvite($code, $used_by);

    public function checkModifyPassword($user_name);

    public function viewEventVisitor($random);

    public function addEventVisitor($event_name, $start_at, $stop_at, $product_id, $group_id, $portal_ip, $ac_id, array $other = []);

    public function updateEventVisitor($event_name, array $other = []);

    public function deleteEventVisitor($event_name);
}