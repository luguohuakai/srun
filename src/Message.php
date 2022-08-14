<?php

namespace srun\src;

class Message extends Srun implements \srun\base\Message
{
    public function __construct($srun_north_api_url = null, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        parent::__construct($srun_north_api_url, $srun_north_access_token, $srun_north_access_token_expire, $srun_north_access_token_redis_key);
    }

    /**
     * 发送通知消息接口
     * @param $account
     * @param $receive_type
     * @param $subject
     * @param $target
     * @param $product_id
     * @return object|string
     */
    public function notice($account, $receive_type, $subject, $target = null, $product_id = null)
    {
        $data = compact('account', 'receive_type', 'subject');
        if ($target !== null) $data['target'] = $target;
        if ($product_id !== null) $data['product_id'] = $product_id;
        return $this->req('api/v1/message/notice', $data, 'post');
    }

    /**
     * 查询通知公告
     * @param $type
     * @param $sys_mgr_user_name
     * @return object|string
     */
    public function message($type = null, $sys_mgr_user_name = null)
    {
        $data = [];
        if ($type !== null) $data['type'] = $type;
        if ($sys_mgr_user_name !== null) $data['sys_mgr_user_name'] = $sys_mgr_user_name;
        return $this->req('api/v1/message', $data);
    }

    /**
     * 查询通知公告接口,适配新版本自服务
     * 本接口适配新版本自服务系统， 如果用户已经升级为新版本,则应尽快进行接口切换
     * @param array $param
     * @return object|string
     */
    public function newMessage(array $param = [])
    {
        return $this->req('api/v1/message/new-message', $param);
    }

    /**
     * 消息策略 key 事件
     * @param $user_name
     * @param $key_name
     * @return object|string
     */
    public function keyEvent($user_name, $key_name)
    {
        return $this->req('api/v1/task/key-event', compact('user_name', 'key_name'), 'post');
    }

    /**
     * 消息策略 key 事件接口(第三方)
     * 本接口适用于第三方自定义 事件类型以及事件来源的 场景
     * @param $event_source
     * @param $event_type
     * @param $variable
     * @return object|string
     */
    public function keyThird($event_source, $event_type, $variable)
    {
        return $this->req('api/v1/task/key-third', compact('event_source', 'event_type', 'variable'), 'post');
    }

    /**
     * 消息策略 key 数据详情
     * @param $key_name
     * @return object|string
     */
    public function keyView($key_name)
    {
        return $this->req('api/v1/task/key-view', compact('key_name'));
    }
}