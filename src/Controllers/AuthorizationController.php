<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 12:24 AM
 */

namespace Horat1us\TaskBook\Controllers;


use Horat1us\TaskBook\Application;
use Horat1us\TaskBook\Entities\User;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthorizationController
 * @package Horat1us\TaskBook\Controllers
 */
class AuthorizationController extends Controller
{
    protected $path = '/^\/authorization$/';

    public function post()
    {
        if (Application::user() instanceof User) {
            return new Response("Forbidden", 403);
        }

        $usersRepository = $this->entityManager->getRepository(User::class);
        $user = $usersRepository->findOneBy(['name' => $this->request->query->get('name')]);
        xdebug_break();
        if (!$user instanceof User) {

        }
    }
}