<?php

namespace srun\src;

class Package extends Srun implements \srun\base\Package
{
    public function __construct($srun_north_api_url, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        parent::__construct($srun_north_api_url, $srun_north_access_token, $srun_north_access_token_expire, $srun_north_access_token_redis_key);
    }

    // 查询已订购的产品/套餐接口
    public function userPackage($user_name)
    {
        return $this->req('api/v1/package/users-packages', ['user_name' => $user_name]);
    }
}