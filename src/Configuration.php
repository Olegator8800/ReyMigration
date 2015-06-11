<?php

namespace Rey\BitrixMigrations;

use Rey\BitrixMigrations\Configuration\ConfigurationInterface;
use Rey\BitrixMigrations\Configuration\DoctrineConfiguration;
use Doctrine\DBAL\DriverManager as DoctrineDriverManager;

/**
 * Class for storing MigrationManager configuration
 *
 * @api
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var \Doctrine\DBAL\Connection|null
     */
    protected $connection = null;

    /**
     * Sets the default values for parameters
     */
    public function __construct()
    {
        $this->parameters['db']['dbname']   = '';
        $this->parameters['db']['user']     = 'root';
        $this->parameters['db']['password'] = '';
        $this->parameters['db']['host']     = '127.0.0.1';
        $this->parameters['db']['driver']   = 'pdo_mysql';

        $this->parameters['migrations']['name'] = 'BXMigrations';
        $this->parameters['migrations']['migrations_namespace'] = 'BXMigrations';
        $this->parameters['migrations']['table_name'] = 'doctrine_migration_versions';
        $this->parameters['migrations']['migrations_directory'] = 'migrations';
        $this->parameters['migrations']['abstract_class'] = 'Rey\BitrixMigrations\AbstractMigration';
    }

    /**
     * {@inheritdoc}
     */
    public function setConnectionParameters(array $params)
    {
        $this->parameters['db'] = array_replace($this->parameters['db'], $params);
    }

    /**
     * {@inheritdoc}
     */
    public function getConnectionParameters()
    {
        return $this->parameters['db'];
    }

    /**
     * {@inheritdoc}
     */
    public function setMigrationsParameters(array $params)
    {
        $this->parameters['migrations'] = array_replace($this->parameters['migrations'], $params);
    }

    /**
     * {@inheritdoc}
     */
    public function getMigrationsParameters()
    {
        return $this->parameters['migrations'];
    }

    /**
     * {@inheritdoc}
     */
    public function getDoctrineDbConnection()
    {
        if ($this->connection === null) {
            $connection = DoctrineDriverManager::getConnection($this->getConnectionParameters());

            if ($connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySqlPlatform) {
                $abstractPlatform = $connection->getDatabasePlatform();
                $abstractPlatform->registerDoctrineTypeMapping('enum', 'string');
                $abstractPlatform->registerDoctrineTypeMapping('set', 'string');
            }

            $this->connection = $connection;
        }

        return $this->connection;
    }

    /**
     * {@inheritdoc}
     */
    public function getDoctrineConfiguration()
    {
        $params = $this->getMigrationsParameters();
        $config = new DoctrineConfiguration($this->getDoctrineDbConnection());

        $config->setName($params['name']);
        $config->setMigrationsDirectory($params['migrations_directory']);
        $config->setMigrationsNamespace($params['migrations_namespace']);
        $config->setMigrationsTableName($params['table_name']);
        $config->setMigrationsAbstractClass($params['abstract_class']);

        return $config;
    }
}
