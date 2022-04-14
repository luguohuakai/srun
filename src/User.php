<?php

namespace srun\src;

class User extends Srun implements \srun\base\User
{
    public function __construct($srun_north_api_url, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        parent::__construct($srun_north_api_url, $srun_north_access_token, $srun_north_access_token_expire, $srun_north_access_token_redis_key);
    }

    /**
     * (北向接口)查询账号详情
     * @param $user_name
     * @return object|string
     */
    public function view($user_name)
    {
        return $this->req('api/v1/user/view', ['user_name' => $user_name]);
    }

    /**
     * (北向接口)销户
     * @param $user_name
     * @return object|string
     */
    public function delete($user_name)
    {
        return $this->req('api/v1/user/delete', ['user_name' => $user_name], 'delete');
    }

    /**
     * 开启/暂停接口
     * 0-正常
     * 1-禁用
     * 2-停机保号
     * 3-暂停
     * 4-未开通
     * 场景1:微信访客, user_available = 0
     * @param $user_name
     * @param int $user_available
     * @return object|string
     */
    public function statusControl($user_name, int $user_available = 1)
    {
        return $this->req('api/v1/user/user-status-control', [
            'user_name' => $user_name,
            'user_available' => $user_available
        ], 'post');
    }

    // 修改最大在线数接口
    public function maxOnlineNum($user_name, $num)
    {
        return $this->req('api/v1/user/max-online-num', [
            'user_name' => $user_name,
            'max_online_num' => $num
        ], 'post');
    }

    // 查询在线设备接口
    public function onlineEquipment($user_name = null, $user_ip = null, $user_mac = null)
    {
        if ($user_mac !== null) $data = ['user_mac' => $user_mac];
        if ($user_ip !== null) $data = ['user_ip' => $user_ip];
        if ($user_name !== null) $data = ['user_name' => $user_name];
        if (!isset($data)) return '参数不正确';
        return $this->req('api/v1/base/online-equipment', $data);
    }

    // 获取当前在线终端数量接口
    public function getOnlineTotal()
    {
        return $this->req('api/v1/base/get-online-total');
    }

    /**
     * (北向接口)创建账号
     * @param $user_name
     * @param string $user_real_name
     * @param string $user_password
     * @param int $products_id
     * @param int $group_id
     * @param string $mgr_name_create
     * @param array $other
     * @return object|string
     */
    public function add($user_name, string $user_real_name = '', string $user_password = '123456', int $products_id = 1, int $group_id = 1, string $mgr_name_create = 'SrunApi', array $other = [])
    {
        $data = [
            'user_name' => $user_name,
            'user_real_name' => $user_real_name,
            'user_password' => $user_password,
            'group_id' => $group_id,
            'products_id' => $products_id,
            'mgr_name_create' => $mgr_name_create
        ];
        if (!empty($other)) $data = array_merge($data, $other);
        return $this->req('api/v1/users', $data, 'post');
    }

    /**
     * (北向接口)创建账号 中国矿业大学定制北向接口
     * @param $user_name
     * @param string $user_real_name
     * @param string $user_password
     * @param int $products_id
     * @param int $group_id
     * @param string $mgr_name_create
     * @param array $other
     * @return object|string
     */
    public function addCumt($user_name, string $user_real_name = '', string $user_password = '123456', int $products_id = 1, int $group_id = 1, string $mgr_name_create = 'SrunApi', array $other = [])
    {
        $data = [
            'user_name' => $user_name,
            'user_real_name' => $user_real_name,
            'user_password' => $user_password,
            'group_id' => $group_id,
            'products_id' => $products_id,
            'mgr_name_create' => $mgr_name_create,
            'mac_auth' => 1
        ];
        if (!empty($other)) $data = array_merge($data, $other);
        if (!$data['group_id']) $data['group_id'] = '2';
        if (!$data['sex']) $data['sex'] = '0';
        return $this->req('api/cumt/user/sync', $data, 'post');
    }

    /**
     * @param $user_name
     * @param $event_id
     * @param string $user_real_name
     * @param string $user_password
     * @return object|string
     */
    public function eventVisitor($user_name, $event_id, string $user_real_name = '', string $user_password = '123456')
    {
        return $this->req('', ['user_name' => $user_name, 'event_id' => $event_id, 'user_real_name' => $user_real_name, 'user_password' => $user_password], 'post');
    }

    /**
     * @param $user_name
     * @param string $user_real_name
     * @param string $user_password
     * @param string $mgr_name_create
     * @param array $other
     * @return object|string
     */
    public function addVisitor($user_name, string $user_real_name = '', string $user_password = '123456', string $mgr_name_create = 'SrunApi', array $other = [])
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $invite_code
     * @param string $user_real_name
     * @param string $user_password
     * @param string $mgr_name_create
     * @return object|string
     */
    public function addInviteVisitor($user_name, $invite_code, string $user_real_name = '', string $user_password = '123456', string $mgr_name_create = 'SrunApi')
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param string $user_real_name
     * @param $email
     * @param $cert_num
     * @param $phone
     * @param $address
     * @return object|string
     */
    public function update($user_name, string $user_real_name = '', $email = null, $cert_num = null, $phone = null, $address = null)
    {
        return $this->req('');
    }

