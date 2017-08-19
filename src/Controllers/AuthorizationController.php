<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 12:24 AM
 */

namespace Horat1us\TaskBook\Controllers;


use Horat1us\TaskBook\Application;
use Horat1us\TaskBook\Entities\Token;
use Horat1us\TaskBook\Entities\User;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthorizationController
 * @package Horat1us\TaskBook\Controllers
 */
class AuthorizationController extends Controller
{
    /** @var string */
    protected $path = '/^\/authorization$/';

    /**
     * @param Response $response
     * @return void
     */
    public function get(Response $response)
    {
        if (!Application::getUser() instanceof User) {
            $response->setStatusCode(403);
            $response->setContent(json_encode("Forbidden"));
            return;
        }

        $response->setContent(json_encode([
            'name' => Application::getUser()->getName(),
        ]));
    }

    /**
     * @param Response $response
     * @return void
     */
    public function post(Response $response)
    {
        if (Application::getUser() instanceof User) {
            $response->setStatusCode(403);
            $response->setContent(json_encode("Forbidden"));
            return;
        }

        $usersRepository = $this->entityManager->getRepository(User::class);
        $user = $usersRepository->findOneBy(['name' => $this->request->query->get('name')]);

        if (
            (!$user instanceof User)
            || !password_verify(
                $this->request->query->get('password'),
                $user->getPasswordHash()
            )
        ) {
            $response->setStatusCode(400);
            $response->setContent(json_encode("Invalid name or password"));
            return;
        }

        $tokensRepository = $this->entityManager->getRepository(Token::class);
        $token = $tokensRepository->create($user);

        $this->entityManager->persist($token);
        $this->entityManager->flush();

        $response->setContent(json_encode([
            'token' => $token->getToken(),
        ]));
    }
}