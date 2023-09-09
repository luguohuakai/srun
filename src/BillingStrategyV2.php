<?php

namespace srun\src;

class BillingStrategyV2 extends SrunV2
{
    public function __construct($srun_north_api_url = null)
    {
        parent::__construct($srun_north_api_url);
    }

    /**
     * 添加计费策略
     * @param $billing_name
     * @param $billing_num
     * @param $billing_unit
     * @param $billing_rate
     * @param array $other
     * @return object|string
     */
    public function billingCreate($billing_name, $billing_num, $billing_unit, $billing_rate, array $other = [])
    {
        $data = compact('billing_name', 'billing_num', 'billing_unit', 'billing_rate');
        if (!empty($other)) $data = array_merge($data, $other);
        return $this->req('api/v2/strategy/billing-create', $data, 'post');
    }

    /**
     * 产品详情接口
     * @param $product_id
     * @return object|string
     */
    public function productView($product_id)
    {
        return $this->req('api/v2/product/view', ['products_id' => $product_id]);
    }

    /**
     * 增加产品接口
     * @param $product_name
     * @param $billing_id
     * @param $control_id
     * @param $binding_mode
     * @param $checkout_mode
     * @param $condition
     * @return object|string
     */
    public function productCreate($product_name, $billing_id, $control_id, $binding_mode, $checkout_mode, $condition)
    {
        $data = compact('billing_id', 'control_id', 'binding_mode', 'checkout_mode', 'condition');
        $data['products_name'] = $product_name;
        return $this->req('api/v2/product/create', $data, 'post');
    }

    /**
     * 删除产品接口
     * @param $product_id
     * @return object|string
     */
    public function productDelete($product_id)
    {
        return $this->req('api/v2/product/delete', ['products_id' => $product_id], 'delete');
    }

    /**
     * 修改产品接口
     * @param int product_id
     * @param string product_name
     * @param string|int billing_id
     * @param string|int control_id
     * @param string binding_mode
     * @param int checkout_mode
     * @param string condition
     * @return object|string
     */
    public function productUpdate($param = [])
    {
        return $this->req('api/v2/product/update', $param, 'put');
    }

    /**
     * 查询计费策略列表接口 产品列表接口
     * @return object|string
     */
    public function productIndex()
    {
        return $this->req('api/v2/product/index');
    }

    /**
     * 添加控制策略
     * @param $control_name
     * @param array $other
     * @return object|string
     */
    public function controlCreate($control_name, array $other = [])
    {
        return $this->req('api/v2/strategy/control-create', array_merge(compact('control_name'), $other), 'post');
    }
}