<?php

namespace srun\src;

class UserV2 extends SrunV2
{
    public function __construct($srun_north_api_url = null)
    {
        parent::__construct($srun_north_api_url);
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
        return $this->req('api/v2/users', $data, 'post');
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
        return $this->req('api/v2/user/update', $data, 'post');
    }

    /**
     * (北向接口)销户
     * @param $user_name
     * @return object|string
     */
    public function delete($user_name)
    {
        return $this->req('api/v2/user/delete', ['user_name' => $user_name], 'delete');
    }

    /**
     * step1 发送短信验证码接口
     * @param $user_name
     * @param $way
     * @return object|string
     */
    public function code($user_name, $way)
    {
        return $this->req('api/v2/user/code', compact('user_name', 'way'));
    }

    /**
     * step2 忘记密码重置接口
     * @param $user_name
     * @param $verify_code
     * @param $new_password
     * @param $way
     * @return object|string
     */
    public function forgetResetPassword($user_name, $verify_code, $new_password, $way)
    {
        return $this->req('api/v2/user/forget-reset-password', compact('user_name', 'verify_code', 'new_password', 'way'), 'post');
    }

    /**
     * 验证是否需要修改密码
     * @param $user_name
     * @return object|string
     */
    public function checkModifyPassword($user_name)
    {
        return $this->req('api/v2/auth/check-modify-password', compact('user_name'), 'post');
    }

    /**
     * 返回用户上网密码
     * @param $user_name
     * @return object|string
     */
    public function getPassword($user_name)
    {
        return $this->req('api/v2/user/get-password', ['user_name' => $user_name]);
    }

    /**
     * 修改密码高级接口
     * @param $user_name
     * @param $new_password
     * @return object|string
     */
    public function superResetPassword($user_name, $new_password)
    {
        return $this->req('api/v2/user/super-reset-password', compact('user_name', 'new_password'), 'post');
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
        return $this->req('api/v2/user/reset-password', compact('user_name', 'old_password', 'new_password'), 'post');
    }

    /**
     * (北向接口)查询账号详情
     * @param $user_name
     * @return object|string
     */
    public function view($user_name)
    {
        return $this->req('api/v2/user/view', ['user_name' => $user_name]);
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
        return $this->req('api/v2/user/super-search', $param);
    }

    /**
     * 搜索用户接口
     * @param $value
     * @param int $type
     * @return object|string
     */
    public function search($value, int $type = 1)
    {
        return $this->req('api/v2/user/search', ['type' => $type, 'value' => $value]);
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
        return $this->req('api/v2/user/user-status-control', [
            'user_name' => $user_name,
            'user_available' => $user_available
        ], 'post');
    }

    /**
     * 校验用户接口
     * @param $user_name
     * @param $password
     * @return object|string
     */
    public function validate($user_name, $password)
    {
        return $this->req('api/v2/user/validate-users', compact('user_name', 'password'), 'post');
    }

    /**
     * 返回电子钱包余额接口
     * @param $user_name
     * @return object|string
     */
    public function userBalance($user_name)
    {
        return $this->req('api/v2/user/balance', ['user_name' => $user_name]);
    }

    /**
     * step1 发送验证码接口
     * @param $user_name
     * @param $phone
     * @return object|string
     */
    public function sendCode($user_name, $phone = null)
    {
        $data['user_name'] = $user_name;
        if ($phone) $data['phone'] = $phone;
        return $this->req('api/v2/user/send-code', $data);
    }

    /**
     * step2 绑定/更换 手机号码接口
     * @param string $user_name
     * @param $phone
     * @param $verify_code
     * @return object|string
     */
    public function bindingPhone(string $user_name, $phone, $verify_code)
    {
        return $this->req('api/v2/user/binding-phone', compact('user_name', 'phone', 'verify_code'), 'put');
    }

    /**
     * 修改最大在线数接口
     * @param $user_name
     * @param $num
     * @return object|string
     */
    public function maxOnlineNum($user_name, $num)
    {
        return $this->req('api/v2/user/max-online-num', [
            'user_name' => $user_name,
            'max_online_num' => $num
        ], 'post');
    }
}