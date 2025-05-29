<?php

namespace srun\src;

use luguohuakai\func\Func;

class Srun implements \srun\base\Srun
{
    protected $srun_north_api_url = 'https://127.0.0.1:8001/';
    private $srun_north_access_token = '';
    private $srun_north_access_token_expire = 7200;
    private $srun_north_access_token_redis_key = 'srun_north_access_token_redis';
    private $use_this_rds = false;
    // 默认使用Cache类管理缓存
    public $cache = true;
    public $cache_file = ''; // 自定义缓存文件 全路径
    private $default_log_path = '/var/log/srun/'; // 如果默认位置没有权限 需要自定义日志文件路径
    private $rds_config = [
        'index' => 0,
        'port' => 6379,
        'host' => '127.0.0.1',
        'pass' => null,
    ];

    public $errors = [];
    /**
     * 北向接口返回的code
     * @var null
     */
    public $north_code = null;

    public function hasError()
    {
        return !empty($this->errors);
    }

    public function firstError()
    {
        if ($this->hasError()) return $this->errors[0];
        return '';
    }

    public function lastError()
    {
        if ($this->hasError()) return $this->errors[count($this->errors) - 1];
        return '';
    }

    public function addError($error)
    {
        $this->errors[] = $error;
    }

    public function __construct($srun_north_api_url = null, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        if ($srun_north_api_url) $this->srun_north_api_url = $srun_north_api_url;
        if ($srun_north_access_token) $this->srun_north_access_token = $srun_north_access_token;
        if ($srun_north_access_token_expire) $this->srun_north_access_token_expire = $srun_north_access_token_expire;
        if ($srun_north_access_token_redis_key) $this->srun_north_access_token_redis_key = $srun_north_access_token_redis_key;

        // 自动判断当前环境是否为srun4k
        $system_conf_file = '/srun3/etc/system.conf';
        if (is_file($system_conf_file)) {
            $this->use_this_rds = true;
            $system_conf = parse_ini_file($system_conf_file);
            $this->rds_config['port'] = 16382;
            $this->rds_config['host'] = $system_conf['user_server'];
            $this->rds_config['pass'] = $system_conf['redis_password'];

            $this->default_log_path = '/srun3/log/srun/';
        }

        if (PHP_OS === 'WINNT') $this->default_log_path = './log/';

        if (!is_dir($this->default_log_path)) @mkdir($this->default_log_path, 0777, true);
    }

    public function setDefaultLogPath($path)
    {
        $this->default_log_path = $path;
    }

    /**
     * 自定义redis缓存配置
     * @param $rds_config
     * @return void
     * @deprecated since 1.0.8
     */
    public function setRdsConfig($rds_config)
    {
        $this->use_this_rds = true;
        $this->rds_config = array_merge($this->rds_config, $rds_config);
    }

    /**
     * 关闭Cache类, 兼容老版本
     * @return void
     */
    public function closeCache()
    {
        $this->cache = false;
    }