    /**
     * @param $value
     * @param int $type
     * @return object|string
     */
    public function search($value, int $type = 1)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $user_real_name
     * @param $cert_num
     * @param $address
     * @param $phone
     * @param $email
     * @param $per_page
     * @param $page
     * @return object|string
     */
    public function superSearch($user_name = null, $user_real_name = null, $cert_num = null, $address = null, $phone = null, $email = null, $per_page = null, $page = null)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $add_time
     * @param $user_ip
     * @param $per_page
     * @param $page
     * @return object|string
     */
    public function detail($user_name = null, $add_time = null, $user_ip = null, $per_page = null, $page = null)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $old_password
     * @param $new_password
     * @return object|string
     */
    public function resetPassword($user_name, $old_password, $new_password)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $new_password
     * @return object|string
     */
    public function superResetPassword($user_name, $new_password)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $verify_code
     * @param $new_password
     * @param $way
     * @return object|string
     */
    public function forgetResetPassword($user_name, $verify_code, $new_password, $way)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $way
     * @return object|string
     */
    public function code($user_name, $way)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $phone
     * @return object|string
     */
    public function sendCode($user_name, $phone = null)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $phone
     * @param $verify_code
     * @return object|string
     */
    public function bindingPhone($user_name, $phone, $verify_code)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $drop_type
     * @param $rad_online_id
     * @return object|string
     */
    public function onlineDrop($user_name, $drop_type, $rad_online_id)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @return object|string
     */
    public function batchOnlineDrop($user_name)
    {
        return $this->req('');
    }

    /**
     * @param $type
     * @param $group_id
     * @param $product
     * @param int $user_available
     * @return object|string
     */
    public function statusControlBatch($type, $group_id, $product, int $user_available = 1)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @return object|string
     */
    public function getPassword($user_name)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $password
     * @return object|string
     */
    public function validate($user_name, $password)
    {
        return $this->req('');
    }

    /**
     * @param $username
     * @param $password
     * @return object|string
     */
    public function validateManager($username, $password)
    {
        return $this->req('');
    }

    /**
     * @param $username
     * @param $password
     * @param $new_password
     * @return object|string
     */
    public function resetPasswordManager($username, $password, $new_password)
    {
        return $this->req('');
    }

    /**
     * @param $domain
     * @param $auth_type
     * @param $type
     * @return object|string
     */
    public function auth($domain, $auth_type, $type)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @return object|string
     */
    public function macs($user_name)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $mac_address
     * @param $device_name
     * @param $device_type
     * @return object|string
     */
    public function createMac($user_name, $mac_address, $device_name, $device_type)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $mac_address
     * @return object|string
     */
    public function deleteMac($user_name, $mac_address)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $old_address
     * @param $new_address
     * @param $device_name
     * @param $device_type
     * @return object|string
     */
    public function updateMac($user_name, $old_address, $new_address, $device_name = null, $device_type = null)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $type
     * @param $old_vlan
     * @param $new_vlan
     * @return object|string
     */
    public function updateVlan($user_name, $type, $old_vlan, $new_vlan)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $mac_address
     * @return object|string
     */
    public function createMacAuth($user_name, $mac_address)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $mac_address
     * @return object|string
     */
    public function deleteMacAuth($user_name, $mac_address)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @return object|string
     */
    public function listMacAuth($user_name)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $device_name
     * @param $mac_address
     * @param $new_address
     * @return object|string
     */
    public function updateMacAuth($user_name, $device_name, $mac_address, $new_address = null)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $expire_at
     * @param $effective_at
     * @param $num
     * @param $generate_num
     * @return object|string
     */
    public function createInvite($user_name, $expire_at, $effective_at = null, $num = null, $generate_num = null)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $code
     * @param $page
     * @param $per_page
     * @return object|string
     */
    public function viewInvite($user_name, $code, $page = null, $per_page = null)
    {
        return $this->req('');
    }

    /**
     * @param $code
     * @param $used_by
     * @return object|string
     */
    public function useInvite($code, $used_by)
    {
        return $this->req('');
    }

    /**
     * @param $code
     * @param $used_by
     * @return object|string
     */
    public function disabledInvite($code, $used_by)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @return object|string
     */
    public function checkModifyPassword($user_name)
    {
        return $this->req('');
    }

    /**
     * @param $random
     * @return object|string
     */
    public function viewEventVisitor($random)
    {
        return $this->req('');
    }

    /**
     * @param $event_name
     * @param $start_at
     * @param $stop_at
     * @param $product_id
     * @param $group_id
     * @param $portal_ip
     * @param $ac_id
     * @param array $other
     * @return object|string
     */
    public function addEventVisitor($event_name, $start_at, $stop_at, $product_id, $group_id, $portal_ip, $ac_id, array $other = [])
    {
        return $this->req('');
    }

    /**
     * @param $event_name
     * @param array $other
     * @return object|string
     */
    public function updateEventVisitor($event_name, array $other = [])
    {
        return $this->req('');
    }

    /**
     * @param $event_name
     * @return object|string
     */
    public function deleteEventVisitor($event_name)
    {
        return $this->req('');
    }
}