<?php

namespace srun\src;

class Group extends Srun implements \srun\base\Group
{
    public function __construct($srun_north_api_url = null, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        parent::__construct($srun_north_api_url, $srun_north_access_token, $srun_north_access_token_expire, $srun_north_access_token_redis_key);
    }

    // (北向接口)添加用户组
    public function add($group_name, $parent_name = '/')
    {
        $data['name'] = mb_substr($group_name, 0, 100);
        $data['parent_name'] = $parent_name;
        return $this->req('api/v1/groups', $data, 'post');
    }

    /**
     * 查询用户组接口
     * @param $name
     * @param $id
     * @param $per_page
     * @param $page
     * @return object|string
     */
    public function view($name = null, $id = null, $per_page = null, $page = null)
    {
        $data = [];
        if ($name !== null) $data['name'] = $name;
        if ($id !== null) $data['id'] = $id;
        if ($per_page !== null) $data['per-page'] = $per_page;
        if ($page !== null) $data['page'] = $page;
        return $this->req('api/v1/groups', $data);
    }

    /**
     * 查询用户组可订购的产品接口
     * @param $group_id
     * @return object|string
     */
    public function Subscribe($group_id)
    {
        return $this->req('api/v1/group/subscribe', compact('group_id'));
    }
}