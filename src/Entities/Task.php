<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/18/17
 * Time: 9:10 PM
 */

namespace Horat1us\TaskBook\Entities;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;


/**
 * Class Task
 * @package Horat1us\TaskBook\Entities
 *
 * @Entity
 * @Table(name="tasks")
 */
class Task
{
    /**
     * @Id
     * @Column(type="integer", unique=true)
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
     * @Column(type="text")
     * @var string
     */
    protected $text;

    /**
     * @Column(type="boolean")
     * @var boolean
     */
    protected $completed = false;

    /**
     * @Column(type="blob", nullable=true)
     * @var mixed
     */
    protected $image;

    /**
     * @return mixed
     */
    public function getId()
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
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return bool
     */
    public function getCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     */
    public function setCompleted(bool $completed)
    {
        $this->completed = $completed;
    }


}