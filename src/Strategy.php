<?php

namespace srun\src;

class Strategy extends Srun implements \srun\base\Strategy
{
    public function __construct($srun_north_api_url = null, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        parent::__construct($srun_north_api_url, $srun_north_access_token, $srun_north_access_token_expire, $srun_north_access_token_redis_key);
    }

    /**
     * 添加计费策略
     * @param $billing_name
     * @param $billing_num
     * @param $billing_unit
     * @param $billing_rate
     * @param array $other
     * @return object|string
     */
    public function billingCreate($billing_name, $billing_num, $billing_unit, $billing_rate, array $other = [])
    {
        $data = compact('billing_name', 'billing_num', 'billing_unit', 'billing_rate');
        if (!empty($other)) $data = array_merge($data, $other);
        return $this->req('api/v1/strategy/billing-create', $data, 'post');
    }

    /**
     * 添加控制策略
     * @param $control_name
     * @param array $other
     * @return object|string
     */
    public function controlCreate($control_name, array $other = [])
    {
        return $this->req('api/v1/strategy/control-create', array_merge(compact('control_name'), $other), 'post');
    }
}