<?php

namespace srun\base;

interface Srun
{
    public function req($path, array $data = [], string $method = 'get', bool $access_token = true, array $header = []);

    public function userExist($user_name): bool;

    public function userRight($user_name, $pwd): bool;

    public function accessToken();

    public function postSso($url, $post_data);

    public function ssoLogin($auth_addr, $secret, $user_name, $ip, $ac_id = null): string;

    public function ssoDrop($auth_addr, $secret, $user_name, $ip, $ac_id = null): string;

    public function sso(string $username, string $ip, $ac_id = null, $drop = null): string;

    public function getAuthMsg($rs): array;

    public function keyHandleLog($msg, bool $next = false, string $file = '');
}