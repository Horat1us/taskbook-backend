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
     */
    protected $completed = false;

    /**
     * @Column(type="blob", nullable=true)
     * @var mixed
     */
    protected $image;
}