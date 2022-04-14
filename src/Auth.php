<?php

namespace srun\src;

class Auth extends Srun implements \srun\base\Auth
{
    public function __construct($srun_north_api_url, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        parent::__construct($srun_north_api_url, $srun_north_access_token, $srun_north_access_token_expire, $srun_north_access_token_redis_key);
    }

    /**
     * @return object|string
     */
    public function getAccessToken()
    {
        return $this->req('');
    }

    /**
     * @return object|string
     */
    public function getVersion()
    {
        return $this->req('');
    }
}