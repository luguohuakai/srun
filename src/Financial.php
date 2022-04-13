<?php

namespace srun\src;

class Financial extends Srun implements \srun\base\Financial
{
    public function __construct($srun_north_api_url, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        parent::__construct($srun_north_api_url, $srun_north_access_token, $srun_north_access_token_expire, $srun_north_access_token_redis_key);
    }

    /**
     * 电子钱包充值接口
     * @param $user_name
     * @param $amount
     * @param $order_no
     * @return object|string
     */
    public function rechargeWallet($user_name, $amount, $order_no)
    {
        $data = [
            'user_name' => $user_name,
            'pay_type_id' => 1,
            'pay_num' => $amount,
            'order_no' => $order_no,
        ];
        return $this->req('api/v1/financial/recharge-wallet', $data, 'post');
    }
}