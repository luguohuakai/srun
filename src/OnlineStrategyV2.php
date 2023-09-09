<?php

namespace srun\src;

class OnlineStrategyV2 extends SrunV2
{
    public function __construct($srun_north_api_url = null)
    {
        parent::__construct($srun_north_api_url);
    }

    /**
     * 查询用户可订购的产品列表<br>
     * 此处管理可通过 8080 系统设置/自服务设置/修改产品设置 进行管理
     * @param $user_name
     * @return object|string
     */
    public function canSubscribe($user_name)
    {
        return $this->req('api/v2/product/can-subscribe-products', compact('user_name'));
    }

    /**
     * 产品使用人数接口
     * @param $product_id
     * @return object|string
     */
    public function useNumber($product_id)
    {
        return $this->req('api/v2/product/use-number', ['products_id' => $product_id]);
    }

    /**
     * 产品续费接口
     * 接口依赖 `transfer_balance ` 表(转账表)
     * @param $user_name
     * @param $amount
     * @param $product
     * @param $transfer_account
     * @return object|string
     */
    public function recharge($user_name, $amount, $product = null, $transfer_account = null)
    {
        $data = compact('user_name', 'amount');
        if ($product !== null) $data['product'] = $product;
        if ($transfer_account !== null) $data['transfer_account'] = $transfer_account;
        return $this->req('api/v2/product/product-recharge', $data, 'post');
    }

    /**
     * 产品续费高级接口
     * 用户可以直接通过本接口将现金，一卡通，充值卡里面的可用金额充值到产品内，降低了错误的发生机率。
     * @param $user_name
     * @param $product
     * @param array $other
     * @return object|string
     */
    public function rechargeSuper($user_name, $product = null, array $other = [])
    {
        $data = compact('user_name');
        if ($product !== null) $data['product'] = $product;
        if (!empty($other)) $data = array_merge($data, $other);
        return $this->req('api/v2/product/recharge', $data, 'post');
    }

    /**
     * 订购产品接口
     * @param $user_name
     * @param $product
     * @param $amount
     * @param $is_use
     * @return object|string
     */
    public function subscribe($user_name, $product, $amount = null, $is_use = null)
    {
        $data = compact('user_name', 'product');
        if ($amount !== null) $data['amount'] = $amount;
        if ($is_use !== null) $data['is_use'] = $is_use;
        return $this->req('api/v2/product/subscribe', $data, 'post');
    }

    /**
     * 产品转移(下月生效)
     * @param $user_name
     * @param $product_id_form
     * @param $product_id_to
     * @return object|string
     */
    public function nextBillingCycle($user_name, $product_id_form, $product_id_to)
    {
        $data = compact('user_name');
        $data['products_id_from'] = $product_id_form;
        $data['products_id_to'] = $product_id_to;
        return $this->req('api/v2/product/next-billing-cycle', $data, 'post');
    }

    /**
     * 产品转组(立即生效)
     * @param $user_name
     * @param $product_id_form
     * @param $product_id_to
     * @param $checkout_type
     * @return object|string
     */
    public function transfer($user_name, $product_id_form, $product_id_to, $checkout_type = null)
    {
        $data = compact('user_name');
        $data['products_id_from'] = $product_id_form;
        $data['products_id_to'] = $product_id_to;
        if ($checkout_type !== null) $data['checkout_type'] = $checkout_type;
        return $this->req('api/v2/product/transfer-product', $data, 'post');
    }

    /**
     * 产品转移(预约转移)
     * @param $user_name
     * @param $product_id_form
     * @param $product_id_to
     * @param $change_at
     * @return object|string
     */
    public function reservationTransfer($user_name, $product_id_form, $product_id_to, $change_at)
    {
        $data = compact('user_name');
        $data['products_id_from'] = $product_id_form;
        $data['products_id_to'] = $product_id_to;
        if ($change_at !== null) $data['change_at'] = $change_at;
        return $this->req('api/v2/product/reservation-transfer-products', $data, 'post');
    }

    /**
     * 产品转移(取消预约转移)
     * @param $user_name
     * @param $product_id
     * @return object|string
     */
    public function cancelReservationTransfer($user_name, $product_id)
    {
        return $this->req('api/v2/product/cancel-reservation-transfer-products', ['user_name' => $user_name, 'products_id' => $product_id], 'post');
    }

    /**
     * 取消产品接口<br>
     * 如果返回取消失败, 可以申请一线人员协助, 查一下账号所在用户组是否有操作该产品的权利<br>
     * 管理后台/系统设置/自服务设置/修改产品设置->点击目标用户组 即可查看该组可使用的产品
     * @param $user_name
     * @param $product_id
     * @return object|string
     */
    public function cancelProduct($user_name, $product_id)
    {
        return $this->req('api/v2/product/cancel', ['user_name' => $user_name, 'products_id' => $product_id], 'delete');
    }

    /**
     * 禁用产品接口
     * @param $user_name
     * @param $product_id
     * @return object|string
     */
    public function disableProduct($user_name, $product_id)
    {
        return $this->req('api/v2/product/disable-product', ['user_name' => $user_name, 'products_id' => $product_id], 'post');
    }

