<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/18/17
 * Time: 11:59 PM
 */

namespace Horat1us\TaskBook;

use Horat1us\TaskBook\Controllers\Controller;


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
     * @return Controller|null
     */
    public function match()
    {
        foreach ($this->controllers as $controller) {
            if ($controller->match()) {
                return $controller;
            }
        }

        return null;
    }
}