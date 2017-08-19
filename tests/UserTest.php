<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 2:56 PM
 */

namespace Horat1us\TaskBook\Tests;


use Horat1us\TaskBook\Entities\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testSettingPassword()
    {
        $password = sha1(mt_rand());
        $user = new User();
        $user->setPassword($password);
        $this->assertTrue(password_verify($password, $user->getPasswordHash()));
    }
}