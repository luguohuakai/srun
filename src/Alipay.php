<?php

namespace srun\src;

class Alipay extends Srun implements \srun\base\Alipay
{
    public function __construct($srun_north_api_url, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        parent::__construct($srun_north_api_url, $srun_north_access_token, $srun_north_access_token_expire, $srun_north_access_token_redis_key);
    }

    /**
     * @param $out_trade_no
     * @param $order_name
     * @param $desc
     * @param $money
     * @param $notify_type
     * @param $return_url
     * @return object|string
     */
    public function pay($out_trade_no, $order_name, $desc, $money, $notify_type, $return_url)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $out_trade_no
     * @param $money
     * @param $status
     * @param $payment
     * @param $trade_no
     * @param $remark
     * @return object|string
     */
    public function writeLog($user_name, $out_trade_no, $money, $status, $payment = null, $trade_no = null, $remark = null)
    {
        return $this->req('');
    }
}