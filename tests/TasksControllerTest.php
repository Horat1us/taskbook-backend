<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 12:42 AM
 */

namespace Horat1us\TaskBook\Tests;


use Horat1us\TaskBook\Controllers\TasksController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TasksControllerTest
 * @package Horat1us\TaskBook\Tests
 */
class TasksControllerTest extends ControllerTestCase
{
    /**
     * @var TasksController
     */
    protected $controller;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->controller = new TasksController($this->request, $this->entityManager);
    }

    /**
     * @todo: tests for pagination
     */
    public function testList()
    {
        $response = new Response();
        $this->controller->get($response);

        $this->assertEquals($response->getStatusCode(), 200);
        $responseJson = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('count', $responseJson);
        $this->assertArrayHasKey('tasks', $responseJson);
    }
}