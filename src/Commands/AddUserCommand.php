<?php

namespace Horat1us\TaskBook\Commands;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Horat1us\TaskBook\Entities\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class AddUserCommand
 * @package Horat1us\TaskBook\Commands
 */
class AddUserCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    public $entityManager;

    public function __construct(EntityManagerInterface $entityManager, $name = null)
    {
        parent::__construct($name);

        $this->entityManager = $entityManager;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName("make:user")
            ->setDescription("Creates a new user");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $usernameQuestion = new Question("Username: ", false);

        $username = $helper->ask($input, $output, $usernameQuestion);
        if (!preg_match('/[a-zA-Z\d]{4,}/', $username)) {
            $output->write(
                "Username is not valid."
                . "It must contain only letters and number and have at least 4 symbols",
                true
            );
            return;
        }

        $passwordQuestion = new Question("Password: ", false);
        $passwordQuestion->setHidden(true);

        $password = $helper->ask($input, $output, $passwordQuestion);

        $user = new User();
        $user->setName($username);
        $user->setPassword($password);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}