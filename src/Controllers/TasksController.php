<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 12:05 AM
 */

namespace Horat1us\TaskBook\Controllers;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Horat1us\TaskBook\Application;
use Horat1us\TaskBook\Entities\Task;
use Horat1us\TaskBook\Validator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

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
        if ($this->request->query->get('id') !== null) {
            $this->viewTask($response);
            return;
        }

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
                new Length([
                    'max' => 1000,
                ]),
            ],
        ];

        if (!$this->validate($response, $rules)) {
            return;
        }

        $task = new Task();
        $task->setName($this->request->get('name'));
        $task->setText($this->request->get('text'));

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $response->setStatusCode(201);
        $response->setContent(json_encode([
            'id' => $task->getId(),
        ]));
    }

    public function put(Response $response)
    {
        if (!Application::getUser()) {
            $response->setStatusCode(403);
            $response->setContent(json_encode("Forbidden"));
            return;
        }

        $task = $this->entityManager->getRepository(Task::class)
            ->find((int)$this->request->get('id'));

        if (!$task instanceof Task) {
            $response->setStatusCode(404);
            return;
        }

        $rules = [
            'text' => [
                new Length([
                    'max' => 1000,
                ]),
            ],
            'completed' => [
                new Range([
                    'min' => 0,
                    'max' => 1,
                ]),
            ],
        ];

        if (!$this->validate($response, $rules)) {
            return;
        }

        $task->setText($this->request->get('text'));
        $task->setCompleted($this->request->get('completed'));

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $response->setStatusCode(204);
    }

    /**
     * @param Response $response
     */
    protected function viewTask(Response $response)
    {
        $taskRepository = $this->entityManager->getRepository(Task::class);
        $task = $taskRepository->find((int)$this->request->get('id'));

        if (!$task instanceof Task) {
            $response->setStatusCode(404);
            return;
        }

        $response->setContent(json_encode([
            'id' => $task->getId(),
            'name' => $task->getName(),
            'isCompleted' => $task->getCompleted(),
            'text' => $task->getText(),
        ]));
    }

    /**
     * @param Response $response
     * @param array[] $rules
     * @return bool
     */
    protected function validate(Response $response, array $rules): bool
    {
        $validator = new Validator($rules, $this->request);

        if ($errors = $validator->validate()) {
            $response->setStatusCode(400);
            $response->setContent(json_encode($errors));
            return false;
        }

        return true;
    }
}