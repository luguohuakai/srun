<?php

namespace srun\src;

class Strategy extends Srun implements \srun\base\Strategy
{
    public function __construct($srun_north_api_url, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        parent::__construct($srun_north_api_url, $srun_north_access_token, $srun_north_access_token_expire, $srun_north_access_token_redis_key);
    }

    /**
     * @param $billing_name
     * @param $billing_num
     * @param $billing_unit
     * @param $billing_rate
     * @param array $other
     * @return object|string
     */
    public function billingCreate($billing_name, $billing_num, $billing_unit, $billing_rate, array $other = [])
    {
        return $this->req('');
    }

    /**
     * @param $control_name
     * @param array $other
     * @return object|string
     */
    public function controlCreate($control_name, array $other = [])
    {
        return $this->req('');
    }
}