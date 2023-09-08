<?php

namespace srun\src;

class AuthV2 extends SrunV2
{
    public function __construct($srun_north_api_url = null)
    {
        parent::__construct($srun_north_api_url);
    }

    /**
     * 获取令牌接口
     * @return object|string
     */
    public function getAccessToken()
    {
        return $this->req('api/v2/auth/get-access-token');
    }

    /**
     * 查看接口程序版本号接口
     * @return object|string
     */
    public function getVersion()
    {
        return $this->req('api/v2/auth/get-version');
    }
}