    /**
     * 发起curl请求
     * @param $path
     * @param array $data
     * @param string $method
     * @param bool $access_token
     * @param array $header
     * @return string|object 错误时返回字符串|正确时返回json对象
     */
    public function req($path, array $data = [], string $method = 'get', bool $access_token = true, array $header = [])
    {
        $method = strtolower($method);
        $srun_north_api_url = $this->srun_north_api_url;
        if (!$srun_north_api_url) {
            $this->logError('NORTH INTERFACE ERR');
            return 'NORTH INTERFACE ERR';
        }
        $url = "$srun_north_api_url$path";

        if ($access_token === true) {
            $srun_north_access_token = $this->accessToken();
            if (!$srun_north_access_token) {
                $this->logError('NORTH INTERFACE ACCESS_TOKEN ERR');
                return 'NORTH INTERFACE ACCESS_TOKEN ERR';
            }
            if ($method === 'get') $url = "$url?access_token=$srun_north_access_token";
            if ($method === 'post') $data['access_token'] = $srun_north_access_token;
            if ($method === 'delete') $data['access_token'] = $srun_north_access_token;
        }

        $this->logInfo("$method|$url|" . json_encode($data, JSON_UNESCAPED_UNICODE), 'request');
        $rs = Func::$method($url, $data, $header);

        if (!$rs) {
            $this->logError('NORTH INTERFACE REQUEST ERR');
            return 'NORTH INTERFACE REQUEST ERR';
        }
        if (is_object($rs) && isset($rs->_err)) {
            $this->logError('Curl error: ' . $rs->_err);
            return (string)$rs->_err;
        }
        $json = is_object($rs) ? $rs : json_decode($rs);
        $json = is_object($json) ? $json : json_decode($json);
        if (!is_object($json)) {
            $this->logError('RESULT DECODE ERR');
            return 'RESULT DECODE ERR';
        }
        if (isset($json->code)) $this->north_code = $json->code;
        if (isset($json->code) && $json->code == 0) {
            $this->logInfo("$method|$url|" . json_encode($json, JSON_UNESCAPED_UNICODE), 'response');
            return $json;
        }
        if (isset($json->code)) {
            $err_code = (int)$json->code;
            $err_msg = $json->message;
            if (in_array($err_code, array_keys(SrunError::$north))) $err_msg .= '-' . SrunError::$north[$err_code];
            $this->logError('NORTH ERR - ' . json_encode($json, JSON_UNESCAPED_UNICODE));
            $this->addError($err_msg);
            return 'NORTH ERR - ' . $err_msg;
        }
        $this->logError('NORTH INTERFACE UNKNOWN ERR: ', json_encode($rs, JSON_UNESCAPED_UNICODE));
        return 'NORTH INTERFACE UNKNOWN ERR';
    }

    /**
     * 获取北向接口令牌
     * @return mixed|bool|string
     */
    public function accessToken()
    {
        if ($this->srun_north_access_token) return $this->srun_north_access_token;

        // 默认使用Cache类管理缓存
        if ($this->cache) goto cache;

        if ($this->use_this_rds) {
            $access_token = Func::Rds($this->rds_config['index'], $this->rds_config['port'], $this->rds_config['host'], $this->rds_config['pass'])->get($this->srun_north_access_token_redis_key);
            if ($access_token) return $access_token;
            $rs = $this->req('api/v1/auth/get-access-token', [], 'get', false);
            if (isset($rs->data) && isset($rs->data->access_token)) {
                // 缓存令牌
                Func::Rds($this->rds_config['index'], $this->rds_config['port'], $this->rds_config['host'], $this->rds_config['pass'])->setex($this->srun_north_access_token_redis_key, $this->srun_north_access_token_expire, $rs->data->access_token);
                return $rs->data->access_token;
            } else {
                $this->logError('get access_token failed1', __METHOD__);
                return false;
            }
        } else {
            cache:
            $cache = new Cache(0, $this->cache_file);
            $access_token = $cache->get($this->srun_north_access_token_redis_key);
            if ($access_token) return $access_token;
            $rs = $this->req('api/v1/auth/get-access-token', [], 'get', false);
            if (isset($rs->data) && isset($rs->data->access_token)) {
                // 缓存令牌
                $cache->set($this->srun_north_access_token_redis_key, $rs->data->access_token, $this->srun_north_access_token_expire);
                return $rs->data->access_token;
            } else {
                $this->logError('get access_token failed2', __METHOD__);
                return false;
            }
        }
    }

    /**
     * (北向接口)判断账号是否存在
     * @param $user_name
     * @return bool
     */
    public function userExist($user_name): bool
    {
        $rs = $this->req('api/v1/user/search', ['value' => $user_name, 'type' => 1, 'field' => 'user_name']);
        return is_object($rs) && $rs->code === 0;
    }

