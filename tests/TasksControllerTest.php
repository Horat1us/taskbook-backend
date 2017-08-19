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
        $this->controller = new TasksController($this->entityManager, $this->request);
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

    public function testAdding()
    {
        $response = new Response();
        $this->request->initialize([
            'name' => 'Horat1us',
            'text' => 'Do nothing',
        ]);
        $this->controller->post($response);
        $this->assertEquals($response->getStatusCode(), 201);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $content);
    }

    public function testAddingError()
    {
        $response = new Response();
        $this->controller->post($response);

        $this->assertEquals(400, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
        $this->assertCount(2, $data);
    }

    public function testInvalidMethod()
    {
        $this->request->setMethod('OPTIONS');
        $response = $this->controller->dispatch();
        $this->assertEquals(404, $response->getStatusCode());
    }
}