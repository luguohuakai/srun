<?php

namespace srun\src;

class GroupV2 extends SrunV2
{
    public function __construct($srun_north_api_url = null)
    {
        parent::__construct($srun_north_api_url);
    }

    // (北向接口)添加用户组
    public function add($group_name, $parent_name = '/')
    {
        $data['name'] = mb_substr($group_name, 0, 100);
        $data['parent_name'] = $parent_name;
        return $this->req('api/v2/groups', $data, 'post');
    }

    /**
     * 查询用户组接口
     * @param string|null $name 用户组名称 支持模糊查询
     * @param $id
     * @param $per_page
     * @param $page
     * @return object|string
     */
    public function view(string $name = null, $id = null, $per_page = null, $page = null)
    {
        $data = [];
        if ($name !== null) $data['name'] = $name;
        if ($id !== null) $data['id'] = $id;
        if ($per_page !== null) $data['per-page'] = $per_page;
        if ($page !== null) $data['page'] = $page;

        return $this->req('api/v2/groups', $data);
    }

    /**
     * 查询用户组可订购的产品接口
     * @param $group_id
     * @return object|string
     */
    public function Subscribe($group_id)
    {
        return $this->req('api/v2/group/subscribe', compact('group_id'));
    }
}