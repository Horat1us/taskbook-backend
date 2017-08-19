<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/18/17
 * Time: 11:38 PM
 */

use Horat1us\TaskBook\Application;

require __DIR__ . "/../vendor/autoload.php";

$application = new Application();
$application->run()->send();
