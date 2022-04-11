<?php

namespace srun\src;

use DateTime;
use Exception;
use Redis;

class Func implements FuncBase
{
    /**
     * 调试输出神器
     * @param string $var
     * @param bool $stop 是否截断(die)
     * @param bool $as_array
     */
    public static function dd(string $var = '', bool $stop = true, bool $as_array = false)
    {
        if (is_array($var) || is_object($var)) {
            echo '<pre>';
            if ($as_array) {
                var_export($var);
            } else {
                print_r($var);
            }
            if ($stop) {
                die('</pre>');
            } else {
                echo '</pre>';
            }
        } else {
            var_dump($var);
            if ($stop) die;
        }
    }

    /**
     * 打印并换行
     * @param mixed $var
     * @return void
     */
    public static function ww($var = '')
    {
        if ($var === false) echo 'bool false' . "\r\n";
        if ($var === null) echo 'null' . "\r\n";
        if (is_string($var) and trim($var) === '') echo 'string ""' . "\r\n";
        if (is_string($var)) {
            echo $var . "\r\n";
        } else {
            echo '<pre>';
            print_r($var);
            echo '</pre>';
            echo "\r\n";
        }
    }

    /**
     * @param string $filename 日志存放位置 默认 /tmp/dm-log/
     * @param mixed $data 日志内容
     * @param string $format 日志格式 human-readable:默认 json:JSON格式化 serialize:序列化
     * @param int $flags 默认:FILE_APPEND 追加
     * @param string $by 默认:month 日志文件按月生成
     */
    public static function logs(string $filename, $data, string $format = 'human-readable', int $flags = FILE_APPEND, string $by = 'month')
    {
        if (strpos($filename, '/') === false) {
            switch (true) {
                case is_dir('/tmp/'):
                    $dir = '/tmp/dm-log/';
                    if (!is_dir($dir))
                        mkdir($dir);
                    break;
                case is_dir('/srun3/log/'):
                    $dir = '/srun3/log/dm-log/';
                    if (!is_dir($dir))
                        mkdir($dir);
                    break;
                case is_dir('/srun3/www/srun_mq/backend/runtime/logs/'):
                    $dir = '/srun3/www/srun_mq/backend/runtime/logs/dm-log/';
                    if (!is_dir($dir))
                        mkdir($dir);
                    break;
                case is_dir('/srun3/www/srun4-mgr/center/runtime/logs/'):
                    $dir = '/srun3/www/srun4-mgr/center/runtime/logs/dm-log/';
                    if (!is_dir($dir))
                        mkdir($dir);
                    break;
                default:
                    break;
            }
            if (isset($dir)) $filename = $dir . $filename;
        }
        $time = date('Y-m-d H:i:s', time());
        if ($by === 'month') $filename .= '_' . date('Ym', time());
        if ($by === 'day') $filename .= '_' . date('Ymd', time());
        if ($by === 'year') $filename .= '_' . date('Y', time());
        if ($by === 'hour') $filename .= '_' . date('YmdH', time());
        if ($by === 'minute') $filename .= '_' . date('YmdHi', time());
        if ($format === 'json') $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        if ($format === 'serialize') $data = serialize($data);
        $filename .= '.log';
        if (!is_file($filename)) file_put_contents($filename, '', FILE_APPEND);
        chmod($filename, 0777);
        file_put_contents($filename, $time . ' ' . print_r($data, true) . "\r\n", $flags);
    }

    public static function wwLogs($filename, $data, $format = 'human-readable', $flags = FILE_APPEND, $by = 'month')
    {
        self::ww($data);
        self::logs($filename, $data, $format, $flags, $by);
    }

    /**
     * 网页快速弹出调试
     * @param $var
     */
    public static function alert($var)
    {
        $str = (string)json_encode($var);
        echo "<script type='text/javascript'>alert('$str');</script>";
    }

    /**
     * 快速连接redis
     * @param int $index
     * @param int $port
     * @param string $host
     * @param string|null $pass
     * @return Redis
     */
    public static function Rds(int $index = 0, int $port = 6379, string $host = 'localhost', string $pass = null): Redis
    {
        $rds = new Redis();
        $rds->connect($host, $port);
        if ($pass !== null) $rds->auth($pass);
        $rds->select($index);

        return $rds;
    }

