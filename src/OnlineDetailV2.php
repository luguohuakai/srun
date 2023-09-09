<?php

namespace srun\src;

class OnlineDetailV2 extends SrunV2
{
    public function __construct($srun_north_api_url = null)
    {
        parent::__construct($srun_north_api_url);
    }

    /**
     * 查询上网明细
     * @param $user_name
     * @param null $add_time
     * @param null $drop_time
     * @param null $user_ip
     * @param null $per_page
     * @param null $page
     * @return object|string
     */
    public function detail($user_name, $add_time = null, $drop_time = null, $user_ip = null, $per_page = null, $page = null)
    {
        $data = compact('user_name');
        if ($add_time) $data['add_time'] = $add_time;
        if ($drop_time) $data['drop_time'] = $drop_time;
        if ($user_ip) $data['user_ip'] = $user_ip;
        if ($per_page) $data['per-page'] = $per_page;
        if ($page) $data['page'] = $page;
        return $this->req('api/v2/details', $data);
    }

    /**
     * 查询上网流量TOP排名数据<br>
     * 如果开始时间参数没有传递，默认查询当天0点开始的数据<br>
     * 如果不传递drop_time，则默认以开始时间为起点，向后延长24小时
     * @return object|string
     */
    public function top($add_time, $drop_time = null, $limit = null)
    {
        $data = compact('add_time');
        if ($drop_time !== null) $data['drop_time'] = $drop_time;
        if ($limit !== null) $data['limit'] = $limit;
        return $this->req('api/v2/detail/top', $data);
    }
}