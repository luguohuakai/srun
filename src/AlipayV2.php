<?php

namespace srun\src;

class AlipayV2 extends SrunV2
{
    public function __construct($srun_north_api_url = null)
    {
        parent::__construct($srun_north_api_url);
    }

    /**
     * step1. 获取支付宝跳转窗口的表单提交代码
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
        return $this->req('api/v2/alipay/pay', compact('out_trade_no', 'order_name', 'desc', 'money', 'notify_type', 'return_url'), 'post');
    }

    /**
     * step2. 将支付结果写入支付日志
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
        $data = compact('user_name', 'out_trade_no', 'money', 'status');
        if ($payment !== null) $data['payment'] = $payment;
        if ($trade_no !== null) $data['trade_no'] = $trade_no;
        if ($remark !== null) $data['remark'] = $remark;
        return $this->req('api/v2/alipay/write-log', $data, 'post');
    }
}