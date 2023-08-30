# SRUN

> PHP >= 7.4
>
> 引入: composer require luguohuakai/srun:~1.0.0
>
> SRUN SDK(北向接口/单点登录) 和常用工具类封装

* 部分接口未实现的,或者需要扩展的,可以继承相应模块自行实现,也可以GitHub提交PR(欢迎PR)
* 日志: srun4k环境下默认位于: `/srun3/log/srun/`; 非srun4k环境: `/var/log/srun/`


* 如果要使用SDK请到srun4k 8080中配置北向接口权限
* 支持的接口 随北向接口更新

### 使用方法

```php
use func\src\Func;
use srun\src\User;

// 基本用法
$user = new User;
$user->view('srun');

// 手动配置接口地址
$user = new User('https://127.0.0.1:8001/')
$user->view('srun');

// 使用配置文件 配置文件参考 `vendor/luguohuakai/srun/config/srun.ini`
$ini = parse_ini_file('./srun.ini', true);
$user = new User(...$ini['north'])
$user->view('srun');

// 也可以先在自己项目中先初始化再调用
function initUser(): User
{
    $ini = parse_ini_file('./srun.ini');
    return new User($ini['srun_north_api_url']);
}
initUser()->view('srun');

// 手动配置缓存redis 老版本 < 1.0.8
$user = new User;
$user->setRdsConfig(['port' => 6380, 'host' => '127.0.0.1', 'pass' => 'xxx']);
$user->view('srun');

```

> 新版缓存逻辑: srun4k环境: 自动设置为redis缓存(16382); 非srun4k环境: 自动设置为文件缓存

### 缓存类使用方法

```php
/**
 * 目前支持 文件缓存 redis缓存
 * @param int $type 可选 0:自动设置 1:文件缓存Cache::CACHE_FILE, 2:redis缓存Cache::CACHE_REDIS
 * @param string $cache_file 可选 自定义文件缓存位置
 * @param array $rds_config 可选 自定义redis缓存配置 如: ['index' => 0,'port' => 6379,'host' => '127.0.0.1','pass' => null]
 */
use srun\src\Cache;

$cache = new Cache;
$cache->set($key, $value);
$cache->get($key);

```
