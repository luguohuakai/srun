<?php

namespace srun\base;

interface Message
{
    public function notice($account, $receive_type, $subject, $target = null, $product_id = null);

    public function message($type = null, $sys_mgr_user_name = null);

    public function newMessage(array $param = []);

    public function keyEvent($user_name, $key_name);

    public function keyThird($eventSource, $eventType, $variable);

    public function keyView($key_name);
}