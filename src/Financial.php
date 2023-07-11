<?php

namespace srun\src;

class Financial extends Srun implements \srun\base\Financial
{
    public function __construct($srun_north_api_url = null, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
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
        if (!empty($other)) $data = array_merge($data, $other);
        return $this->req('api/v1/financial/recharge-wallet', $data, 'post');
    }

    /**
     * 缴费记录接口
     * @param $user_name
     * @param null $page
     * @param null $size
     * @param $order_no
     * @param $start_at
     * @param $stop_at
     * @param $pay_type_id
     * @return object|string
     */
    public function paymentRecord($user_name, $page = null, $size = null, $order_no = null, $start_at = null, $stop_at = null, $pay_type_id = null)
    {
        $data = compact('user_name');
        if ($page !== null) $data['page'] = $page;
        if ($size !== null) $data['per-page'] = $size;
        if ($order_no !== null) $data['order_no'] = $order_no;
        if ($start_at !== null) $data['start_at'] = $start_at;
        if ($stop_at !== null) $data['stop_at'] = $stop_at;
        if ($pay_type_id !== null) $data['pay_type_id'] = $pay_type_id;
        return $this->req('api/v1/financial/payment-records', $data);
    }

    /**
     * 退费记录接口
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
        $data = compact('user_name');
        if ($refund_num !== null) $data['refund_num'] = $refund_num;
        if ($product_id !== null) $data['product_id'] = $product_id;
        if ($mgr_name !== null) $data['mgr_name'] = $mgr_name;
        if ($start_at !== null) $data['start_at'] = $start_at;
        if ($stop_at !== null) $data['stop_at'] = $stop_at;
        return $this->req('api/v1/financial/refund', $data);
    }

    /**
     * 结算清单接口
     * @param $user_name
     * @param $start_at
     * @param $stop_at
     * @return object|string
     */
    public function checkoutListDetail($user_name, $start_at = null, $stop_at = null)
    {
        $data = compact('user_name');
        if ($start_at !== null) $data['start_at'] = $start_at;
        if ($stop_at !== null) $data['stop_at'] = $stop_at;
        return $this->req('api/v1/checkoutlist/detail', $data);
    }

    /**
     * 电子钱包内余额转账
     * @param $user_name
     * @param $amount
     * @param $transfer_account
     * @return object|string
     */
    public function transfer($user_name, $amount, $transfer_account)
    {
        return $this->req('api/v1/financial/transfer', compact('user_name', 'amount', 'transfer_account'), 'post');
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
     * 充值卡查询接口
     * @param $ka_card_num
     * @param $ka_passwd
     * @return object|string
     */
    public function rechargeCard($ka_card_num, $ka_passwd)
    {
        return $this->req('api/v1/financial/recharge-cards', compact('ka_card_num', 'ka_passwd'));
    }

    /**
     * 缴纳开户费接口
     * 缴纳开户费接口前提: 用户的当前状态为 未开通 才可以缴纳开户费。缴纳完成后，系统自动将 未开通 状态 修正为 正常 状态
     * @param $extra_pay_id
     * @param array $other
     * @return object|string
     */
    public function extraPay($extra_pay_id, array $other = [])
    {
        return $this->req('api/v1/financial/extra-pay', array_merge(compact('extra_pay_id'), $other), 'post');
    }

    /**
     * 支付包/微信缴费数据同步
     * 适用范围：比如用户已经对接了各缴费平台，比如微信企业号缴费。
     * 用户在平台缴费成功后，只需要将缴费数据通过本接口同步重要参数即可在深澜系统进行电子钱包充值操作。
     * pay_type 参数非常重要， 请准确填写
     * @param $user_name
     * @param $trade_no
     * @param $money
     * @param $buy_time
     * @param int $pay_type 支付方式 0:支付宝 1:微信支付 2:校付通
     * @param $remark
     * @return object|string
     */
    public function paymentDataSync($user_name, $trade_no, $money, $buy_time, $pay_type, $remark = null)
    {
        $data = compact('user_name', 'trade_no', 'money', 'buy_time', 'pay_type');
        if ($remark !== null) $data['remark'] = $remark;
        return $this->req('api/v1/financial/payment-data-sync', $data, 'post');
    }

    /**
     * 缴费对账接口
     * @param $pay_type_id
     * @param $start_time
     * @param $stop_time
     * @return object|string
     */
    public function paymentOverview($pay_type_id, $start_time = null, $stop_time = null)
    {
        $data = compact('pay_type_id');
        if ($start_time !== null) $data['start_time'] = $start_time;
        if ($stop_time !== null) $data['stop_time'] = $stop_time;
        return $this->req('api/v1/financial/payment-overview', $data);
    }

    /**
     * 查询缴费方式接口
     * @return object|string
     */
    public function payType()
    {
        return $this->req('api/v1/financial/pay-type');
    }

    /**
     * 添加缴费方式接口
     * @param $type_name
     * @param $default
     * @param $is_balance
     * @return object|string
     */
    public function createPayment($type_name, $default = null, $is_balance = null)
    {
        $data = compact('type_name');
        if ($default !== null) $data['default'] = $default;
        if ($is_balance !== null) $data['is_balance'] = $is_balance;
        return $this->req('api/v1/financial/create-payment', $data, 'post');
    }

    /**
     * 修改缴费方式接口
     * @param $pay_type_id
     * @param $type_name
     * @param $default
     * @param $is_balance
     * @return object|string
     */
    public function updatePayment($pay_type_id, $type_name = null, $default = null, $is_balance = null)
    {
        $data = compact('pay_type_id');
        if ($type_name !== null) $data['type_name'] = $type_name;
        if ($default !== null) $data['default'] = $default;
        if ($is_balance !== null) $data['is_balance'] = $is_balance;
        return $this->req('api/v1/financial/update-payment', $data, 'post');
    }

    /**
     * 删除缴费方式接口
     * @param $pay_type_id
     * @return object|string
     */
    public function deletePayment($pay_type_id)
    {
        return $this->req('api/v1/financial/delete-payment', compact('pay_type_id'), 'delete');
    }

    /**
     * 查询用充值卡充值流量包记录
     * 使用流量卡为用户的流量套餐包充流量
     * @param $user_name
     * @param $start_at
     * @param $stop_at
     * @return object|string
     */
    public function packageRechargeRecord($user_name, $start_at = null, $stop_at = null)
    {
        $data = compact('user_name');
        if ($start_at !== null) $data['start_at'] = $start_at;
        if ($stop_at !== null) $data['stop_at'] = $stop_at;
        return $this->req('api/v1/financial/package-recharge-record', $data);
    }
}