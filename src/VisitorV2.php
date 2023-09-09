<?php

namespace srun\src;

class VisitorV2 extends SrunV2
{
    public function __construct($srun_north_api_url = null)
    {
        parent::__construct($srun_north_api_url);
    }

    /**
     * 事件访客开户接口
     * @param $user_name
     * @param $event_id
     * @param string $user_real_name
     * @param string $user_password
     * @return object|string
     */
    public function eventVisitor($user_name, $event_id, string $user_real_name = '', string $user_password = '123456')
    {
        return $this->req('api/v2/user/event-visitors', ['user_name' => $user_name, 'event_id' => $event_id, 'user_real_name' => $user_real_name, 'user_password' => $user_password], 'post');
    }

    /**
     * 查询事件访客详情
     * @param $random
     * @return object|string
     */
    public function viewEventVisitor($random)
    {
        return $this->req('api/v2/visitor/view-event-visitor', compact('random'));
    }

    /**
     * 添加事件访客接口
     * @param $event_name
     * @param $start_at
     * @param $stop_at
     * @param $product_id
     * @param $group_id
     * @param $portal_ip
     * @param $ac_id
     * @param array $other 其它参数
     * @return object|string
     */
    public function createEventVisitor($event_name, $start_at, $stop_at, $product_id, $group_id, $portal_ip, $ac_id, array $other = [])
    {
        $data = compact('event_name', 'start_at', 'stop_at', 'product_id', 'group_id', 'portal_ip', 'ac_id');
        $data = array_merge($data, $other);
        return $this->req('api/v2/visitor/create-event-visitor', $data, 'post');
    }

    /**
     * 修改事件访客接口
     * @param $event_name
     * @param array $other
     * @return object|string
     */
    public function updateEventVisitor($event_name, array $other = [])
    {
        $data = ['event_name' => $event_name];
        if (!empty($other)) $data = array_merge($data, $other);
        return $this->req('api/v2/visitor/update-event-visitor', $data, 'post');
    }

    /**
     * 删除事件访客接口
     * @param $event_name
     * @return object|string
     */
    public function deleteEventVisitor($event_name)
    {
        return $this->req('api/v2/visitor/delete-event-visitor', compact('event_name'), 'delete');
    }

    /**
     * 查询访客邀请码
     * @param $user_name
     * @param $code
     * @param $page
     * @param $per_page
     * @return object|string
     */
    public function viewInvite($user_name, $code, $page = null, $per_page = null)
    {
        $data = compact('user_name', 'code');
        if ($page !== null) $data['page'] = $page;
        if ($per_page !== null) $data['per-page'] = $per_page;
        return $this->req('api/v2/user/view-invite', $data);
    }

    /**
     * 邀请码访客开户接口 本接口依赖: 8080端/访客管理/访客设置必须开启
     * @param $user_name
     * @param $invite_code
     * @param string $user_real_name
     * @param string $user_password
     * @param string $mgr_name_create
     * @return object|string
     */
    public function addInviteVisitor($user_name, $invite_code, string $user_real_name = '', string $user_password = '123456', string $mgr_name_create = 'SrunApi')
    {
        $data = [
            'user_name' => $user_name,
            'invite_code' => $invite_code,
            'user_real_name' => $user_real_name,
            'user_password' => $user_password,
            'mgr_name_create' => $mgr_name_create
        ];
        return $this->req('api/v2/user/invite-visitors', $data, 'post');
    }

    /**
     * 使用访客邀请码
     * @param $code
     * @param $used_by
     * @return object|string
     */
    public function useInvite($code, $used_by)
    {
        return $this->req('api/v2/user/use-invite', compact('code', 'used_by'), 'post');
    }

    /**
     * 创建访客邀请码
     * @param $user_name
     * @param $expire_at
     * @param $effective_at
     * @param $num
     * @param $generate_num
     * @return object|string
     */
    public function createInvite($user_name, $expire_at, $effective_at = null, $num = null, $generate_num = null)
    {
        $data = compact('user_name', 'expire_at');
        if ($effective_at !== null) $data['effective_at'] = $effective_at;
        if ($num !== null) $data['num'] = $num;
        if ($generate_num !== null) $data['generate_num'] = $generate_num;
        return $this->req('api/v2/user/create-invite', $data, 'post');
    }

    /**
     * 禁用访客邀请码
     * @param $code
     * @param $used_by
     * @return object|string
     */
    public function disabledInvite($code, $used_by)
    {
        return $this->req('api/v2/user/disabled-invite', compact('code', 'used_by'), 'post');
    }

    /**
     * 添加访客白名单
     * @param $white_account
     * @param array $other
     * @return object|string
     */
    public function createVisitorWhite($white_account, array $other = [])
    {
        $data = compact('white_account');
        $data = array_merge($data, $other);
        return $this->req('$path', $data, 'post');
    }

    /**
     * 删除访客白名单
     * @param $white_account
     * @return object|string
     */
    public function deleteVisitorWhite($white_account)
    {
        $data = compact('white_account');
        return $this->req('$path', $data, 'delete');
    }

    /**
     * 邀请码访客开户接口
     * @param $user_name
     * @param string $user_real_name
     * @param string $user_password
     * @param string $mgr_name_create
     * @param array $other
     * @return object|string
     */
    public function addVisitor($user_name, string $user_real_name = '', string $user_password = '123456', string $mgr_name_create = 'SrunApi', array $other = [])
    {
        $data = [
            'user_name' => $user_name,
            'user_real_name' => $user_real_name,
            'user_password' => $user_password,
            'mgr_name_create' => $mgr_name_create
        ];
        if (!empty($other)) $data = array_merge($data, $other);
        return $this->req('api/v2/user/visitors', $data, 'post');
    }

    /**
     * 二维码访客开户
     * @param $user_name
     * @return object|string
     */
    public function tokenVisitor($user_name)
    {
        return $this->req('api/v2/user/token-visitors', compact('user_name'), 'post');
    }

    /**
     * 开启/暂停接口批处理
     * @param $type
     * @param $group_id
     * @param $product
     * @param int $user_available
     * @return object|string
     */
    public function statusControlBatch($type, $group_id = null, $product = null, int $user_available = 1)
    {
        $data = ['type' => $type, 'user_available' => $user_available];
        if ($group_id) $data['group_id'] = $group_id;
        if ($product) $data['product'] = $product;
        return $this->req('api/v2/user/user-status-control-batch', $data, 'post');
    }

    /**
     * 添加事件访客接口
     * @param $event_name
     * @param $start_at
     * @param $stop_at
     * @param $product_id
     * @param $group_id
     * @param $portal_ip
     * @param $ac_id
     * @param array $other
     * @return object|string
     */
    public function addEventVisitor($event_name, $start_at, $stop_at, $product_id, $group_id, $portal_ip, $ac_id, array $other = [])
    {
        $data = compact('event_name', 'start_at', 'stop_at', 'product_id', 'group_id', 'portal_ip', 'ac_id');
        if (!empty($other)) $data = array_merge($data, $other);
        return $this->req('api/v2/visitor/add-event-visitor', $data, 'post');
    }
}