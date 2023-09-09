<?php

namespace srun\src;

class DeviceV2 extends SrunV2
{
    public function __construct($srun_north_api_url = null)
    {
        parent::__construct($srun_north_api_url);
    }

    /**
     * 查询mac认证设备 已绑定
     * @param $user_name
     * @return object|string
     */
    public function listMacAuth($user_name)
    {
        return $this->req('api/v2/base/list-mac-auth', compact('user_name'));
    }

    /**
     * 添加mac认证地址
     * @param $user_name
     * @param $mac_address
     * @return object|string
     */
    public function createMacAuth($user_name, $mac_address)
    {
        return $this->req('api/v2/base/create-mac-auth', compact('user_name', 'mac_address'), 'post');
    }

    /**
     * 删除mac认证地址
     * @param $user_name
     * @param $mac_address
     * @return object|string
     */
    public function deleteMacAuth($user_name, $mac_address)
    {
        return $this->req('api/v2/base/delete-mac-auth', compact('user_name', 'mac_address'), 'delete');
    }

    /**
     * 更新mac认证地址
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
        return $this->req('api/v2/base/update-mac-auth', $data, 'post');
    }

    /**
     * 手机号无感知
     * @param $user_name
     * @param $phone
     * @param $action
     * @return object|string
     */
    public function phoneAuth($user_name, $phone, $action)
    {
        return $this->req('api/v2/base/phone-auth', compact('user_name', 'phone', 'action'), 'post');
    }

    /**
     * 获取用户已绑定的设备列表
     * @param $user_name
     * @return object|string
     */
    public function macs($user_name)
    {
        return $this->req('api/v2/base/macs', compact('user_name'));
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
        return $this->req('api/v2/base/create-mac', $data, 'post');
    }

    /**
     * 删除用户设备(mac地址)
     * @param $user_name
     * @param $mac_address
     * @return object|string
     */
    public function deleteMac($user_name, $mac_address)
    {
        return $this->req('api/v2/base/delete-mac', compact('user_name', 'mac_address'), 'delete');
    }

    /**
     * 更新用户设备(mac地址)
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
        return $this->req('api/v2/base/update-mac', $data, 'put');
    }

    /**
     * 绑定/更换用户的 vlan 地址
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
        return $this->req('api/v2/base/update-vlan', $data, 'post');
    }

    /**
     * 根据用户名搜索可用/已用IP
     * @param $user_name
     * @return object|string
     */
    public function getIp($user_name)
    {
        return $this->req('api/v2/base/get-ip', compact('user_name'));
    }

    /**
     * 绑定IP地址
     * @param $user_name
     * @param $ip
     * @return object|string
     */
    public function bindIp($user_name, $ip)
    {
        return $this->req('api/v2/base/bind-ip', compact('user_name', 'ip'));
    }


}