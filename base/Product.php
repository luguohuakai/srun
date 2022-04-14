<?php

namespace srun\base;

interface Product
{
    public function create($product_name, $billing_id, $control_id, $binding_mode, $checkout_mode, $condition);

    public function delete($product_id);

    public function update($product_id, $product_name, $billing_id, $control_id, $binding_mode, $checkout_mode, $condition);

    public function canSubscribe($user_name);

    public function useNumber($product_id);

    public function index();

    public function view($product_id);

    public function recharge($user_name, $amount, $product = null, $transfer_account = null);

    public function rechargeSuper($user_name, $product = null, array $other = []);

    public function subscribe($user_name, $product, $amount = null, $is_use = null);

    public function nextBillingCycle($user_name, $product_id_form, $product_id_to);

    public function transfer($user_name, $product_id_form, $product_id_to, $checkout_type = null);

    public function reservationTransfer($user_name, $product_id_form, $product_id_to, $change_at);

    public function cancelReservationTransfer($user_name, $product_id);

    public function cancel($user_name, $product_id);

    public function disable($user_name, $product_id);

    public function enable($user_name, $product_id);

    public function checkOperator($mobile_phone, $mobile_password, $action, $is_use = null);

    public function operator($user_name, $product_id, $mobile_phone = null, $mobile_password = null, $user_available = null, $action = null);

    public function expire($product_id, $start_at = null, $stop_at = null, $per_page = null, $page = null);

    public function refund($user_name, $operate_type, $monthly_fee = null, $product_id = null);
}