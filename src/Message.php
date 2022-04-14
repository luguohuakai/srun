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
     * @return mixed
     */
    public function notice($account, $receive_type, $subject, $target = null, $product_id = null)
    {
        // TODO: Implement notice() method.
    }

    /**
     * @param $type
     * @param $sys_mgr_user_name
     * @return mixed
     */
    public function message($type = null, $sys_mgr_user_name = null)
    {
        // TODO: Implement message() method.
    }

    /**
     * @param array $param
     * @return mixed
     */
    public function newMessage(array $param = [])
    {
        // TODO: Implement newMessage() method.
    }

    /**
     * @param $user_name
     * @param $key_name
     * @return mixed
     */
    public function keyEvent($user_name, $key_name)
    {
        // TODO: Implement keyEvent() method.
    }

    /**
     * @param $eventSource
     * @param $eventType
     * @param $variable
     * @return mixed
     */
    public function keyThird($eventSource, $eventType, $variable)
    {
        // TODO: Implement keyThird() method.
    }

    /**
     * @param $key_name
     * @return mixed
     */
    public function keyView($key_name)
    {
        // TODO: Implement keyView() method.
    }
}