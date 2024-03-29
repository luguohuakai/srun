<?php

namespace srun\src;

class Setting extends Srun implements \srun\base\Setting
{
    public function __construct($srun_north_api_url = null, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        parent::__construct($srun_north_api_url, $srun_north_access_token, $srun_north_access_token_expire, $srun_north_access_token_redis_key);
    }

    /**
     * @param $language
     * @return object|string
     */
    public function settingLanguage($language)
    {
        return $this->req('api/v1/setting/language', compact('language'));
    }
}