    /**
     * 启用产品接口
     * @param $user_name
     * @param $product_id
     * @return object|string
     */
    public function enableProduct($user_name, $product_id)
    {
        return $this->req('api/v2/product/enable-product', ['user_name' => $user_name, 'products_id' => $product_id], 'post');
    }

    /**
     * 验证运营商账号密码
     * 应用场景:验证运营商账号密码是否正确
     * 说明:在使用本功能之前，请相关负责人先设置好Interface核心接口所在IP地址，深澜工程师请看为知笔记
     * 验证运营商账号总共分三步来完成
     * 第一步 发参数 action=login， 触发 PA 代拨上线
     * 第二步 发参数 action=check， 验证是否成功
     * 第三步 发参数 action=logout，触发 PA 代拨下线
     * @param $mobile_phone
     * @param $mobile_password
     * @param $action
     * @param $is_use
     * @return object|string
     */
    public function checkOperator($mobile_phone, $mobile_password, $action, $is_use = null)
    {
        $data = compact('mobile_phone', 'mobile_password', 'action');
        if ($is_use !== null) $data['is_use'] = $is_use;
        return $this->req('api/v2/product/check-operators', $data, 'post');
    }

    /**
     * 绑定/解绑运营商账号
     * 应用场景:在多运营商的场景中，每个运营商使用一个产品
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
        $data = compact('user_name');
        $data['products_id'] = $product_id;
        if ($mobile_phone !== null) $data['mobile_phone'] = $mobile_phone;
        if ($mobile_password !== null) $data['mobile_password'] = $mobile_password;
        if ($user_available !== null) $data['user_available'] = $user_available;
        if ($action !== null) $data['action'] = $action;
        return $this->req('api/v2/product/operators', $data, 'post');
    }

    /**
     * 查询产品到期用户接口
     * @param $product_id
     * @param $start_at
     * @param $stop_at
     * @param $per_page
     * @param $page
     * @return object|string
     */
    public function expire($product_id, $start_at = null, $stop_at = null, $per_page = null, $page = null)
    {
        $data['products_id'] = $product_id;
        if ($start_at !== null) $data['start_at'] = $start_at;
        if ($stop_at !== null) $data['stop_at'] = $stop_at;
        if ($per_page !== null) $data['per-page'] = $per_page;
        if ($page !== null) $data['page'] = $page;
        return $this->req('api/v2/product/expire', $data);
    }

    /**
     * 产品退费接口
     * @param $user_name
     * @param $operate_type
     * @param $monthly_fee
     * @param $product_id
     * @return object|string
     */
    public function refund($user_name, $operate_type, $monthly_fee = null, $product_id = null)
    {
        $data = compact('user_name', 'operate_type');
        if ($monthly_fee !== null) $data['monthly_fee'] = $monthly_fee;
        if ($product_id !== null) $data['products_id'] = $product_id;
        return $this->req('api/v2/product/refund', $data, 'post');
    }

    /**
     * 查询已订购的产品/套餐【统计时长/流量包可用总和】
     * @param $user_name
     * @return object|string
     */
    public function userPackage($user_name)
    {
        return $this->req('api/v2/package/users-packages', ['user_name' => $user_name]);
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
        return $this->req('api/v2/package/buy', compact('user_name', 'product', 'package'), 'post');
    }

    /**
     * 购买套餐 - 赠送<br>
     * 购买套餐高级接口<br>
     * 1、用户当前必须订购了相应的产品,才能进行购买套餐操作,否则无法进行<br>
     * 2、本接口应用场景为 赠送套餐， 不收取用户费用
     * @param $user_name
     * @param $product
     * @param $package
     * @return object|string
     */
    public function BuySuper($user_name, $product, $package)
    {
        return $this->req('api/v2/package/buy-super', compact('user_name', 'product', 'package'), 'post');
    }

    /**
     * 查询可购买的套餐接口
     * @param $user_name
     * @return object|string
     */
    public function package($user_name)
    {
        return $this->req('api/v2/packages', compact('user_name'));
    }

    /**
     * 购买套餐接口二<br>
     * 本接口融合了 电子钱包充值+订购套餐 两个接口，请求参数同样也分为两个部分<br>
     * 电子钱包充值请求参数以及购买套餐请求参数，如有疑问可参考电子钱包缴费请求参数来填写
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
        return $this->req('api/v2/package/buys', compact('user_name', 'pay_type_id', 'pay_num', 'order_no', 'product', 'pay_num'), 'post');
    }

    /**
     * 购买套餐批处理<br>
     * type 字段说明<br>
     * type = group_id 批量为 一个或多个用户组 下面的用户购买套餐, 此时 group_id 必填, product 字段如果不填写 系统会为用户正在使用的产品订购套餐<br>
     * type = product 批量为已经订购 某一个产品 的所有用户购买套餐,此时 group_id 不填
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
        return $this->req('api/v2/package/batch', $data, 'post');
    }

    /**
     * 查询用户订购的产品是否绑定运营商账号
     * @param $user_name
     * @param $products_id
     * @return object|string
     */
    public function searchMobilePhone($user_name, $products_id = null)
    {
        $data = compact('user_name');
        if ($products_id !== null) $data['products_id'] = $products_id;
        return $this->req('api/v2/user/search-mobile-phone', $data);
    }
}