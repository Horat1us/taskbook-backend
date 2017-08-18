<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/18/17
 * Time: 11:40 PM
 */

namespace Horat1us\TaskBook\Controllers;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractController
 * @package Horat1us\TaskBook\Controllers
 */
abstract class Controller
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * AbstractController constructor.
     *
     * @param Request $request
     * @param EntityManager $entityManager
     */
    public function __construct(Request $request, EntityManager $entityManager)
    {
        $this->request = $request;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $action
     * @return Response
     */
    protected function beforeAction(string $action): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    /**
     * @param string $action
     * @param Response $response
     * @return void
     */
    protected function afterAction(string $action, Response $response)
    {
    }

    /**
     * @return Response
     */
    final public function dispatch(): Response
    {
        $action = $this->request->getMethod();
        if (!method_exists($this, $action)) {
            return new Response("Not found", 404);
        }

        $response = $this->beforeAction($this->request);
        call_user_func([$this, $action], $response);
        $this->afterAction($action, $response);

        return $response;
    }

    /**
     * @return bool
     */
    final public function match(): bool
    {
        return preg_match($this->path, $this->request->getRequestUri()) === 1;
    }
}