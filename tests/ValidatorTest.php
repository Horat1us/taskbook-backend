<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 2:48 PM
 */

namespace Horat1us\TaskBook\Tests;


use Horat1us\TaskBook\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ValidatorTest
 * @package Horat1us\TaskBook\Tests
 */
class ValidatorTest extends TestCase
{
    /** @var  Request */
    protected $request;

    /** @var  Validator */
    protected $validator;

    protected function setUp()
    {
        $this->request = new Request();
        $this->validator = new Validator([], $this->request);
    }

    public function testNoErrors()
    {
        $this->validator->setRules($rules = [
            'token' => [
                new NotBlank(),
            ],
        ]);
        $this->assertEquals($this->validator->getRules(), $rules);
        $this->request->query->set('token', 'NotEmpty');
        $errors = $this->validator->validate();
        $this->assertCount(0, $errors);
    }

    public function testErrors()
    {
        $this->validator->setRules($rules = [
            'token' => [
                new NotBlank(),
            ],
        ]);
        $errors = $this->validator->validate();
        $this->assertCount(1, $errors);
        $tokenError = array_shift($errors);

        $this->assertArrayHasKey('details', $tokenError);

        $this->assertArrayHasKey('code', $tokenError);
        $this->assertEquals(NotBlank::IS_BLANK_ERROR, $tokenError['code']);

        $this->assertArrayHasKey('attribute', $tokenError);
        $this->assertEquals('token', $tokenError['attribute']);
    }
}