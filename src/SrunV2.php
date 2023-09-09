<?php

namespace srun\src;

use luguohuakai\func\Func;

class SrunV2 extends Srun
{
    protected string $app_id;
    protected string $app_secret;
    private string $srun_north_access_token_redis_key_v2 = 'srun_north_access_token_redis_v2';

    public function __construct($srun_north_api_url = null)
    {
        parent::__construct($srun_north_api_url);
    }

    public function setAppId($app_id)
    {
        $this->app_id = $app_id;
    }

    public function setAppSecret($app_secret)
    {
        $this->app_secret = $app_secret;
    }

    /**
     * 发起curl请求
     * @param $path
     * @param array $data
     * @param string $method
     * @param bool $access_token
     * @param array $header
     * @return string|object 错误时返回字符串|正确时返回json对象
     */
    public function req($path, array $data = [], string $method = 'get', bool $access_token = true, array $header = [])
    {
        $method = strtolower($method);
        $srun_north_api_url = $this->srun_north_api_url;
        if (!$srun_north_api_url) {
            $this->logError('NORTH INTERFACE V2 ERR');
            return 'NORTH INTERFACE ERR';
        }
        $url = "$srun_north_api_url$path";

        if ($access_token === true) {
            $srun_north_access_token = $this->accessToken();
            if (!$srun_north_access_token) {
                $this->logError('NORTH INTERFACE V2 ACCESS_TOKEN ERR');
                return 'NORTH INTERFACE ACCESS_TOKEN ERR';
            }
            if ($method === 'get') $url = "$url?access_token=$srun_north_access_token";
            if ($method === 'post') $data['access_token'] = $srun_north_access_token;
            if ($method === 'delete') $data['access_token'] = $srun_north_access_token;
        }

        $this->logInfo("$method|$url|" . json_encode($data, JSON_UNESCAPED_UNICODE), 'request');
        $rs = Func::$method($url, $data, $header);

        if (!$rs) {
            $this->logError('NORTH INTERFACE V2 REQUEST ERR');
            return 'NORTH INTERFACE REQUEST ERR';
        }
        if (is_object($rs) && isset($rs->_err)) {
            $this->logError('Curl error: ' . $rs->_err);
            return (string)$rs->_err;
        }
        $json = is_object($rs) ? $rs : json_decode($rs);
        $json = is_object($json) ? $json : json_decode($json);
        if (!is_object($json)) {
            $this->logError('RESULT DECODE ERR V2');
            return 'RESULT DECODE ERR';
        }
        if (isset($json->code) && $json->code == 0) {
            $this->logInfo("$method|$url|" . json_encode($json, JSON_UNESCAPED_UNICODE), 'response');
            return $json;
        }
        if (isset($json->code)) {
            $err_code = (int)$json->code;
            $err_msg = $json->message;
            if (in_array($err_code, array_keys(SrunError::$north))) $err_msg .= '-' . SrunError::$north[$err_code];
            $this->logError('NORTH ERR V2 - ' . json_encode($json, JSON_UNESCAPED_UNICODE));
            return 'NORTH ERR - ' . $err_msg;
        }
        $this->logError('NORTH INTERFACE V2 UNKNOWN ERR');
        return 'NORTH INTERFACE UNKNOWN ERR';
    }

    public function accessToken()
    {
        $cache = new Cache;
        $access_token = $cache->get($this->srun_north_access_token_redis_key_v2);
        if ($access_token) return $access_token;
        $rs = $this->req('api/v2/auth/get-access-token', ['appId' => $this->app_id, 'appSecret' => $this->app_secret], 'post', false);
        if (isset($rs->data) && isset($rs->data->access_token)) {
            // 缓存令牌
            $cache->set($this->srun_north_access_token_redis_key_v2, $rs->data->access_token, $rs->data->lifetime);
            return $rs->data->access_token;
        } else {
            $this->logError('get access_token failed v2', __METHOD__);
            return false;
        }
    }

    /**
     * 获取令牌接口
     * @return object|string
     */
    public function getAccessToken()
    {
        return $this->req('api/v2/auth/get-access-token', ['appId' => $this->app_id, 'appSecret' => $this->app_secret], 'post');
    }

    /**
     * 查看接口程序版本号接口
     * @return object|string
     */
    public function getVersion()
    {
        return $this->req('api/v2/auth/get-version');
    }

    /**
     * @param string $language zh-CN/en-us
     * @return object|string
     */
    public function settingLanguage(string $language)
    {
        return $this->req('api/v2/setting/language', compact('language'), 'post');
    }
}