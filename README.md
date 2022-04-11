# SRUN
> 引入: composer require luguohuakai/srun

> SRUN SDK(北向接口/单点登录) 和常用工具类封装

* 如果要使用SDK请到srun4k 8080中配置北向接口权限
* 支持的接口 随北向接口更新
* 使用方法

```php
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
```
