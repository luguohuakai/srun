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
}