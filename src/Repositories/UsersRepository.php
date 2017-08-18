<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 1:43 AM
 */

namespace Horat1us\TaskBook\Repositories;


use Doctrine\ORM\EntityRepository;

class UsersRepository extends EntityRepository
{
    public function findByAccessToken(string $token)
    {

    }
}