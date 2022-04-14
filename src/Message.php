<?php

namespace srun\src;

class Message extends Srun implements \srun\base\Message
{
    public function __construct($srun_north_api_url, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        parent::__construct($srun_north_api_url, $srun_north_access_token, $srun_north_access_token_expire, $srun_north_access_token_redis_key);
    }

    /**
     * @param $account
     * @param $receive_type
     * @param $subject
     * @param $target
     * @param $product_id
     * @return object|string
     */
    public function notice($account, $receive_type, $subject, $target = null, $product_id = null)
    {
        return $this->req('');
    }

    /**
     * @param $type
     * @param $sys_mgr_user_name
     * @return object|string
     */
    public function message($type = null, $sys_mgr_user_name = null)
    {
        return $this->req('');
    }

    /**
     * @param array $param
     * @return object|string
     */
    public function newMessage(array $param = [])
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $key_name
     * @return object|string
     */
    public function keyEvent($user_name, $key_name)
    {
        return $this->req('');
    }

    /**
     * @param $eventSource
     * @param $eventType
     * @param $variable
     * @return object|string
     */
    public function keyThird($eventSource, $eventType, $variable)
    {
        return $this->req('');
    }

    /**
     * @param $key_name
     * @return object|string
     */
    public function keyView($key_name)
    {
        return $this->req('');
    }
}