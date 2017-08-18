<?php

require __DIR__ . "/vendor/autoload.php";

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Horat1us\TaskBook\EntityManagerContainer;

$entityManager = EntityManagerContainer::get();

return ConsoleRunner::createHelperSet($entityManager);