<?php

namespace srun\src;

class Product extends Srun implements \srun\base\Product
{
    public function __construct($srun_north_api_url, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        parent::__construct($srun_north_api_url, $srun_north_access_token, $srun_north_access_token_expire, $srun_north_access_token_redis_key);
    }

    /**
     * @param $product_name
     * @param $billing_id
     * @param $control_id
     * @param $binding_mode
     * @param $checkout_mode
     * @param $condition
     * @return mixed
     */
    public function create($product_name, $billing_id, $control_id, $binding_mode, $checkout_mode, $condition)
    {
        // TODO: Implement create() method.
    }

    /**
     * @param $product_id
     * @return mixed
     */
    public function delete($product_id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param $product_id
     * @param $product_name
     * @param $billing_id
     * @param $control_id
     * @param $binding_mode
     * @param $checkout_mode
     * @param $condition
     * @return mixed
     */
    public function update($product_id, $product_name, $billing_id, $control_id, $binding_mode, $checkout_mode, $condition)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param $user_name
     * @return mixed
     */
    public function canSubscribe($user_name)
    {
        // TODO: Implement canSubscribe() method.
    }

    /**
     * @param $product_id
     * @return mixed
     */
    public function useNumber($product_id)
    {
        // TODO: Implement useNumber() method.
    }

    /**
     * @return mixed
     */
    public function index()
    {
        // TODO: Implement index() method.
    }

    /**
     * @param $product_id
     * @return mixed
     */
    public function view($product_id)
    {
        // TODO: Implement view() method.
    }

    /**
     * @param $user_name
     * @param $amount
     * @param $product
     * @param $transfer_account
     * @return mixed
     */
    public function recharge($user_name, $amount, $product = null, $transfer_account = null)
    {
        // TODO: Implement recharge() method.
    }

    /**
     * @param $user_name
     * @param $product
     * @param array $other
     * @return mixed
     */
    public function rechargeSuper($user_name, $product = null, array $other = [])
    {
        // TODO: Implement rechargeSuper() method.
    }

    /**
     * @param $user_name
     * @param $product
     * @param $amount
     * @param $is_use
     * @return mixed
     */
    public function subscribe($user_name, $product, $amount = null, $is_use = null)
    {
        // TODO: Implement subscribe() method.
    }

    /**
     * @param $user_name
     * @param $product_id_form
     * @param $product_id_to
     * @return mixed
     */
    public function nextBillingCycle($user_name, $product_id_form, $product_id_to)
    {
        // TODO: Implement nextBillingCycle() method.
    }

    /**
     * @param $user_name
     * @param $product_id_form
     * @param $product_id_to
     * @param $checkout_type
     * @return mixed
     */
    public function transfer($user_name, $product_id_form, $product_id_to, $checkout_type = null)
    {
        // TODO: Implement transfer() method.
    }

    /**
     * @param $user_name
     * @param $product_id_form
     * @param $product_id_to
     * @param $change_at
     * @return mixed
     */
    public function reservationTransfer($user_name, $product_id_form, $product_id_to, $change_at)
    {
        // TODO: Implement reservationTransfer() method.
    }

    /**
     * @param $user_name
     * @param $product_id
     * @return mixed
     */
    public function cancelReservationTransfer($user_name, $product_id)
    {
        // TODO: Implement cancelReservationTransfer() method.
    }

    /**
     * @param $user_name
     * @param $product_id
     * @return mixed
     */
    public function cancel($user_name, $product_id)
    {
        // TODO: Implement cancel() method.
    }

    /**
     * @param $user_name
     * @param $product_id
     * @return mixed
     */
    public function disable($user_name, $product_id)
    {
        // TODO: Implement disable() method.
    }

    /**
     * @param $user_name
     * @param $product_id
     * @return mixed
     */
    public function enable($user_name, $product_id)
    {
        // TODO: Implement enable() method.
    }

    /**
     * @param $mobile_phone
     * @param $mobile_password
     * @param $action
     * @param $is_use
     * @return mixed
     */
    public function checkOperator($mobile_phone, $mobile_password, $action, $is_use = null)
    {
        // TODO: Implement checkOperator() method.
    }

    /**
     * @param $user_name
     * @param $product_id
     * @param $mobile_phone
     * @param $mobile_password
     * @param $user_available
     * @param $action
     * @return mixed
     */
    public function operator($user_name, $product_id, $mobile_phone = null, $mobile_password = null, $user_available = null, $action = null)
    {
        // TODO: Implement operator() method.
    }

    /**
     * @param $product_id
     * @param $start_at
     * @param $stop_at
     * @param $per_page
     * @param $page
     * @return mixed
     */
    public function expire($product_id, $start_at = null, $stop_at = null, $per_page = null, $page = null)
    {
        // TODO: Implement expire() method.
    }

    /**
     * @param $user_name
     * @param $operate_type
     * @param $monthly_fee
     * @param $product_id
     * @return mixed
     */
    public function refund($user_name, $operate_type, $monthly_fee = null, $product_id = null)
    {
        // TODO: Implement refund() method.
    }
}