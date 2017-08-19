<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 2:05 PM
 */

namespace Horat1us\TaskBook\Repositories;


use Doctrine\ORM\EntityRepository;
use Horat1us\TaskBook\Entities\Token;
use Horat1us\TaskBook\Entities\User;

/**
 * Class TokensRepository
 * @package Horat1us\TaskBook\Repositories
 */
class TokensRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return Token
     */
    public function create(User $user)
    {
        $token = new Token();

        $token
            ->setUser($user)
            ->setToken(bin2hex(random_bytes(60)));

        return $token;
    }
}