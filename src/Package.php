<?php

namespace srun\src;

class Package extends Srun implements \srun\base\Package
{
    public function __construct($srun_north_api_url, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        parent::__construct($srun_north_api_url, $srun_north_access_token, $srun_north_access_token_expire, $srun_north_access_token_redis_key);
    }

    // 查询已订购的产品/套餐接口
    public function userPackage($user_name)
    {
        return $this->req('api/v1/package/users-packages', ['user_name' => $user_name]);
    }

    /**
     * @param $user_name
     * @return mixed
     */
    public function package($user_name)
    {
        // TODO: Implement package() method.
    }

    /**
     * @param $user_name
     * @param $product
     * @param $package
     * @return mixed
     */
    public function Buy($user_name, $product, $package)
    {
        // TODO: Implement Buy() method.
    }

    /**
     * @param $user_name
     * @param $product
     * @param $package
     * @return mixed
     */
    public function BuySuper($user_name, $product, $package)
    {
        // TODO: Implement BuySuper() method.
    }

    /**
     * @param $user_name
     * @param $pay_type_id
     * @param $pay_num
     * @param $order_no
     * @param $product
     * @param $package
     * @return mixed
     */
    public function Buy2($user_name, $pay_type_id, $pay_num, $order_no, $product, $package)
    {
        // TODO: Implement Buy2() method.
    }

    /**
     * @param $type
     * @param $package
     * @param $group_id
     * @param $product
     * @return mixed
     */
    public function Batch($type, $package, $group_id = null, $product = null)
    {
        // TODO: Implement Batch() method.
    }
}