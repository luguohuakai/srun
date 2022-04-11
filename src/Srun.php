<?php

namespace src;

class Srun implements SrunBase
{
    private $srun_north_api_url = '';
    private $srun_north_access_token = '';
    private $srun_north_access_token_expire = 7200;
    private $srun_north_access_token_redis_key = 'srun_north_access_token_redis';

    public function __construct($srun_north_api_url, $srun_north_access_token = null, $srun_north_access_token_expire = null, $srun_north_access_token_redis_key = null)
    {
        $this->srun_north_api_url = $srun_north_api_url;
        if (!$srun_north_access_token) $this->srun_north_access_token = $srun_north_access_token;
        if (!$srun_north_access_token_expire) $this->srun_north_access_token_expire = $srun_north_access_token_expire;
        if (!$srun_north_access_token_redis_key) $this->srun_north_access_token_redis_key = $srun_north_access_token_redis_key;
    }

    /**
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
        if (!$srun_north_api_url) return 'NORTH INTERFACE ERR';
        $url = "$srun_north_api_url$path";

        if ($access_token === true) {
            $srun_north_access_token = $this->accessToken();
            if (!$srun_north_access_token) return 'NORTH INTERFACE ACCESS_TOKEN ERR';
            if ($method === 'get') $url = "$url?access_token=$srun_north_access_token";
            if ($method === 'post') $data['access_token'] = $srun_north_access_token;
            if ($method === 'delete') $data['access_token'] = $srun_north_access_token;
        }

        $rs = Func::$method($url, $data, $header);

        if (!$rs) return 'NORTH INTERFACE REQUEST ERR';
        if (is_object($rs) && isset($rs->_err)) return (string)$rs->_err;
        $json = is_object($rs) ? $rs : json_decode($rs);
        $json = is_object($json) ? $json : json_decode($json);
        if (!is_object($json)) return 'RESULT DECODE ERR';
        if (isset($json->code) && $json->code == 0) return $json;
        if (isset($json->code)) {
            $err_code = (int)$json->code;
            $err_msg = $json->message;
            if (in_array($err_code, array_keys(SrunError::$north))) $err_msg .= '-' . SrunError::$north[$err_code];
            return 'NORTH ERR - ' . $err_msg;
        }
        return 'NORTH INTERFACE UNKNOWN ERR';
    }

    /**
     * 电子钱包充值接口
     * @param $user_name
     * @param $amount
     * @param $order_no
     * @return object|string
     */
    public function financialRechargeWallet($user_name, $amount, $order_no)
    {
        $data = [
            'user_name' => $user_name,
            'pay_type_id' => 1,
            'pay_num' => $amount,
            'order_no' => $order_no,
        ];
        return $this->req('api/v1/financial/recharge-wallet', $data, 'post');
    }

    /**
     * 返回电子钱包余额接口
     * @param $user_name
     * @return object|string
     */
    public function userBalance($user_name)
    {
        return $this->req('api/v1/user/balance', ['user_name' => $user_name]);
    }

    /**
     * (北向接口)查询账号详情
     * @param $account
     * @return object|string
     */
    public function userView($account)
    {
        return $this->req('api/v1/user/view', ['user_name' => $account]);
    }

    // (北向接口)添加用户组
    public function addGroup($group_name, $parent_name = '/')
    {
        $data['name'] = mb_substr($group_name, 0, 100);
        $data['parent_name'] = $parent_name;
        return $this->req('api/v1/groups', $data, 'post');
    }

    /**
     * (北向接口)判断账号是否存在
     * @param $user_name
     * @return bool
     */
    public function userExist($user_name): bool
    {
        $rs = $this->req('api/v1/user/search', ['value' => $user_name, 'type' => 1]);
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
        $rs = $this->req('api/v1/user/validate-users', ['user_name' => $user_name, 'password' => $pwd], 'post');
        return is_object($rs) && $rs->code === 0;
    }

    /**
     * (北向接口)销户
     * @param $user_name
     * @return object|string
     */
    public function userDelete($user_name)
    {
        return $this->req('api/v1/user/delete', ['user_name' => $user_name], 'delete');
    }

    // 查询已订购的产品/套餐接口
    public function usersPackages($user_name)
    {
        return $this->req('api/v1/package/users-packages', ['user_name' => $user_name]);
    }

    /**
     * (北向接口)创建账号
     * @param $user_name
     * @param string $user_real_name
     * @param string $user_password
     * @param int $products_id
     * @param int $group_id
     * @param string $mgr_name_create
     * @param array $other
     * @return object|string
     */
    public function addUser($user_name, string $user_real_name = '', string $user_password = '123456', int $products_id = 1, int $group_id = 1, string $mgr_name_create = 'SrunMeeting', array $other = [])
    {
        $data = [
            'user_name' => $user_name,
            'user_real_name' => $user_real_name,
            'user_password' => $user_password,
            'group_id' => $group_id,
            'products_id' => $products_id,
            'mgr_name_create' => $mgr_name_create
        ];
        if (!empty($other)) $data = array_merge($data, $other);
        return $this->req('api/v1/users', $data, 'post');
    }

