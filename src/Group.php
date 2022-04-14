<?php

namespace srun\src;

class Group extends Srun implements \srun\base\Group
{
    public function __construct($srun_north_api_url, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
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
     * @param $name
     * @param $id
     * @param $per_page
     * @param $page
     * @return mixed
     */
    public function view($name = null, $id = null, $per_page = null, $page = null)
    {
        // TODO: Implement view() method.
    }

    /**
     * @param $group_id
     * @return mixed
     */
    public function Subscribe($group_id)
    {
        // TODO: Implement Subscribe() method.
    }
}