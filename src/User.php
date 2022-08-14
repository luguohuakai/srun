<?php

namespace srun\src;

class User extends Srun implements \srun\base\User
{
    public function __construct($srun_north_api_url = null, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
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

    /**
     * 修改最大在线数接口
     * @param $user_name
     * @param $num
     * @return object|string
     */
    public function maxOnlineNum($user_name, $num)
    {
        return $this->req('api/v1/user/max-online-num', [
            'user_name' => $user_name,
            'max_online_num' => $num
        ], 'post');
    }

    /**
     * 查询在线设备接口
     * @param $user_name
     * @param $user_ip
     * @param $user_mac
     * @return object|string
     */
    public function onlineEquipment($user_name = null, $user_ip = null, $user_mac = null)
    {
        if ($user_mac !== null) $data = ['user_mac' => $user_mac];
        if ($user_ip !== null) $data = ['user_ip' => $user_ip];
        if ($user_name !== null) $data = ['user_name' => $user_name];
        if (!isset($data)) return '参数不正确';
        return $this->req('api/v1/base/online-equipment', $data);
    }

    /**
     * 获取当前在线终端数量接口
     * @return object|string
     */
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
     * 事件访客开户接口
     * @param $user_name
     * @param $event_id
     * @param string $user_real_name
     * @param string $user_password
     * @return object|string
     */
    public function eventVisitor($user_name, $event_id, string $user_real_name = '', string $user_password = '123456')
    {
        return $this->req('api/v1/user/event-visitors', ['user_name' => $user_name, 'event_id' => $event_id, 'user_real_name' => $user_real_name, 'user_password' => $user_password], 'post');
    }

    /**
     * 邀请码访客开户接口
     * @param $user_name
     * @param string $user_real_name
     * @param string $user_password
     * @param string $mgr_name_create
     * @param array $other
     * @return object|string
     */
    public function addVisitor($user_name, string $user_real_name = '', string $user_password = '123456', string $mgr_name_create = 'SrunApi', array $other = [])
    {
        $data = [
            'user_name' => $user_name,
            'user_real_name' => $user_real_name,
            'user_password' => $user_password,
            'mgr_name_create' => $mgr_name_create
        ];
        if (!empty($other)) $data = array_merge($data, $other);
        return $this->req('api/v1/user/visitors', $data, 'post');
    }

    /**
     * 邀请码访客开户接口 本接口依赖: 8080端/访客管理/访客设置必须开启
     * @param $user_name
     * @param $invite_code
     * @param string $user_real_name
     * @param string $user_password
     * @param string $mgr_name_create
     * @return object|string
     */
    public function addInviteVisitor($user_name, $invite_code, string $user_real_name = '', string $user_password = '123456', string $mgr_name_create = 'SrunApi')
    {
        $data = [
            'user_name' => $user_name,
            'invite_code' => $invite_code,
            'user_real_name' => $user_real_name,
            'user_password' => $user_password,
            'mgr_name_create' => $mgr_name_create
        ];
        return $this->req('api/v1/user/invite-visitors', $data, 'post');
    }

    /**
     * 修改用户接口
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
        $data['user_name'] = $user_name;
        if ($user_real_name) $data['user_real_name'] = $user_real_name;
        if ($email) $data['email'] = $email;
        if ($cert_num) $data['cert_num'] = $cert_num;
        if ($phone) $data['phone'] = $phone;
        if ($address) $data['address'] = $address;
        return $this->req('api/v1/user/update', $data, 'post');
    }

    /**
     * 搜索用户接口
     * @param $value
     * @param int $type
     * @return object|string
     */
    public function search($value, int $type = 1)
    {
        return $this->req('api/v1/user/search', ['type' => $type, 'value' => $value]);
    }

    /**
     * 搜索用户高级接口 本接口支持模糊搜索
     * @param string user_name
     * @param string user_real_name
     * @param string cert_num
     * @param string address
     * @param string phone
     * @param string email
     * @param int per_page
     * @param int page
     * @return object|string
     */
    public function superSearch($param = [])
    {
        return $this->req('api/v1/user/super-search', $param);
    }

    /**
     * 上网明细接口
     * @param $user_name
     * @param $add_time
     * @param $user_ip
     * @param $per_page
     * @param $page
     * @return object|string
     */
    public function detail($user_name = null, $add_time = null, $user_ip = null, $per_page = null, $page = null)
    {
        $data = [];
        if ($user_name) $data['user_name'] = $user_name;
        if ($add_time) $data['add_time'] = $add_time;
        if ($user_ip) $data['user_ip'] = $user_ip;
        if ($per_page) $data['per-page'] = $per_page;
        if ($page) $data['page'] = $page;
        return $this->req('api/v1/details', $data);
    }

    /**
     * 修改密码接口
     * @param $user_name
     * @param $old_password
     * @param $new_password
     * @return object|string
     */
    public function resetPassword($user_name, $old_password, $new_password)
    {
        return $this->req('api/v1/user/reset-password', compact('user_name', 'old_password', 'new_password'), 'post');
    }

    /**
     * 修改密码高级接口
     * @param $user_name
     * @param $new_password
     * @return object|string
     */
    public function superResetPassword($user_name, $new_password)
    {
        return $this->req('api/v1/user/super-reset-password', compact('user_name', 'new_password'), 'post');
    }

    /**
     * 忘记密码重置接口
     * @param $user_name
     * @param $verify_code
     * @param $new_password
     * @param $way
     * @return object|string
     */
    public function forgetResetPassword($user_name, $verify_code, $new_password, $way)
    {
        return $this->req('api/v1/user/forget-reset-password', compact('user_name', 'verify_code', 'new_password', 'way'), 'post');
    }

    /**
     * 发送短信验证码接口
     * @param $user_name
     * @param $way
     * @return object|string
     */
    public function code($user_name, $way)
    {
        return $this->req('api/v1/user/code', compact('user_name', 'way'));
    }

    /**
     * 发送验证码接口
     * @param $user_name
     * @param $phone
     * @return object|string
     */
    public function sendCode($user_name, $phone = null)
    {
        $data['user_name'] = $user_name;
        if ($phone) $data['phone'] = $phone;
        return $this->req('api/v1/user/send-code', $data);
    }

    /**
     * 绑定/更换 手机号码接口
     * @param string $user_name
     * @param $phone
     * @param $verify_code
     * @return object|string
     */
    public function bindingPhone($user_name, $phone, $verify_code)
    {
        return $this->req('api/v1/user/binding-phone', compact('user_name', 'phone', 'verify_code'), 'put');
    }

    /**
     * 在线设备下线接口
     * @param $user_name
     * @param $drop_type
     * @param $rad_online_id
     * @return object|string
     */
    public function onlineDrop($user_name, $drop_type, $rad_online_id)
    {
        return $this->req('api/v1/base/online-drop', compact('user_name', 'drop_type', 'rad_online_id'), 'post');
    }

    /**
     * 下线指定用户的所有设备
     * @param $user_name
     * @return object|string
     */
    public function batchOnlineDrop($user_name)
    {
        return $this->req('api/v1/base/batch-online-drop', compact('user_name'), 'post');
    }

    /**
     * 开启/暂停接口批处理
     * @param $type
     * @param $group_id
     * @param $product
     * @param int $user_available
     * @return object|string
     */
    public function statusControlBatch($type, $group_id = null, $product = null, int $user_available = 1)
    {
        $data = ['type' => $type, 'user_available' => $user_available];
        if ($group_id) $data['group_id'] = $group_id;
        if ($product) $data['product'] = $product;
        return $this->req('api/v1/user/user-status-control-batch', $data, 'post');
    }

    /**
     * 返回用户上网密码
     * @param $user_name
     * @return object|string
     */
    public function getPassword($user_name)
    {
        return $this->req('api/v1/user/get-password', ['user_name' => $user_name]);
    }

    /**
     * 校验用户接口
     * @param $user_name
     * @param $password
     * @return object|string
     */
    public function validate($user_name, $password)
    {
        return $this->req('api/v1/user/validate-users', compact('user_name', 'password'), 'post');
    }

    /**
     * 校验管理员接口
     * @param $username
     * @param $password
     * @return object|string
     */
    public function validateManager($username, $password)
    {
        return $this->req('api/v1/user/validate-manager', compact('username', $password), 'post');
    }

    /**
     * 修改管理员密码接口
     * @param $username
     * @param $password
     * @param $new_password
     * @return object|string
     */
    public function resetPasswordManager($username, $password, $new_password)
    {
        return $this->req('api/v1/user/reset-password-manager', compact('username', 'password', $new_password), 'post');
    }

    /**
     * Dhcp认证接口 (实时)
     * @param $domain
     * @param $auth_type
     * @param $type
     * @return object|string
     */
    public function auth($domain, $auth_type, $type)
    {
        return $this->req('api/v1/auth', compact('domain', 'auth_type', 'type'), 'post');
    }

    /**
     * Dhcp认证接口 (异步)
     * @param $user_ip
     * @param $user_mac
     * @param $os_name
     * @param $nas_ip
     * @param $auth_type
     * @return object|string
     */
    public function authAsync($user_ip, $user_mac, $os_name, $nas_ip, $auth_type)
    {
        return $this->req('api/v1/auth', compact('user_ip', 'user_mac', 'os_name', 'nas_ip', 'auth_type'), 'post');
    }

    /**
     * 获取用户已绑定的设备列表
     * @param $user_name
     * @return object|string
     */
    public function macs($user_name)
    {
        return $this->req('api/v1/base/macs', compact('user_name'));
    }

    /**
     * 增加用户设备(mac地址)
     * @param $user_name
     * @param $mac_address
     * @param $device_name
     * @param $device_type
     * @return object|string
     */
    public function createMac($user_name, $mac_address, $device_name, $device_type = null)
    {
        $data = compact('user_name', 'mac_address', 'device_name');
        if ($device_type) $data['device_type'] = $device_type;
        return $this->req('api/v1/base/create-mac', $data, 'post');
    }

    /**
     * 删除用户设备(mac地址)
     * @param $user_name
     * @param $mac_address
     * @return object|string
     */
    public function deleteMac($user_name, $mac_address)
    {
        return $this->req('api/v1/base/delete-mac', compact('user_name', 'mac_address'), 'delete');
    }

    /**
     * 更新用户设备
     * @param $user_name
     * @param $old_address
     * @param $new_address
     * @param $device_name
     * @param $device_type
     * @return object|string
     */
    public function updateMac($user_name, $old_address, $new_address, $device_name = null, $device_type = null)
    {
        $data = compact('user_name', 'old_address', 'new_address');
        if ($device_name) $data['device_name'] = $device_name;
        if ($device_type) $data['device_type'] = $device_type;
        return $this->req('api/v1/base/update-mac', $data, 'post');
    }

    /**
     * 绑定/修改用户 vlan 地址
     * @param $user_name
     * @param $type
     * @param $old_vlan
     * @param $new_vlan
     * @return object|string
     */
    public function updateVlan($user_name, $type = null, $old_vlan = null, $new_vlan = null)
    {
        $data = ['user_name' => $user_name];
        if ($type) $data['type'] = $type;
        if ($old_vlan) $data['old_vlan'] = $old_vlan;
        if ($new_vlan) $data['new_vlan'] = $new_vlan;
        return $this->req('api/v1/base/update-vlan', $data, 'post');
    }

    /**
     * 绑定Mac认证设备
     * @param $user_name
     * @param $mac_address
     * @return object|string
     */
    public function createMacAuth($user_name, $mac_address)
    {
        return $this->req('api/v1/base/create-mac-auth', compact('user_name', 'mac_address'), 'post');
    }

    /**
     * 删除Mac认证设备
     * @param $user_name
     * @param $mac_address
     * @return object|string
     */
    public function deleteMacAuth($user_name, $mac_address)
    {
        return $this->req('api/v1/base/delete-mac-auth', compact('user_name', 'mac_address'), 'delete');
    }

    /**
     * 返回Mac认证设备列表
     * @param $user_name
     * @return object|string
     */
    public function listMacAuth($user_name)
    {
        return $this->req('api/v1/base/list-mac-auth', compact('user_name'));
    }

    /**
     * 修改Mac认证设备信息
     * @param $user_name
     * @param $device_name
     * @param $mac_address
     * @param $new_address
     * @return object|string
     */
    public function updateMacAuth($user_name, $device_name, $mac_address, $new_address = null)
    {
        $data = compact('user_name', 'device_name', 'mac_address');
        if ($new_address !== null) $data['new_address'] = $new_address;
        return $this->req('api/v1/base/update-mac-auth', $data, 'post');
    }

    /**
     * 创建访客邀请码
     * @param $user_name
     * @param $expire_at
     * @param $effective_at
     * @param $num
     * @param $generate_num
     * @return object|string
     */
    public function createInvite($user_name, $expire_at, $effective_at = null, $num = null, $generate_num = null)
    {
        $data = compact('user_name', 'expire_at');
        if ($effective_at !== null) $data['effective_at'] = $effective_at;
        if ($num !== null) $data['num'] = $num;
        if ($generate_num !== null) $data['generate_num'] = $generate_num;
        return $this->req('api/v1/user/create-invite', $data, 'post');
    }

    /**
     * 查询访客邀请码
     * @param $user_name
     * @param $code
     * @param $page
     * @param $per_page
     * @return object|string
     */
    public function viewInvite($user_name, $code, $page = null, $per_page = null)
    {
        $data = compact('user_name', 'code');
        if ($page !== null) $data['page'] = $page;
        if ($per_page !== null) $data['per-page'] = $per_page;
        return $this->req('api/v1/user/view-invite', $data);
    }

    /**
     * 使用访客邀请码
     * @param $code
     * @param $used_by
     * @return object|string
     */
    public function useInvite($code, $used_by)
    {
        return $this->req('api/v1/user/use-invite', compact('code', 'used_by'), 'post');
    }

    /**
     * 禁用访客邀请码
     * @param $code
     * @param $used_by
     * @return object|string
     */
    public function disabledInvite($code, $used_by)
    {
        return $this->req('api/v1/user/disabled-invite', compact('code', 'used_by'), 'post');
    }

    /**
     * 验证是否需要修改密码
     * @param $user_name
     * @return object|string
     */
    public function checkModifyPassword($user_name)
    {
        return $this->req('api/v1/auth/check-modify-password', compact('user_name'), 'post');
    }

    /**
     * 查询事件访客详情
     * @param $random
     * @return object|string
     */
    public function viewEventVisitor($random)
    {
        return $this->req('api/v1/visitor/view-event-visitor', compact('random'));
    }

    /**
     * 添加事件访客接口
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
        $data = compact('event_name', 'start_at', 'stop_at', 'product_id', 'group_id', 'portal_ip', 'ac_id');
        if (!empty($other)) $data = array_merge($data, $other);
        return $this->req('api/v1/visitor/add-event-visitor', $data, 'post');
    }

    /**
     * 修改事件访客接口
     * @param $event_name
     * @param array $other
     * @return object|string
     */
    public function updateEventVisitor($event_name, array $other = [])
    {
        $data = ['event_name' => $event_name];
        if (!empty($other)) $data = array_merge($data, $other);
        return $this->req('api/v1/visitor/update-event-visitor', $data, 'post');
    }

    /**
     * 删除事件访客接口
     * @param $event_name
     * @return object|string
     */
    public function deleteEventVisitor($event_name)
    {
        return $this->req('api/v1/visitor/delete-event-visitor', compact('event_name'), 'delete');
    }
}