<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 12:05 AM
 */

namespace Horat1us\TaskBook\Controllers;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Horat1us\TaskBook\Entities\Task;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TasksController
 * @package Horat1us\TaskBook\Controllers
 */
class TasksController extends Controller
{
    /** @var string */
    public $path = '/^\/tasks$/';

    /**
     * @param Response $response
     * @return void
     */
    public function get(Response $response)
    {
        $limit = max((int)$this->request->get('limit', 3), 1);
        $page = max((int)$this->request->get('page', 1), 1);

        $repository = $this->entityManager->getRepository(Task::class);
        $query = $repository
            ->createQueryBuilder('tasks')
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $paginator = new Paginator($query);

        $response->setContent(json_encode([
            'count' => $paginator->count(),
            'tasks' => $query->getArrayResult(),
        ]));
    }
}