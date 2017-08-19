<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 1:26 PM
 */

namespace Horat1us\TaskBook;


use Doctrine\ORM\EntityManager;
use Horat1us\TaskBook\Controllers\AuthorizationController;
use Horat1us\TaskBook\Controllers\Controller;
use Horat1us\TaskBook\Controllers\TasksController;
use Horat1us\TaskBook\Entities\Token;
use Horat1us\TaskBook\Entities\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Application
 * @package Horat1us\TaskBook
 */
class Application
{
    /** @var  Request */
    protected $request;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  User|null */
    protected static $user;

    /**
     * Application constructor.
     * @param Request $request
     */
    public function __construct(Request $request = null)
    {
        $this->setRequest($request);
        $this->entityManager = EntityManagerContainer::get();
    }

    /**
     * @return User|null
     */
    public static function getUser()
    {
        return self::$user;
    }

    /**
     * @param User|null $user
     */
    public static function setUser(User $user = null)
    {
        self::$user = $user;
    }

    /**
     * @return Response
     */
    public function run(): Response
    {
        if ($this->request->getMethod() === "OPTIONS") {
            return new CorsResponse();
        }

        $this->authorize();
        return $this->execute();
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request ?? Request::createFromGlobals();
        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @todo: implement parsing header and loading user by access token
     */
    protected function authorize()
    {
        if (
            !($header = $this->request->headers->get('Authorization'))
            || !preg_match('/^Bearer (.+)$/', $header, $matches)
        ) {
            return;
        }

        $tokensRepository = $this->entityManager->getRepository(Token::class);
        $token = $tokensRepository->findOneBy(['token' => $matches[1]]);
        if (!$token instanceof Token) {
            return;
        }

        static::setUser($token->getUser());
    }

    /**
     * @return Response
     */
    protected function execute(): Response
    {
        $router = new Router([
            new TasksController($this->entityManager, $this->request),
            new AuthorizationController($this->entityManager, $this->request),
        ]);

        $controller = $router->match($this->request);
        if (!$controller instanceof Controller) {
            return new Response("Not found", 404);
        }

        return $controller->dispatch();
    }

}