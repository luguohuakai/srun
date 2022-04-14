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
     * @return object|string
     */
    public function create($product_name, $billing_id, $control_id, $binding_mode, $checkout_mode, $condition)
    {
        return $this->req('');
    }

    /**
     * @param $product_id
     * @return object|string
     */
    public function delete($product_id)
    {
        return $this->req('');
    }

    /**
     * @param $product_id
     * @param $product_name
     * @param $billing_id
     * @param $control_id
     * @param $binding_mode
     * @param $checkout_mode
     * @param $condition
     * @return object|string
     */
    public function update($product_id, $product_name, $billing_id, $control_id, $binding_mode, $checkout_mode, $condition)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @return object|string
     */
    public function canSubscribe($user_name)
    {
        return $this->req('');
    }

    /**
     * @param $product_id
     * @return object|string
     */
    public function useNumber($product_id)
    {
        return $this->req('');
    }

    /**
     * @return object|string
     */
    public function index()
    {
        return $this->req('');
    }

    /**
     * @param $product_id
     * @return object|string
     */
    public function view($product_id)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $amount
     * @param $product
     * @param $transfer_account
     * @return object|string
     */
    public function recharge($user_name, $amount, $product = null, $transfer_account = null)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $product
     * @param array $other
     * @return object|string
     */
    public function rechargeSuper($user_name, $product = null, array $other = [])
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $product
     * @param $amount
     * @param $is_use
     * @return object|string
     */
    public function subscribe($user_name, $product, $amount = null, $is_use = null)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $product_id_form
     * @param $product_id_to
     * @return object|string
     */
    public function nextBillingCycle($user_name, $product_id_form, $product_id_to)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $product_id_form
     * @param $product_id_to
     * @param $checkout_type
     * @return object|string
     */
    public function transfer($user_name, $product_id_form, $product_id_to, $checkout_type = null)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $product_id_form
     * @param $product_id_to
     * @param $change_at
     * @return object|string
     */
    public function reservationTransfer($user_name, $product_id_form, $product_id_to, $change_at)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $product_id
     * @return object|string
     */
    public function cancelReservationTransfer($user_name, $product_id)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $product_id
     * @return object|string
     */
    public function cancel($user_name, $product_id)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $product_id
     * @return object|string
     */
    public function disable($user_name, $product_id)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $product_id
     * @return object|string
     */
    public function enable($user_name, $product_id)
    {
        return $this->req('');
    }

    /**
     * @param $mobile_phone
     * @param $mobile_password
     * @param $action
     * @param $is_use
     * @return object|string
     */
    public function checkOperator($mobile_phone, $mobile_password, $action, $is_use = null)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $product_id
     * @param $mobile_phone
     * @param $mobile_password
     * @param $user_available
     * @param $action
     * @return object|string
     */
    public function operator($user_name, $product_id, $mobile_phone = null, $mobile_password = null, $user_available = null, $action = null)
    {
        return $this->req('');
    }

    /**
     * @param $product_id
     * @param $start_at
     * @param $stop_at
     * @param $per_page
     * @param $page
     * @return object|string
     */
    public function expire($product_id, $start_at = null, $stop_at = null, $per_page = null, $page = null)
    {
        return $this->req('');
    }

    /**
     * @param $user_name
     * @param $operate_type
     * @param $monthly_fee
     * @param $product_id
     * @return object|string
     */
    public function refund($user_name, $operate_type, $monthly_fee = null, $product_id = null)
    {
        return $this->req('');
    }
}