    /**
     * @param $url
     * @param array $get_data
     * @param array $header
     * @return mixed
     */
    public static function get($url, array $get_data = [], array $header = [])
    {
        if (!empty($get_data)) $url = self::joinParams($url, $get_data);
        // 初始化
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// SSL证书认证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);// SSL证书认证
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        // 设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        // 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $_err = curl_error($ch);
        if ($_err) return json_decode(json_encode(['_err' => $_err]));
        // 释放curl句柄
        curl_close($ch);

        return $output;
    }

    public static function joinParams($path, $params)
    {
        $url = $path;
        $parse_rs = parse_url($url);
        $query = $parse_rs['query'] ?? '';
        if (count($params) > 0) {
            $url = $query ? $url . '&' : $url . '?';
            foreach ($params as $key => $value) {
                $url = $url . $key . '=' . $value . '&';
            }
            $length = mb_strlen($url);
            if ($url[$length - 1] == '&') {
                $url = substr($url, 0, $length - 1);
            }
        }
        return $url;
    }

    /**
     * @param $url
     * @param $post_data
     * @param array $header
     * @return mixed
     */
    public static function post($url, $post_data, array $header = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// SSL证书认证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);// SSL证书认证
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // 模拟表单提交
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        // json方式提交
//        $_header = array("Content-Type: application/json; charset=utf-8", "Content-Length:" . strlen(json_encode($post_data)));
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $_header);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        if (!empty($header)) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        $_err = curl_error($ch);
        if ($_err) return json_decode(json_encode(['_err' => $_err]));
        curl_close($ch);

