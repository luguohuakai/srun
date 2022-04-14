<?php

namespace srun\base;

interface Strategy
{
    public function billingCreate($billing_name, $billing_num, $billing_unit, $billing_rate, array $other = []);

    public function controlCreate($control_name, array $other = []);
}