<?php

namespace srun\src;

class StatisticsV2 extends SrunV2
{
    public function __construct($srun_north_api_url = null)
    {
        parent::__construct($srun_north_api_url);
    }

    /**
     * 获取当前在线终端数量接口
     * @return object|string
     * @deprecated 停止维护！！！
     */
    public function getOnlineTotal()
    {
        return $this->req('api/v2/base/get-online-total');
    }

    /**
     * 在线数统计<br>
     * 在线数统计分为 统计在线用户数和统计在线设备数两个类型
     * @return object|string
     */
    public function onlineReportIndex($start_time, $end_time, $type = null)
    {
        $data = compact('start_time', 'end_time');
        if ($type !== null) $data['type'] = $type;
        return $this->req('api/v2/online-report/index', $data);
    }

    /**
     * 按策略类型统计流量时长
     * @param $start_time
     * @param $end_time
     * @param $value
     * @param $type
     * 统计类型，可选值如下<br>
     * control<br>
     * group<br>
     * os_name<br>
     * product<br>
     * billing<br>
     * class_name<br>
     * nas_ip<br>
     * vlan_area
     * @return object|string
     */
    public function onlineReportOther($start_time, $end_time, $value, $type = null)
    {
        $data = compact('start_time', 'end_time', 'value');
        if ($type !== null) $data['type'] = $type;
        return $this->req('api/v2/online-report/other', $data);
    }

    /**
     * 统计当前在线总数以及设备总数<br>
     * 统计当前在线总数以及设备总数，按用户组和产品进行汇总统计<br>
     * 依赖在线表group_id字段
     * @return object|string
     */
    public function getOnlineCount()
    {
        return $this->req('api/v2/base/get-online-count');
    }
}