        return $output;
    }

    /**
     * @param $url
     * @param array $post_data
     * @return mixed
     */
    public static function delete($url, array $post_data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data)); // 模拟表单提交
        // json方式提交
        $header = array("Content-Type: application/json; charset=utf-8", "Content-Length:" . strlen(json_encode($post_data)));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        if (!empty($header)) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        $_err = curl_error($ch);
        if ($_err) return json_decode(json_encode(['_err' => $_err]));
        curl_close($ch);
        return $output;
    }

    /**
     * @param false $data 返回数据
     * @param string $msg
     * @param int $status
     * @param int $code
     * @return false|string
     */
    public static function formatReturnData2Json(bool $data = false, string $msg = '成功', int $status = 1, int $code = 200)
    {
        $re['code'] = $code;
        $re['message'] = $msg;
        $re['status'] = $status;
        if ($data !== false) {
            if (is_array($data) && isset($data['extra'])) {
                $re['extra'] = $data['extra'];
            }
            if (is_array($data) && isset($data['data'])) {
                $re['data'] = $data['data'];
            } else {
                $re['data'] = $data;
            }
        }
        // 支持跨域
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST,PUT,GET,DELETE,OPTIONS',
            'Access-Control-Allow-Headers' => 'ApiAuth, token, User-Agent, Keep-Alive, Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With',
            'Access-Control-Allow-Credentials' => 'true'
        ];
        $headers['Content-Type'] = 'application/json; charset=utf-8';
        $headers_str = '';
        foreach ($headers as $k => $header) {
            $headers_str .= $k . ': ' . $header . '; ';
        }
        $headers_str = substr($headers_str, 0, -2);
        header($headers_str);
        return json_encode($re, JSON_UNESCAPED_UNICODE);
    }

    /**
     * json返回 成功
     * @param bool|mixed $data false代表不返回data字段
     * @param string $msg
     * @param int $status 1:成功 0:失败
     * @param int $code 大于100时为http状态码
     * @return string json
     */
    public static function success($data = false, string $msg = '成功', int $status = 1, int $code = 200): string
    {
        return self::formatReturnData2Json($data, $msg, $status, $code);
    }

    /**
     * json返回 失败
     * @param bool|mixed $data false代表不返回data字段
     * @param string $msg
     * @param int $status
     * @param int $code 大于100时为http状态码
     * @return string json
     */
    public static function fail($data = false, string $msg = '失败', int $status = 0, int $code = 200): string
    {
        return self::formatReturnData2Json($data, $msg, $status, $code);
    }

    public static function exitSuccess($data, $msg = '成功')
    {
        header('Content-Type:application/json; charset=utf-8');
        exit(self::success($data, $msg));
    }

    public static function exitFail($data = '', $msg = '失败')
    {
        header('Content-Type:application/json; charset=utf-8');
        exit(self::fail($data, $msg));
    }

    /**
     * 分页函数
     * @param int $count 总条目数
     * @param int $page 当前页码
     * @param int $size 当前没有条目数
     * @return object mixed
     */
    public static function page(int $count, int $page, int $size)
    {
        return json_decode(json_encode([
            'page' => $page,
            'size' => $size,
            'total_pages' => ceil($count / $size),
            'total_items' => $count,
            'limit' => $size,
            'offset' => $size * ($page - 1),
        ]));
    }

    /**
     * @param string $string 需要加密解密的字符串
     * @param string $operation 判断是加密还是解密，E表示加密，D表示解密
     * @param string $key 密匙
     * @return array|false|string|string[]
     */
    public static function ED(string $string = '', string $operation = 'E', string $key = 'www.srun.com')
    {
        $key = md5($key);
        $key_length = strlen($key);
        $string = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'D') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            return str_replace('=', '', base64_encode($result));
        }
    }

    /**
     * @param $data
     * [
     *     ['时间' => '2020-4', '人数' => 35, '天数' => 98],
     *     ['时间' => '2020-5', '人数' => 44, '天数' => 66],
     *     ['时间' => '2020-6', '人数' => 55, '天数' => 45],
     *     ['时间' => '2020-7', '人数' => 77, '天数' => 78],
     * ]
     * @param string $filename
     */
    public static function exportAsCsv($data, string $filename = '')
    {
        if (!$filename) $filename = date('YmdHis') . '.csv';
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        $str = '';
        $keys = array_keys($data[0]);
        for ($i = 0; $i < count($keys); $i++) {
            if ($i != count($keys) - 1) {
                $str .= $keys[$i] . ',';
            } else {
                $str .= $keys[$i] . "\r\n";
            }
        }
        foreach ($data as $vv) {
            $k = 0;
            foreach ($vv as $vvv) {
                if ($k != count($vv) - 1) {
                    $str .= $vvv . ',';
                } else {
                    $str .= $vvv . "\r\n";
                }
                $k++;
            }
        }
        $str = iconv('utf-8', 'gb2312', $str);
        exit($str);
    }

    /**
     * 获取客户端IP地址 摘自TP框架
     * @param integer $type 返回类型 0:返回IP地址 1:返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    public static function getRealClientIp(int $type = 0, bool $adv = true)
    {
        $type = $type ? 1 : 0;
        static $ip = null;
        if (null !== $ip) {
            return $ip[$type];
        }
        if ($adv) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown', $arr);
                if (false !== $pos) {
                    unset($arr[$pos]);
                }
                $ip = trim(current($arr));
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? [$ip, $long] : ['0.0.0.0', 0];

        return $ip[$type];
    }

    /**
     * 生成唯一ID
     * @param string $prefix
     * @param string $uid
     * @param string $suffix
     * @return string
     */
    public static function generateUniqueId(string $prefix = 'mg_', string $uid = '', string $suffix = ''): string
    {
        return
            uniqid($prefix . $uid . '_', true)
            . '_' . mt_rand(1, 999999999)
            . $suffix;
    }

    /**
     * 将图片转化为Base64
     * @param $image_file
     * @return string
     */
    public static function base64EncodeImage($image_file): string
    {
        $image_info = getimagesize($image_file);
        $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
        return 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
    }

    /**
     * 把传入的经纬度转换为位置 腾讯地图api 取第一个位置
     * @param $lat
     * @param $lng
     * @return string
     */
    public static function latLngToAddress($lat, $lng): string
    {
        // 不要频繁调用腾讯的接口
        sleep(1);
        $url = 'https://apis.map.qq.com/ws/geocoder/v1/';
        $data['location'] = $lat . ',' . $lng;
        $data['key'] = 'SNCBZ-IAIKX-VD74W-ZBGFH-DBNSQ-UXBE2';

        $rs = self::get($url, $data);
        $address = '';
        $rs = json_decode($rs, true);
        if ($rs['status'] === 0) {
            $address = $rs['result']['address'];
        }

        return $address;
    }

    /**
     * @param $size
     * @param bool $chinese 是否汉化
     * @return string
     */
    public static function formatDistance($size, bool $chinese = false): string
    {
        if ($chinese) {
            $units = ['里', '公里'];
        } else {
            $units = ['m', 'km'];
        }
        for ($i = 0; $size >= 1000 && $i < 1; $i++) {
            $size /= 1000;
        }

        return round($size, 2) . $units[$i];
    }

    public static function isEmail($input): bool
    {
        return (bool)filter_var($input, FILTER_VALIDATE_EMAIL);
    }

    public static function isIp($input): bool
    {
        return (bool)filter_var($input, FILTER_VALIDATE_IP);
    }

    public static function isIpv4($input): bool
    {
        return (bool)filter_var($input, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }

    public static function isIpv6($input): bool
    {
        return (bool)filter_var($input, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }

    public static function isMac($input): bool
    {
        return (bool)filter_var($input, FILTER_VALIDATE_MAC);
    }

    public static function isDomain($input): bool
    {
        return (bool)filter_var($input, FILTER_VALIDATE_DOMAIN);
    }

    public static function isUrl($input): bool
    {
        return (bool)filter_var($input, FILTER_VALIDATE_URL);
    }

    public static function isMobilePhone($input): bool
    {
        return (bool)preg_match('/^1\d{10}$/', $input);
    }

    /**
     * 存储容量转化 B - KB - MB - GB - TB - PB - ...
     * @param int $size B
     * @param int $dec 保留小数位数
     * @return string
     */
    public static function dataSizeFormat(int $size = 0, int $dec = 2)
    {
        if (!is_numeric($size) || $size < 0) return false;
        $unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB', 'BB', 'NB', 'DB', 'CB', 'XB'];
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }
        $result['size'] = round($size, $dec);
        $result['unit'] = $unit[$pos] ?? '--';
        return $result['size'] . $result['unit'];
    }

    /**
     * // 时间函数封装
     * // 举例
     * magicTime('-30_days_begin') 30天前的凌晨
     * magicTime('-30_days_end') 30天前的午夜
     * magicTime('-30_months_begin') 30月前的凌晨
     * magicTime('-30_months_end') 30月前的午夜
     * 'yesterday_begin', 'yesterday_end', 'today_begin',
     * 'now', 'today_end', 'tomorrow_begin',
     * 'tomorrow_end', 'this_week_begin', 'this_week_end',
     * 'this_month_begin', 'this_month_end', 'this_year_begin',
     * 'this_year_end',
     * @param string $what 要转换的时间字符串
     * @return bool|int
     * @throws Exception
     */
    public static function magicTime(string $what)
    {
        $arr = explode('_', $what);
        if (is_numeric($arr[0])) {
            if ($arr[1] === 'days') {
                switch ($arr[2]) {
                    case 'begin':
                        return strtotime(date('Y-m-d', strtotime($arr[0] . ' days')));
                    case 'end':
                        return strtotime(date('Y-m-d', strtotime($arr[0] + 1 . ' days'))) - 1;
                    default:
                        return false;
                }
            } elseif ($arr[0] === 'months') {
                switch ($arr[2]) {
                    case 'begin':
                        return strtotime(date('Y-m', strtotime($arr[0] . ' months')));
                    case 'end':
                        return strtotime(date('Y-m', strtotime($arr[0] + 1 . ' months'))) - 1;
                    default:
                        return false;
                }
            }
        }
        $whats = [
            'yesterday_begin', 'yesterday_end', 'today_begin',
            'now', 'today_end', 'tomorrow_begin',
            'tomorrow_end', 'this_week_begin', 'this_week_end',
            'this_month_begin', 'this_month_end', 'this_year_begin',
            'this_year_end',
        ];
        if (!in_array($what, $whats)) return false;
        switch ($what) {
            case 'tomorrow_end':
                return strtotime(date('Ymd', strtotime('+2 days'))) - 1;
            case 'tomorrow_begin':
                return strtotime(date('Ymd', strtotime('+1 day')));
            case 'today_end':
                return strtotime(date('Ymd', strtotime('+1 day'))) - 1;
            case 'now':
                return time();
            case 'today_begin':
                return strtotime(date('Ymd'));
            case 'yesterday_end':
                return strtotime(date('Ymd')) - 1;
            case 'yesterday_begin':
                return strtotime(date('Ymd', strtotime('-1 day')));
            case 'this_week_begin':
                return strtotime((new DateTime)->modify('this week')->format('Ymd'));
            case 'this_week_end':
                return strtotime((new DateTime)->modify('this week + 7 days')->format('Ymd')) - 1;
            case 'this_month_begin':
                return strtotime(date('Ym01'));
            case 'this_month_end':
                $m = date('m');
                $y = date('Y');
                if ($m == 12) {
                    $next_m = 1;
                    $next_y = $y + 1;
                } else {
                    $next_m = $m + 1;
                    $next_y = $y;
                }
                return strtotime($next_y . $next_m . '01') - 1;
            case 'this_year_begin':
                return strtotime(date('Y0101'));
            case 'this_year_end':
                return strtotime(date(date('Y') + 1 . '0101')) - 1;
            default:
                return false;
        }
    }

    /**
     * json解析key不含双引号的字符串
     * @param $str
     * @param bool $mode
     * @return mixed
     */
    public static function jsonDecodePlus($str, bool $mode = false)
    {
        if (preg_match('/\w:/', $str)) {
            $str = preg_replace('/(\w+):/is', '"$1":', $str);
        }
        return json_decode($str, $mode);
    }

    /**
     * @param array $items 形如: [['id'=>1, 'pid'=>0, ...], ...]
     * @return array|mixed
     */
    public static function tree(array $items)
    {
        foreach ($items as $item)
            $items[$item['pid']]['son'][$item['id']] = &$items[$item['id']];
        return $items[0]['son'] ?? [];
    }

    /**
     * 将字符串的一部分字符替换为指定字符
     * @param string $str 要替换的字符串
     * @param string $position 要替换的位置 left:从左边开始替换 middle:默认从中间开始替换 right:从右边开始替换
     * @param string $replace 要替换成的字符串 默认 *
     * @return string
     */
    public static function replaceWith(string $str, string $position = 'middle', string $replace = '*'): string
    {
        // 替换字符串长度均为原字符串一半 向下取整
        $str_len = mb_strlen($str);
        $replace_len = ceil($str_len / 3);
        $replace_str = str_repeat($replace, $replace_len);
        $offset = 0;
        if ($position === 'middle') {
            if ($str_len <= 5 && $str_len > 1) {
                $offset = 1;
            } elseif($str_len > 5) {
                $offset = ceil($str_len / 4);
            }
        } else {
            if ($position === 'right') $offset = -$replace_len;
        }
        return substr_replace($str, $replace_str, $offset, $replace_len);
    }

    /**
     * 快速格式化时间戳
     * @param false $timestamp
     * @param string $delimiter
     * @param false $short 是否包含时分秒
     * @return false|string
     */
    public static function dt(bool $timestamp = false, string $delimiter = '-', bool $short = false)
    {
        if ($timestamp === false) $timestamp = time();
        if ($short) {
            return date("Y{$delimiter}m{$delimiter}d", $timestamp);
        } else {
            return date("Y{$delimiter}m{$delimiter}d H:i:s", $timestamp);
        }
    }

    /**
     * 请求速率限制
     * @param int $seconds 秒数
     * @param int $times 最大请求次数
     * @return bool|string
     */
    public static function rateLimit(int $seconds = 10, int $times = 1)
    {
        $key = 'rate_limit:' . md5($_SERVER['REMOTE_ADDR'] . $_SERVER['REQUEST_URI'] . json_encode($_GET) . json_encode($_POST));
        $rds = self::Rds();
        $v = $rds->get($key);
        if ($v) {
            $arr = explode(',', $v);
            if ($arr[1] < $times) {
                $rds->set($key, $arr[0] . ',' . ($arr[1] + 1), time() - $arr[0]);
                return true;
            } else {
                return "请求太频繁,请稍后再试[$seconds,$times]";
            }
        } else {
            $rds->set($key, time() . ',1', $seconds);
            return true;
        }
    }
}