<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 2:32 PM
 */

namespace Horat1us\TaskBook\Tests;


use Horat1us\TaskBook\Application;
use Horat1us\TaskBook\Entities\Token;
use Horat1us\TaskBook\Entities\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApplicationAuthTest
 * @package Horat1us\TaskBook\Tests
 */
class ApplicationAuthTest extends TestCase
{
    public function testNoHeader()
    {
        $request = new Request();
        $application = new Application($request);
        $application->run();
        $this->assertNull(Application::getUser());
    }

    public function testInvalidHeader()
    {
        $request = new Request();
        $request->headers->set('Authorization', 'SomeShit');
        $application = new Application($request);
        $application->run();
        $this->assertNull($application::getUser());
    }

    public function testInvalidToken()
    {
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer InvalidToken');
        $application = new Application($request);
        $application->run();
        $this->assertNull(Application::getUser());
    }

    public function testValidToken()
    {
        $request = new Request();
        $application = new Application($request);
        $entityManager = $application->getEntityManager();

        $user = new User();

        $user->setName(mt_rand());
        $user->setPassword(mt_rand());

        $tokensRepository = $entityManager->getRepository(Token::class);
        $token = $tokensRepository->create($user);

        $entityManager->persist($user);
        $entityManager->persist($token);
        $entityManager->flush();

        $request->headers->set('Authorization', 'Bearer ' . $token->getToken());
        $application->run();

        $this->assertInstanceOf(User::class, Application::getUser());
        $this->assertEquals($user->getId(), Application::getUser()->getId());

        $entityManager->remove($token);
        $entityManager->remove($user);
        $entityManager->flush();
    }
}