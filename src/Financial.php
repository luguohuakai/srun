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
     * @param int $pay_type_id
     * @param array $other
     * @return object|string
     */
    public function rechargeWallet($user_name, $pay_type_id = 1, $other = [])
    {
        $data = [
            'user_name' => $user_name,
            'pay_type_id' => $pay_type_id,
        ];
        $data = array_merge($data, $other);
        return $this->req('api/v1/financial/recharge-wallet', $data, 'post');
    }

    /**
     * @param $user_name
     * @param $order_no
     * @param $start_at
     * @param $stop_at
     * @param $pay_type_id
     * @return object|string
     */
    public function paymentRecord($user_name, $order_no = null, $start_at = null, $stop_at = null, $pay_type_id = null)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $refund_num
     * @param $product_id
     * @param $mgr_name
     * @param $start_at
     * @param $stop_at
     * @return object|string
     */
    public function refund($user_name, $refund_num = null, $product_id = null, $mgr_name = null, $start_at = null, $stop_at = null)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $start_at
     * @param $stop_at
     * @return object|string
     */
    public function checkoutListDetail($user_name, $start_at = null, $stop_at = null)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $amount
     * @param $transfer_account
     * @return object|string
     */
    public function transfer($user_name, $amount, $transfer_account)
    {
        return $this->req('');
    }

    /**
     * 返回电子钱包余额接口
     * @param $user_name
     * @return object|string
     */
    public function userBalance($user_name)
    {
        return $this->req('api/v1/user/balance', ['user_name' => $user_name]);
    }

    /**
     * @param $ka_card_num
     * @param $ka_passwd
     * @return object|string
     */
    public function rechargeCard($ka_card_num, $ka_passwd)
    {
        return $this->req('');
    }

    /**
     * @param $extra_pay_id
     * @param array $other
     * @return object|string
     */
    public function extraPay($extra_pay_id, array $other = [])
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $trade_no
     * @param $money
     * @param $buy_time
     * @param $pay_type
     * @param $remark
     * @return object|string
     */
    public function paymentDataSync($user_name, $trade_no, $money, $buy_time, $pay_type, $remark = null)
    {
        return $this->req('');
    }

    /**
     * @param $pay_type_id
     * @param $start_time
     * @param $stop_time
     * @return object|string
     */
    public function paymentOverview($pay_type_id, $start_time = null, $stop_time = null)
    {
        return $this->req('');
    }

    /**
     * @return object|string
     */
    public function payType()
    {
        return $this->req('');
    }

    /**
     * @param $type_name
     * @param $default
     * @param $is_balance
     * @return object|string
     */
    public function createPayment($type_name, $default = null, $is_balance = null)
    {
        return $this->req('');
    }

    /**
     * @param $pay_type_id
     * @param $type_name
     * @param $default
     * @param $is_balance
     * @return object|string
     */
    public function updatePayment($pay_type_id, $type_name = null, $default = null, $is_balance = null)
    {
        return $this->req('');
    }

    /**
     * @param $pay_type_id
     * @return object|string
     */
    public function deletePayment($pay_type_id)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $start_at
     * @param $stop_at
     * @return object|string
     */
    public function packageRechargeRecord($user_name, $start_at = null, $stop_at = null)
    {
        return $this->req('');
    }
}