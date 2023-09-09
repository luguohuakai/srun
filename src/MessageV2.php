<?php

namespace srun\src;

class MessageV2 extends SrunV2
{
    public function __construct($srun_north_api_url = null)
    {
        parent::__construct($srun_north_api_url);
    }

    /**
     * 发送通知消息接口
     * @param $account
     * 发送 至 所有在线用户 account = {SRUN_ALL_ONLINE_USERS}<br>
     * 发送 至 所有注册用户 account = {SRUN_ALL_REGISTER_USERS}<br>
     * 发送 至 某一个用户 account = 具体用户的账号
     * @param $receive_type
     * 通过 客户端 接收消息 receive_type = client<br>
     * 通过 微信 接收消息 receive_type = weixin<br>
     * 通过 短信 接收消息 receive_type = sms<br>
     * 通过 邮件 接收消息 receive_type = email
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
        return $this->req('api/v2/message/notice', $data, 'post');
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
        return $this->req('api/v2/message', $data);
    }

    /**
     * 查询新版本消息<br>
     * @param $id
     * @param null $type
     * type 参数说明<br>
     * type = 0 认证通知<br>
     * type = 1 认证消息公告<br>
     * type = 2 自服务通知<br>
     * type = 3 自服务通知公告<br>
     * type = 4 客户端通知
     * @param null $title
     * @param null $created_at
     * @param null $updated_at
     * @param null $per_page
     * @param null $page
     * @return object|string
     */
    public function newMessage($id, $type = null, $title = null, $created_at = null, $updated_at = null, $per_page = null, $page = null)
    {
        $data = compact('id');
        if ($type !== null) $data['type'] = $type;
        if ($title !== null) $data['title'] = $title;
        if ($created_at !== null) $data['created_at'] = $created_at;
        if ($updated_at !== null) $data['updated_at'] = $updated_at;
        if ($per_page !== null) $data['per_page'] = $per_page;
        if ($page !== null) $data['page'] = $page;
        return $this->req('api/v2/message/new-message', $data);
    }

    /**
     * 查询通知公告接口,适配新版本自服务
     * @param $type
     * @param $title
     * @return object|string
     */
    public function newMessage2($type, $title)
    {
        $data = compact('type', 'title');
        return $this->req('api/v2/message/new-message', $data);
    }

    /**
     * 消息策略 key 事件
     * @param $user_name
     * @param $key_name
     * @return object|string
     */
    public function keyEvent($user_name, $key_name)
    {
        return $this->req('api/v2/task/key-event', compact('user_name', 'key_name'), 'post');
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
        return $this->req('api/v2/task/key-third', compact('event_source', 'event_type', 'variable'), 'post');
    }

    /**
     * 消息策略 key 数据详情
     * @param $key_name
     * @return object|string
     */
    public function keyView($key_name)
    {
        return $this->req('api/v2/task/key-view', compact('key_name'));
    }
}