    /**
     * (北向接口)判断账号是否正确
     * @param $user_name
     * @param $pwd
     * @return bool
     */
    public function userRight($user_name, $pwd): bool
    {
        $rs = $this->req('api/v1/user/validate-users', ['user_name' => $user_name, 'password' => md5($pwd)], 'post');
        return is_object($rs) && $rs->code === 0;
    }


    // 8082上的微信临时放行key(必须核实)也可在服务器文件srun4kauth.xml中ApiAuthSecret字段获得需修改EnableAPIAuth=1然后重启srun3kauth
    // 重启 srun3kauth 程序: /srun3/bin/srun3kauth-ctrl.sh restart
    public $sso_secret = '123456';
    public $sso_auth_url = 'http://127.0.0.1/';

    /**
     * 单点登录请求方法
     * @param $url
     * @param $post_data
     * @return mixed
     */
    public function postSso($url, $post_data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data)); // 模拟表单提交
        // 在尝试连接时等待的秒数
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        // 最大执行时间
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        $header = [
            'Content-Type: application/x-www-form-urlencoded;charset=utf-8',
            'Accept: application/json'
        ];
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $output = curl_exec($ch);
        $_err = curl_error($ch);
        if ($_err) return json_decode(json_encode(['_err' => $_err]));
        curl_close($ch);

        return $output;
    }

    /**
     * 单点认证 上线
     * @param $auth_addr
     * @param $secret
     * @param $user_name
     * @param $ip
     * @param $ac_id
     * @return string
     */
    public function ssoLogin($auth_addr, $secret, $user_name, $ip, $ac_id = null): string
    {
        $time = time();
        $data = [
            'action' => 'login',
            'api_auth' => 1,
            'username' => urlencode($user_name),
            'ip' => $ip,
            'type' => 1001,
            'client_type' => 6,
            'n' => 100,
            'drop' => 0,
            'pop' => 0,
            'time' => $time,
            'password' => md5($secret . $time . $user_name . $ip . $time . $secret),
        ];
        if ($ac_id !== null) $data['ac_id'] = $ac_id;
        $rs = $this->postSso($auth_addr, $data);
        $json = is_object($rs) ? $rs : json_decode($rs);
        if ($json && !isset($json->_err)) {
            // 判断成功还是失败
            $auth_msg = $this->getAuthMsg($json);
            if (isset($auth_msg['success'])) {
                $this->ssoLog('认证成功,异步通知成功');
                return Func::success($json);
            }
            if (isset($auth_msg['fail'])) return Func::fail($json, $auth_msg['fail']);
            return Func::fail($json, '未知错误');
        } else {
            return Func::fail($json, '请求失败');
        }
    }

    /**
     * 单点认证 下线
     * @param $auth_addr
     * @param $secret
     * @param $user_name
     * @param $ip
     * @param $ac_id
     * @return string
     */
    public function ssoDrop($auth_addr, $secret, $user_name, $ip, $ac_id = null): string
    {
        $time = time();
        $data = [
            'action' => 'logout',
            'api_auth' => 1,
            'username' => urlencode($user_name),
            'ip' => $ip,
            'type' => 1001,
            'n' => 1,
            'time' => $time,
            'password' => md5($secret . $time . $user_name . $ip . $time . $secret),
        ];
        if ($ac_id !== null) $data['ac_id'] = $ac_id;
        $rs = $this->postSso($auth_addr, $data);
        $json = is_object($rs) ? $rs : json_decode($rs);
        if ($json && !isset($json->_err)) {
            // 判断成功还是失败
            $auth_msg = $this->getAuthMsg($json);
            if (isset($auth_msg['success'])) {
                $this->ssoLog('退出成功');
                return Func::success($json);
            }
            if (isset($auth_msg['fail'])) return Func::fail($json, $auth_msg['fail']);
            return Func::fail($json, '未知错误');
        } else {
            return Func::fail($json, '请求失败');
        }
    }

    /**
     * 单点认证 发起认证请求/下线
     * @param string $username 上网账号
     * @param string $ip 上网IP
     * @param null $ac_id
     * @param null $drop null:认证 1:下线
     * @return string
     */
    public function sso(string $username, string $ip, $ac_id = null, $drop = null): string
    {
        $time = time();
        // 注意: 需要先在params.php配置
        $secret = $this->sso_secret;
        $data = [
            'action' => 'login',
            'api_auth' => 1,
            'username' => $username,
            'ip' => $ip,
            'type' => 1001,
            'n' => 100,
            'drop' => 0,
            'pop' => 0,
            'time' => $time,
            'password' => md5($secret . $time . $username . $ip . $time . $secret),
        ];
        if ($ac_id !== null) $data['ac_id'] = $ac_id;
        if ($drop !== null) {
            $data['action'] = 'logout';
            unset($data['drop']);
            unset($data['pop']);
        }
        // 注意: 需要先在params.php配置
        $rs = $this->postSso($this->sso_auth_url . 'cgi-bin/srun_portal', $data);
        $res = $rs;
        if (!$rs) return Func::fail($data, '请求失败');
        if (isset($rs->_err)) return Func::fail($data, $rs->_err);
        $rs = is_object($rs) ? $rs : json_decode($rs);
        if (!is_object($rs)) return Func::fail($res);
        // 判断成功还是失败
        $auth_msg = $this->getAuthMsg($rs);
        if (isset($auth_msg['success'])) {
            $this->ssoLog("drop={$drop}操作成功");
            return Func::success($rs);
        }
        if (isset($auth_msg['fail'])) return Func::fail($rs, $auth_msg['fail']);
        return Func::fail($rs, '未知错误');

//        if ($rs->error === 'ok') {
//            return success($rs);
//        } else {
//            return fail($rs, $rs->error . $rs->error_msg);
//        }
    }

    /**
     * 获取认证结果
     * @param $rs
     * @return array 成功: ['success' => 'xxx'] 失败: ['fail' => 'xxx']
     */
    public function getAuthMsg($rs): array
    {
        $fail = true;
        $msg = '失败';
        $rs = is_object($rs) ? $rs : json_decode($rs);
        $rs = is_object($rs) ? $rs : json_decode($rs);
        if (!is_object($rs)) {
            $msg = 'json解析失败:' . json_encode($rs, JSON_UNESCAPED_UNICODE);
            goto end;
        }
        if (isset($rs->ecode) && $rs->ecode) {
            if (in_array($rs->ecode, array_keys(SrunError::$srun_success))) {
                $msg = SrunError::$srun_success[$rs->ecode];
                $fail = false;
                goto end;
            }
            if (in_array($rs->ecode, array_keys(SrunError::$srun_error))) {
                $msg = SrunError::$srun_error[$rs->ecode];
                goto end;
            }
        }

        // 先解析 ploy_msg
        if (isset($rs->ploy_msg) && $rs->ploy_msg) {
            $err = explode(':', $rs->ploy_msg);
            if (count($err) !== 2) {
                $msg = $rs->ploy_msg;
                if (in_array($msg, array_keys(SrunError::$srun_error))) {
                    $msg = SrunError::$srun_error[$msg];
                    goto end;
                }
                if (in_array($msg, array_keys(SrunError::$srun_success))) {
                    $fail = false;
                    $msg = SrunError::$srun_success[$msg];
                    goto end;
                }
            } else {
                $err_v = $err[1] ?? '';
                $err_k = $err[0] ?? '';
                if (in_array($err_k, array_keys(SrunError::$srun_success))) {
                    $fail = false;
                    $msg = SrunError::$srun_success[$err_k];
                    goto end;
                } elseif (in_array($err_k, array_keys(SrunError::$srun_error))) {
                    $msg = SrunError::$srun_error[$err_k];
                } else {
                    $msg = $err_v ?: $err_k;
                }
                goto end;
            }
        }

        // 再解析 suc_msg
        if (isset($rs->suc_msg) && $rs->suc_msg) {
            if (in_array($rs->suc_msg, array_keys(SrunError::$srun_success))) {
                $fail = false;
                $msg = SrunError::$srun_success[$rs->suc_msg];
                goto end;
            } elseif (in_array($rs->suc_msg, array_keys(SrunError::$srun_error))) {
                $msg = SrunError::$srun_error[$rs->suc_msg];
            } else {
                $msg = $rs->suc_msg;
            }
            goto next;
        }
        // 再解析 error_msg
        next:
        if (isset($rs->error_msg) && $rs->error_msg) {
            $err = explode(':', $rs->error_msg);
            if (count($err) !== 2) {
                $msg = $rs->error_msg;
                if (in_array($msg, array_keys(SrunError::$srun_error))) {
                    $msg = SrunError::$srun_error[$msg];
                    goto end;
                }
                if (in_array($msg, array_keys(SrunError::$srun_success))) {
                    $fail = false;
                    $msg = SrunError::$srun_success[$msg];
                    goto end;
                }
            } else {
                $err_k = $err[0] ?? '';
                $err_v = $err[1] ?? '';
                if (in_array($err_k, array_keys(SrunError::$srun_success))) {
                    $fail = false;
                    $msg = SrunError::$srun_success[$err_k];
                    goto end;
                } elseif (in_array($err_k, array_keys(SrunError::$srun_error))) {
                    $msg = SrunError::$srun_error[$err_k];
                } else {
                    $msg = $err_v ?: $err_k;
                }
                goto end;
            }
        }
        if (isset($rs->res) && $rs->res) {
            if (in_array($rs->res, array_keys(SrunError::$srun_success))) {
                $fail = false;
                $msg = SrunError::$srun_success[$rs->res];
                goto end;
            } elseif (in_array($rs->res, array_keys(SrunError::$srun_error))) {
                $msg = SrunError::$srun_error[$rs->res];
                goto end;
            } else {
                $msg = $rs->res;
            }
        }
        if (isset($rs->error) && $rs->error) {
            if (in_array($rs->error, array_keys(SrunError::$srun_success))) {
                $fail = false;
                $msg = SrunError::$srun_success[$rs->error];
                goto end;
            } elseif (in_array($rs->error, array_keys(SrunError::$srun_error))) {
                $msg = SrunError::$srun_error[$rs->error];
            } else {
                $msg = $rs->error;
            }
        }

        end:
        if ($fail) {
            $re['fail'] = $msg;
        } else {
            $re['success'] = $msg;
        }

        return $re;
    }

    /**
     * sso关键操作日志
     * @param string|array|object $msg
     * @param string $level info/error/warn...
     * @return void
     */
    public function ssoLog($msg, string $level = 'info')
    {
        $this->srunLog($msg, $level, 'sso', 'sso');
    }


    /**
     * 一般日志
     * @param string|array|object $msg
     * @param string $category app/sso/request/response...
     * @return void
     */
    public function logInfo($msg, string $category = 'app')
    {
        $this->srunLog($msg, 'info', $category);
    }

    /**
     * 错误日志
     * @param string|array|object $msg
     * @param string $category app/sso/request/response...
     * @return void
     */
    public function logError($msg, string $category = 'app')
    {
        $this->addError($msg);
        $this->srunLog($msg, 'error', $category);
    }

    /**
     * 日志
     * @param string|array|object $msg
     * @param string $level info/error/warn...
     * @param string $category app/sso/request/response...
     * @return void
     */
    private function srunLog($msg, string $level = 'info', string $category = 'app', $type = 'srun')
    {
        if (is_array($msg) || is_object($msg)) $msg = json_encode($msg, JSON_UNESCAPED_UNICODE);
        $msg = mb_substr($msg, 0, 1000);
        $msg = date('Y-m-d H:i:s') . " [$level] [$category] $msg" . PHP_EOL;
        @file_put_contents($this->default_log_path . $type . date('Ym') . '.log', $msg, FILE_APPEND);
    }
}