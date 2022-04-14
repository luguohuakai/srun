<?php

namespace srun\base;

interface Alipay
{
    public function pay($out_trade_no, $order_name, $desc, $money, $notify_type, $return_url);

    public function writeLog($user_name, $out_trade_no, $money, $status, $payment = null, $trade_no = null, $remark = null);
}