<?php

namespace srun\base;

interface Package
{
    public function userPackage($user_name);

    public function package($user_name);

    public function Buy($user_name, $product, $package);

    public function BuySuper($user_name, $product, $package);

    public function Buy2($user_name, $pay_type_id, $pay_num, $order_no, $product, $package);

    public function Batch($type, $package, $group_id = null, $product = null);
}