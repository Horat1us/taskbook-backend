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

/**
 * Class ControllerTestCase
 * @package Horat1us\TaskBook\Tests
 */
class ControllerTestCase extends TestCase
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
}