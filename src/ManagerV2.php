<?php

namespace srun\src;

class ManagerV2 extends SrunV2
{
    public function __construct($srun_north_api_url = null)
    {
        parent::__construct($srun_north_api_url);
    }

    /**
     * 校验管理员接口
     * @param $username
     * @param $password
     * @return object|string
     */
    public function validateManager($username, $password)
    {
        return $this->req('api/v2/user/validate-manager', compact('username', 'password'), 'post');
    }

    /**
     * 添加管理员接口
     * @param $username
     * @param $password
     * @return object|string
     */
    public function addManager($username, $password)
    {
        return $this->req('api/v2/user/add-manager', compact('username', 'password'), 'post');
    }

    /**
     * 修改管理员密码接口[通过原密码]
     * @param $username
     * @param $password
     * @param $new_password
     * @return object|string
     */
    public function resetPasswordManager($username, $password, $new_password)
    {
        return $this->req('api/v2/user/reset-password-manager', compact('username', 'password', 'new_password'), 'put');
    }

    /**
     * 重置管理员密码
     * @param $username
     * @param $new_password
     * @return object|string
     */
    public function superResetPasswordManager($username, $new_password)
    {
        return $this->req('api/v2/user/super-reset-password-manager', compact('username', 'new_password'), 'put');
    }
}