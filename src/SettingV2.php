<?php

namespace srun\src;

class SettingV2 extends SrunV2
{
    public function __construct($srun_north_api_url = null)
    {
        parent::__construct($srun_north_api_url);
    }

    /**
     * @param $language
     * @return object|string
     */
    public function settingLanguage($language)
    {
        return $this->req('api/v2/setting/language', compact('language'));
    }
}