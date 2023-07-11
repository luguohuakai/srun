<?php

namespace srun\base;

interface Financial
{
    public function paymentRecord($user_name, $page = null, $size = null, $order_no = null, $start_at = null, $stop_at = null, $pay_type_id = null);

    public function refund($user_name, $refund_num = null, $product_id = null, $mgr_name = null, $start_at = null, $stop_at = null);

    public function checkoutListDetail($user_name, $start_at = null, $stop_at = null);

    public function rechargeWallet($user_name, $pay_type_id = 1, $other = []);

    public function transfer($user_name, $amount, $transfer_account);

    public function userBalance($user_name);

    public function rechargeCard($ka_card_num, $ka_passwd);

    public function extraPay($extra_pay_id, array $other = []);

    public function paymentDataSync($user_name, $trade_no, $money, $buy_time, $pay_type, $remark = null);

    public function paymentOverview($pay_type_id, $start_time = null, $stop_time = null);

    public function payType();

    public function createPayment($type_name, $default = null, $is_balance = null);

    public function updatePayment($pay_type_id, $type_name = null, $default = null, $is_balance = null);

    public function deletePayment($pay_type_id);

    public function packageRechargeRecord($user_name, $start_at = null, $stop_at = null);
}
