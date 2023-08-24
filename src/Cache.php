<?php

namespace srun\src;

use luguohuakai\func\Func;

class Cache
{
    // CACHE_FILE:文件缓存,CACHE_REDIS:redis缓存
    const CACHE_FILE = 1;
    const CACHE_REDIS = 2;
    // 当处于srun4k环境时使用redis缓存, 可手动设置为file缓存
    // 当不是srun4k环境时默认使用文件缓存, 可手动设置为redis缓存
    private $cache_type = self::CACHE_FILE;
    private $in_srun4k = false;

    private $cache_file = './.srun_cache';

    private $rds_config = [
        'index' => 0,
        'port' => 6379,
        'host' => '127.0.0.1',
        'pass' => null,
    ];

    // 缓存是否加密
    private $encrypt = false;

    /**
     * 目前支持 文件缓存 redis缓存
     * @param int $type 可选 0:自动设置 1:文件缓存Cache::CACHE_FILE, 2:redis缓存Cache::CACHE_REDIS
     * @param string $cache_file 可选 自定义文件缓存位置
     * @param array $rds_config 可选 自定义redis缓存配置 如: ['index' => 0,'port' => 6379,'host' => '127.0.0.1','pass' => null]
     */
    public function __construct(int $type = 0, string $cache_file = '', array $rds_config = [], $encrypt = false)
    {
        $this->encrypt = $encrypt;
        if ($cache_file) $this->cache_file = $cache_file;
        if (!empty($rds_config)) $this->rds_config = array_merge($this->rds_config, $rds_config);
        // 自动判断当前环境是否为srun4k
        $system_conf_file = '/srun3/etc/system.conf';
        if (is_file($system_conf_file)) $this->in_srun4k = true;

        // 当处于srun4k环境时使用redis缓存, 可手动设置为file缓存
        // 当不是srun4k环境时默认使用文件缓存, 可手动设置为redis缓存
        switch ($type) {
            case self::CACHE_FILE:
                $this->cache_type = $type;
                if ($this->in_srun4k) $this->cache_file = '/srun3/www/srun4-mgr/center/runtime/cache/.srun_cache';
                break;
            case self::CACHE_REDIS:
                $this->cache_type = $type;
                if ($this->in_srun4k) {
                    $system_conf = parse_ini_file($system_conf_file);
                    $this->rds_config['port'] = 16382;
                    $this->rds_config['host'] = $system_conf['user_server'];
                    $this->rds_config['pass'] = $system_conf['redis_password'];
                }
                break;
            case 0:
            default:
                if ($this->in_srun4k) {
                    $this->cache_type = self::CACHE_REDIS;
                    $system_conf = parse_ini_file($system_conf_file);
                    $this->rds_config['port'] = 16382;
                    $this->rds_config['host'] = $system_conf['user_server'];
                    $this->rds_config['pass'] = $system_conf['redis_password'];
                } else {
                    $this->cache_type = self::CACHE_FILE;
                }
        }
    }

    public function set($key, $value, $ttl = 0): bool
    {
        if ($this->encrypt) $value = Func::ED($value);
        switch ($this->cache_type) {
            // todo:
            case self::CACHE_FILE:
                break;
            case self::CACHE_REDIS:
                if ($ttl > 0) {
                    return Func::Rds(...$this->rds_config)->setEx($key, $ttl, $value);
                } elseif ($ttl == 0) {
                    return Func::Rds(...$this->rds_config)->set($key, $value);
                }
        }
        return false;
    }

    public function get($key)
    {
        switch ($this->cache_type) {
            // todo:
            case self::CACHE_FILE:
                break;
            case self::CACHE_REDIS:
                $rs = Func::Rds(...$this->rds_config)->get($key);
                if ($this->encrypt) return Func::ED($rs, 'D');
                return $rs;
        }
        return false;
    }
}