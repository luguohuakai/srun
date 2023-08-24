<?php

namespace srun\src;

use luguohuakai\func\Func;

require_once '../base/Srun.php';
require_once 'Srun.php';

// 可以先在自己项目中先初始化再调用
function initUser(): User
{
    $ini = parse_ini_file('./srun.ini');
    return new User($ini['srun_north_api_url']);
}

$res = initUser()->view('srun');
Func::dd($res);

// 也可以直接调用
$ini = parse_ini_file('./srun.ini');
$user = new User($ini['srun_north_api_url']);
$res = $user->view('srun');
Func::dd($res);

(new Alipay(''))->req('');
(new Auth(''))->req('');
(new Financial(''))->req('');
(new Group(''))->req('');
(new Message(''))->req('');
(new Package(''))->req('');
(new Product(''))->req('');
(new Setting(''))->req('');
(new Strategy(''))->req('');
