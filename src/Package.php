<?php

namespace srun\src;

class Package extends Srun implements \srun\base\Package
{
    public function __construct($srun_north_api_url = null, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        parent::__construct($srun_north_api_url, $srun_north_access_token, $srun_north_access_token_expire, $srun_north_access_token_redis_key);
    }

    /**
     * 查询已订购的产品/套餐接口
     * @param $user_name
     * @return object|string
     */
    public function userPackage($user_name)
    {
        return $this->req('api/v1/package/users-packages', ['user_name' => $user_name]);
    }

    /**
     * 查询可购买的套餐接口
     * @param $user_name
     * @return object|string
     */
    public function package($user_name)
    {
        return $this->req('api/v1/packages', compact('user_name'));
    }

    /**
     * 购买套餐接口
     * @param $user_name
     * @param $product
     * @param $package
     * @return object|string
     */
    public function Buy($user_name, $product, $package)
    {
        return $this->req('api/v1/package/buy', compact('user_name', 'product', 'package'), 'post');
    }

    /**
     * 购买套餐高级接口
     * 1、用户当前必须订购了相应的产品,才能进行购买套餐操作,否则无法进行
     * 2、本接口应用场景为 赠送套餐， 不收取用户费用
     * @param $user_name
     * @param $product
     * @param $package
     * @return object|string
     */
    public function BuySuper($user_name, $product, $package)
    {
        return $this->req('api/v1/package/buy-super', compact('user_name', 'product', 'package'), 'post');
    }

    /**
     * 购买套餐接口二
     * @param $user_name
     * @param $pay_type_id
     * @param $pay_num
     * @param $order_no
     * @param $product
     * @param $package
     * @return object|string
     */
    public function Buy2($user_name, $pay_type_id, $pay_num, $order_no, $product, $package)
    {
        return $this->req('api/v1/package/buys', compact('user_name', 'pay_type_id', 'pay_num', 'order_no', 'product', 'pay_num'), 'post');
    }

    /**
     * 购买套餐批处理
     * 本接口执行结果会写入到一个 xlsx 文件内,操作结果均在文件内保存 .xlsx 文件内
     * 文件地址 /srun3/www/srun4-api/rest/web/batch_buy_package
     * @param $type
     * @param $package
     * @param $group_id
     * @param $product
     * @return object|string
     */
    public function Batch($type, $package, $group_id = null, $product = null)
    {
        $data = compact('type', 'package');
        if ($group_id !== null) $data['group_id'] = $group_id;
        if ($product !== null) $data['product'] = $product;
        return $this->req('api/v1/package/batch', $data, 'post');
    }
}