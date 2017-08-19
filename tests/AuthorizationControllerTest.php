<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 3:03 PM
 */

namespace Horat1us\TaskBook\Tests;

use Horat1us\TaskBook\Application;
use Horat1us\TaskBook\Controllers\AuthorizationController;
use Horat1us\TaskBook\Entities\User;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class AuthorizationControllerTest
 * @package Horat1us\TaskBook\Tests
 */
class AuthorizationControllerTest extends ControllerTestCase
{
    /** @var  AuthorizationController */
    protected $controller;

    /** @var  User */
    protected $user;

    /** @var  string */
    protected $password;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->controller = new AuthorizationController($this->entityManager, $this->request);

        $this->user = new User();
        $this->user->setPassword($this->password = mt_rand());
        $this->user->setName(mt_rand());

        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }

    public function testNotAuthorizedGet()
    {
        Application::setUser();
        $response = new Response();

        $this->controller->get($response);
        $this->assertForbidden($response);
    }

    public function testAuthorizedGet()
    {
        Application::setUser($this->user);
        $response = new Response();

        $this->controller->get($response);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
        $this->assertArrayHasKey('name', $data);
    }

    public function testAuthorizedPost()
    {
        Application::setUser($this->user);
        $response = new Response();

        $this->controller->post($response);
        $this->assertForbidden($response);
    }

    public function testInvalidUsername()
    {
        Application::setUser();
        $response = new Response();

        $this->request->query->set('name', sha1(mt_rand()));

        $this->controller->post($response);
        $this->assertAuthorizationError($response);
    }

    public function testInvalidPassword()
    {
        Application::setUser();
        $response = new Response();

        $this->request->query->set('name', $this->user->getName());
        $this->request->query->set('password', $this->password . 'postfix');

        $this->controller->post($response);
        $this->assertAuthorizationError($response);
    }

    public function testReceivingToken()
    {
        Application::setUser();
        $response = new Response();

        $this->request->query->set('name', $this->user->getName());
        $this->request->query->set('password', $this->password);

        $this->controller->post($response);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
        $this->assertArrayHasKey('token', $data);
    }

    /**
     * @param Response $response
     * @return void
     */
    protected function assertAuthorizationError(Response $response)
    {
        $this->assertEquals(json_encode("Invalid name or password"), $response->getContent());
        $this->assertEquals(400, $response->getStatusCode());
    }
}