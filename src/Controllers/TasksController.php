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
use Horat1us\TaskBook\Validator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

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

    /**
     * @param Response $response
     * @return void
     */
    public function post(Response $response)
    {
        $rules = [
            'name' => [
                new NotBlank(),
                new Length([
                    'min' => 4,
                    'max' => 24,
                ]),
            ],
            'text' => [
                new NotBlank(),
            ],
        ];

        $validator = new Validator($rules, $this->request);
        $errors = $validator->validate();

        if (!empty($errors)) {
            $response->setStatusCode(400);
            $response->setContent(json_encode($errors));
            return;
        }

        $task = new Task();
        $task->setName($this->request->query->get('name'));
        $task->setText($this->request->query->get('text'));

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $response->setStatusCode(201);
        $response->setContent(json_encode([
            'id' => $task->getId(),
        ]));
    }
}