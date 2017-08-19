<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 1:46 PM
 */

namespace Horat1us\TaskBook\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Token
 * @package Horat1us\TaskBook\Entities
 *
 * @ORM\Entity(repositoryClass="Horat1us\TaskBook\Repositories\TokensRepository")
 * @ORM\Table(name="tokens")
 */
class Token
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     * @var int
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Horat1us\TaskBook\Entities\User", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="cascade")
     * @var User
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=120)
     */
    protected $token;

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
}