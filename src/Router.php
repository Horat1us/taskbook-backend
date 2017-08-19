<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/18/17
 * Time: 11:59 PM
 */

namespace Horat1us\TaskBook;

use Horat1us\TaskBook\Controllers\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class Router
 * @package Horat1us\TaskBook
 */
class Router
{
    /**
     * @var Controller[]
     */
    protected $controllers;

    /**
     * Router constructor.
     * @param array $controllers
     */
    public function __construct(array $controllers = [])
    {
        $this->controllers = $controllers;
    }

    /**
     * @param Request $request
     * @return Controller|null
     */
    public function match(Request $request)
    {
        foreach ($this->controllers as $controller) {
            if ($controller->setRequest($request)->match()) {
                return $controller;
            }
        }

        return null;
    }
}