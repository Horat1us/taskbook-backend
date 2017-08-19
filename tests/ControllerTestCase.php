<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 12:43 AM
 */

namespace Horat1us\TaskBook\Tests;


use Doctrine\ORM\EntityManager;
use Horat1us\TaskBook\Controllers\Controller;
use Horat1us\TaskBook\EntityManagerContainer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ControllerTestCase
 * @package Horat1us\TaskBook\Tests
 */
abstract class ControllerTestCase extends TestCase
{
    /**
     * @var Controller
     */
    protected $controller;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->request = new Request();
        $this->entityManager = EntityManagerContainer::get();
    }

    /**
     * @param Response $response
     * @return void
     */
    protected function assertForbidden(Response $response)
    {
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('"Forbidden"', $response->getContent());
    }
}