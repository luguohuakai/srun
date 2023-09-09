<?php

namespace srun\src;

class LogV2 extends SrunV2
{
    public function __construct($srun_north_api_url = null)
    {
        parent::__construct($srun_north_api_url);
    }

    /**
     * 认证日志<br>
     * 根据ClickHouse里的srun_login_log查询用户登录错误日志
     * @param $start_time
     * @param $end_time
     * @param $per_page
     * @param array $other
     * @return object|string
     */
    public function login($start_time, $end_time, $per_page, array $other = [])
    {
        $data = compact('start_time', 'end_time');
        $data['per-page'] = $per_page;
        $data = array_merge($data, $other);
        return $this->req('api/v2/log/login', $data);
    }

    /**
     * 用户分析-报表<br>
     * 认证错误top榜 返回数据格式：用户名->数量<br>
     * 认证错误时间线 返回数据格式：时间->数量<br>
     * 错误消息top 返回数据格式：错误信息->数量
     * @param $start_time
     * @param $end_time
     * @return object|string
     */
    public function authErr($start_time, $end_time)
    {
        $data = compact('start_time', 'end_time');
        return $this->req('api/v2/log/auth-err', $data);
    }

    /**
     * 按认证错误原因统计次数
     * @param $start_time
     * @param $end_time
     * @param $err_msg
     * @return object|string
     */
    public function authErrorTotal($start_time, $end_time, $err_msg = null)
    {
        $data = compact('start_time', 'end_time');
        if ($err_msg !== null) $data['err_msg'] = $err_msg;
        return $this->req('api/v2/log/auth-error-total', $data);
    }

    /**
     * 系统日志
     * @param $start_time
     * @param $end_time
     * @param array $other 其它参数
     * @return object|string
     */
    public function sys($start_time, $end_time, array $other = [])
    {
        $data = compact('start_time', 'end_time');
        $data = array_merge($data, $other);
        return $this->req('api/v2/log/sys', $data);
    }

    /**
     * 按天统计当月流量
     * @param $user_name
     * @param $add_time
     * @param $drop_time
     * @return object|string
     */
    public function dayByte($user_name, $add_time, $drop_time)
    {
        $data = compact('user_name', 'add_time', 'drop_time');
        return $this->req('api/v2/statistics/day-byte', $data);
    }

    /**
     * 按天统计当月时长
     * @param $user_name
     * @param $add_time
     * @param $drop_time
     * @return object|string
     */
    public function dayTime($user_name, $add_time, $drop_time)
    {
        $data = compact('user_name', 'add_time', 'drop_time');
        return $this->req('api/v2/statistics/day-time', $data);
    }

    /**
     * 按天统计当月时长
     * @param $user_name
     * @param $add_time
     * @param $drop_time
     * @return object|string
     */
    public function monthByte($user_name, $add_time, $drop_time)
    {
        $data = compact('user_name', 'add_time', 'drop_time');
        return $this->req('api/v2/statistics/month-byte', $data);
    }

    /**
     * Flow(流量)日志
     * @param array $params
     * @return object|string
     */
    public function flowSearch(array $params = [])
    {
        return $this->req('api/v2/flow/search', $params);
    }

    /**
     * 审计日志
     * @param array $params
     * @return object|string
     */
    public function auditSearch(array $params = [])
    {
        return $this->req('api/v2/audit/search', $params);
    }
}