<?php

namespace srun\base;

interface Financial
{
    public function rechargeWallet($user_name, $amount, $order_no);
}