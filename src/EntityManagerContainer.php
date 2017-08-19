<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/18/17
 * Time: 11:01 PM
 */

namespace Horat1us\TaskBook;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\DBAL\Configuration as DbalConfiguration;
use Doctrine\DBAL\DriverManager;
use \Doctrine\ORM\Configuration as OrmConfiguration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Symfony\Component\Yaml\Yaml;

/**
 * Class EntityManagerContainer
 * @package Horat1us\TaskBook
 */
class EntityManagerContainer
{
    /** @var  EntityManagerInterface */
    protected static $instance;

    /**
     * @return EntityManagerInterface
     */
    public static function get(): EntityManagerInterface
    {
        return static::$instance ?? (static::$instance = static::instantiate());
    }

    /**
     * @return EntityManagerInterface
     */
    protected static function instantiate(): EntityManagerInterface
    {

        AnnotationRegistry::registerFile(
            __DIR__ . "/../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php"
        );

        $config = Yaml::parse(
            file_exists(__DIR__ . "/../config.yml")
                ? file_get_contents(__DIR__ . "/../config.yml")
                : file_get_contents(__DIR__ . "/../config.yml.dist")
        );

        $connection = DriverManager::getConnection(
            $config['database'],
            new DbalConfiguration()
        );

        $ormConfig = new OrmConfiguration();
        $ormConfig->setAutoGenerateProxyClasses(false);
        $ormConfig->setProxyNamespace("Horat1us\TaskBook\Entities\Proxies");
        $ormConfig->setProxyDir(__DIR__ . '/src/Entities/Proxies');
        $ormConfig->setEntityNamespaces(["Horat1us\TaskBook\Entities\Proxies"]);
        $ormConfig->setMetadataDriverImpl(AnnotationDriver::create([__DIR__ . '/Entities']));

        return EntityManager::create(
            $connection,
            $ormConfig
        );
    }
}