    /**
     * (北向接口)创建账号 中国矿业大学定制北向接口
     * @param $user_name
     * @param string $user_real_name
     * @param string $user_password
     * @param int $products_id
     * @param int $group_id
     * @param string $mgr_name_create
     * @param array $other
     * @return object|string
     */
    public function addUserCumt($user_name, string $user_real_name = '', string $user_password = '123456', int $products_id = 1, int $group_id = 1, string $mgr_name_create = 'SrunMeeting', array $other = [])
    {
        $data = [
            'user_name' => $user_name,
            'user_real_name' => $user_real_name,
            'user_password' => $user_password,
            'group_id' => $group_id,
            'products_id' => $products_id,
            'mgr_name_create' => $mgr_name_create,
            'mac_auth' => 1
        ];
        if (!empty($other)) $data = array_merge($data, $other);
        if (!$data['group_id']) $data['group_id'] = '2';
        if (!$data['sex']) $data['sex'] = '0';
        return $this->req('api/cumt/user/sync', $data, 'post');
    }

    /**
     * 获取北向接口令牌
     * @return mixed|bool|string
     */
    public function accessToken()
    {
        if ($this->srun_north_access_token) return $this->srun_north_access_token;
        $access_token = Func::Rds()->get($this->srun_north_access_token_redis_key);
        if ($access_token) return $access_token;
        $rs = $this->req('api/v1/auth/get-access-token', [], 'get', false);
        if (isset($rs->data) && isset($rs->data->access_token)) {
            // 缓存令牌
            Func::Rds()->setex($this->srun_north_access_token_redis_key, $this->srun_north_access_token_expire, $rs->data->access_token);
            return $rs->data->access_token;
        } else {
            return false;
        }
    }

    /**
     * @param $url
     * @param $post_data
     * @return mixed
     */
    function postSso($url, $post_data)
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

    // 单点认证 上线
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
                $this->keyHandleLog('认证成功,异步通知成功');
                return Func::success($json);
            }
            if (isset($auth_msg['fail'])) return Func::fail($json, $auth_msg['fail']);
            return Func::fail($json, '未知错误');
        } else {
            return Func::fail($json, '请求失败');
        }
    }

    // 单点认证 下线
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
                $this->keyHandleLog('退出成功');
                return Func::success($json);
            }
            if (isset($auth_msg['fail'])) return Func::fail($json, $auth_msg['fail']);
            return Func::fail($json, '未知错误');
        } else {
            return Func::fail($json, '请求失败');
        }
    }


    public $sso_secret;
    public $sso_auth_url;

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
        if (!$rs) return Func::fail($data, '请求失败');
        if (isset($rs->_err)) return Func::fail($data, $rs->_err);
        $rs = is_object($rs) ? $rs : json_decode($rs);
        // 判断成功还是失败
        $auth_msg = $this->getAuthMsg($rs);
        if (isset($auth_msg['success'])) {
            $this->keyHandleLog("drop={$drop}操作成功");
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
            $msg = 'json解析失败';
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
     * 关键操作日志
     * @param $msg
     * @param bool $next 是否前置换行
     * @param string $file 保存的文件路径及名称
     */
    public function keyHandleLog($msg, bool $next = false, string $file = '')
    {
        if ($file === '') $file = '/temp/key_handle_ali_' . date('Y-m', time()) . '.log';
        $log = Func::dt() . " $msg\r\n";
        if ($next !== false) $log = "\r\n" . $log;
        file_put_contents($file, $log, FILE_APPEND);
    }

    // 获取当前在线终端数量接口
    public function getOnlineTotal()
    {
        return $this->req('api/v1/base/get-online-total');
    }

    // 查询在线设备接口
    public function onlineEquipment($user_name = null, $user_ip = null, $user_mac = null)
    {
        if ($user_mac !== null) $data = ['user_mac' => $user_mac];
        if ($user_ip !== null) $data = ['user_ip' => $user_ip];
        if ($user_name !== null) $data = ['user_name' => $user_name];
        if (!isset($data)) return '参数不正确';
        return $this->req('api/v1/base/online-equipment', $data);
    }

    /**
     * 开启/暂停接口
     * 0-正常
     * 1-禁用
     * 2-停机保号
     * 3-暂停
     * 4-未开通
     * 场景1:微信访客, user_available = 0
     * @param $user_name
     * @param int $user_available
     * @return object|string
     */
    public function userStatusControl($user_name, int $user_available = 1)
    {
        return $this->req('api/v1/user/user-status-control', [
            'user_name' => $user_name,
            'user_available' => $user_available
        ], 'post');
    }

    // 修改最大在线数接口
    public function maxOnlineNum($user_name, $num)
    {
        return $this->req('api/v1/user/max-online-num', [
            'user_name' => $user_name,
            'max_online_num' => $num
        ], 'post');
    }
}