# SRUN

> PHP >= 7.0
>
> 引入: composer require luguohuakai/srun
>
> SRUN SDK(北向接口/单点登录) 和常用工具类封装

* 如果要使用SDK请到srun4k 8080中配置北向接口权限
* 支持的接口 随北向接口更新
* 使用方法

```php
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
```
* 部分接口未实现的,或者需要扩展的,可以继承相应模块自行实现,也可以GitHub提交PR(欢迎PR)

