<?php

namespace srun\src;

use func\src\Func;

require_once '../base/Srun.php';
require_once 'Srun.php';

// 可以先在自己项目中先初始化再调用
function initUser(): User
{
    $ini = parse_ini_file('./srun.ini');
    return new User($ini['srun_north_api_url']);
}

$res = initUser()->userBalance('srun');
Func::dd($res);

// 也可以直接调用
$ini = parse_ini_file('./srun.ini');
$user = new User($ini['srun_north_api_url']);
$res = $user->userBalance('srun');
Func::dd($res);
