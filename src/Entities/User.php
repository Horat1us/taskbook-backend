<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/18/17
 * Time: 9:22 PM
 */

namespace Horat1us\TaskBook\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;


/**
 * Class User
 * @package Horat1us\TaskBook\Entities
 *
 * @ORM\Entity()
 * @Table(name="users")
 */
class User
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @Column(type="string", length=24)
     * @var string
     */
    protected $name;

    /**
     * @Column(type="string", length=60)
     * @var string
     */
    protected $password;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordHash() :string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }
}