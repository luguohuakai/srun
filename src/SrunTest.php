<?php

namespace srun\src;

require_once 'FuncBase.php';
require_once 'Func.php';
require_once 'SrunBase.php';
require_once 'Srun.php';

// 可以先在自己项目中先初始化再调用
function init(): Srun
{
    $ini = parse_ini_file('./srun.ini');
    return new Srun($ini['srun_north_api_url']);
}

$res = init()->userBalance('srun');
Func::dd($res);

// 也可以直接调用
$ini = parse_ini_file('./srun.ini');
$srun = new Srun($ini['srun_north_api_url']);
$res = $srun->userBalance('srun');
Func::dd($res);
