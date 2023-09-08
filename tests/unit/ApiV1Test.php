<?php

use Codeception\Test\Unit;
use srun\src\Srun;
use srun\src\User;

/**
 * 北向接口V1版本测试<br>
 * 测试前请修改下文北向接口地址<br>
 * 并创建用户:srun 密码:111111<br>
 * 设置北向接口access_token为: k2kflzvWWsEtdQGFWp0FuYg86LaIN8TJ
 */
class ApiV1Test extends Unit
{
    /**
     * @var UnitTester
     */
    protected UnitTester $tester;
    // 北向接口地址
    protected string $addr;

    protected function _before()
    {
        $this->addr = 'https://idp.srun.com:8001/';
    }

    protected function _after()
    {
    }

    // tests
    public function testAccessToken()
    {
        $srun = new Srun($this->addr);
        $rs = $srun->accessToken();
        $this->assertNotFalse($rs, 'access token error!');
        $this->assertEquals('k2kflzvWWsEtdQGFWp0FuYg86LaIN8TJ', $rs, 'access token not right!');
    }

    public function testUserExist()
    {
        $srun = new Srun($this->addr);
        $this->assertTrue($srun->userExist('srun'));
        $this->assertFalse($srun->userExist('srun_test'));
        $this->assertFalse($srun->userExist(null));
        $this->assertFalse($srun->userExist(''));
    }

    public function testUserRight()
    {
        $srun = new Srun($this->addr);

        $this->assertTrue($srun->userRight('srun', '111111'));
        $this->assertFalse($srun->userRight('srun', '1111112'));
    }

    public function testUser()
    {
        $user = new User($this->addr);
        $rs = $user->view('srun');

        $this->assertIsNotString($rs);
        $this->assertIsObject($rs);
        $this->assertObjectHasProperty('data', $rs);
    }

    public function testTest()
    {
        $arr = ['aa' => 'bb', 'cc' => 'dd', 'ee' => ['f' => 'ff', 'g' => 'gg']];
        $this->assertContains(['f' => 'ff', 'g' => 'gg'], $arr);
    }
}