<?php

namespace srun\src;

class OnlineV2 extends SrunV2
{
    public function __construct($srun_north_api_url = null)
    {
        parent::__construct($srun_north_api_url);
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
        return $this->req('api/v2/base/online-drop', compact('user_name', 'drop_type', 'rad_online_id'), 'post');
    }

    /**
     * 根据用户名批量下线
     * @param $user_name
     * @return object|string
     */
    public function batchOnlineDrop($user_name)
    {
        return $this->req('api/v2/base/batch-online-drop', compact('user_name'), 'post');
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
        return $this->req('api/v2/auth', compact('domain', 'auth_type', 'type'), 'post');
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
        return $this->req('api/v2/auth', compact('user_ip', 'user_mac', 'os_name', 'nas_ip', 'auth_type'), 'post');
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
        return $this->req('api/v2/base/online-equipment', $data);
    }

    /**
     * 查询当前在线用户数据
     * @return object|string
     */
    public function onlineData()
    {
        return $this->req('api/v2/base/online-data');
    }


}