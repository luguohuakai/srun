<?php

namespace srun\src;

class Auth extends Srun implements \srun\base\Auth
{
    public function __construct($srun_north_api_url, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        parent::__construct($srun_north_api_url, $srun_north_access_token, $srun_north_access_token_expire, $srun_north_access_token_redis_key);
    }

    /**
     * 获取令牌接口
     * @return object|string
     */
    public function getAccessToken()
    {
        return $this->req('api/v1/auth/get-access-token');
    }

    /**
     * 查看接口程序版本号接口
     * @return object|string
     */
    public function getVersion()
    {
        return $this->req('api/v1/auth/get-version');
    }
}