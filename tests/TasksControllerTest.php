<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 12:42 AM
 */

namespace Horat1us\TaskBook\Tests;


use Horat1us\TaskBook\Application;
use Horat1us\TaskBook\Controllers\TasksController;
use Horat1us\TaskBook\Entities\Task;
use Horat1us\TaskBook\Entities\User;
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

    public function testView()
    {
        $task = $this->createTask();

        $this->request->query->set('id', $task->getId());
        $this->request->setMethod('get');
        $response = $this->controller->dispatch();

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($content = $response->getContent(), true);
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());

        $this->assertArrayHasKey('id', $data);
        $this->assertEquals($data['id'], $task->getId());

        $this->assertArrayHasKey('name', $data);
        $this->assertEquals($data['name'], $task->getName());

        $this->assertArrayHasKey('text', $data);
        $this->assertEquals($data['text'], $task->getText());

        $this->assertArrayHasKey('isCompleted', $data);
    }

    public function testViewInvalidTask()
    {
        $this->request->query->set('id', 0);
        $this->request->setMethod('get');
        $response = $this->controller->dispatch();

        $this->assertEquals(404, $response->getStatusCode());
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


    public function testUnauthorizedModify()
    {
        Application::setUser();

        $this->request->setMethod('put');
        $response = $this->controller->dispatch();

        $this->assertForbidden($response);
    }

    public function testModifyValidationFailed()
    {
        Application::setUser(new User);
        $task = $this->createTask();

        $this->request->query->set('id', $task->getId());
        $this->request->request->set('completed', 2);

        $this->request->setMethod('put');
        $response = $this->controller->dispatch();

        $this->assertEquals(400, $response->getStatusCode());
        $this->deleteTask($task);
    }

    public function testModify()
    {
        Application::setUser(new User);
        $task = $this->createTask();

        $this->request->query->set('id', $task->getId());
        $this->request->request->set('completed', $newCompleted = true);
        $this->request->request->set('text', $newText = ($task->getText() . 'postfix'));

        $this->request->setMethod('put');
        $response = $this->controller->dispatch();

        $this->assertEquals(204, $response->getStatusCode());

        $this->entityManager->refresh($task);

        $this->assertEquals($task->getCompleted(), $newCompleted);
        $this->assertEquals($task->getText(), $newText);
        $this->deleteTask($task);
    }

    public function testModifyInvalidId()
    {
        Application::setUser(new User);
        $this->request->query->set('id', 0);

        $this->request->setMethod('put');
        $response = $this->controller->dispatch();

        $this->assertEquals(404, $response->getStatusCode());
    }

    protected function createTask(): Task
    {
        $task = new Task();

        $task->setName($name = "Tester");
        $task->setText($text = "Some important text");
        $task->setCompleted(false);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    protected function deleteTask(Task $task)
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }
}