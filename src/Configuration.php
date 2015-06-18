<?php

namespace Rey\BitrixMigrations;

use Rey\BitrixMigrations\Configuration\ConfigurationInterface;
use Rey\BitrixMigrations\Configuration\DoctrineConfiguration;
use Doctrine\DBAL\DriverManager as DoctrineDriverManager;
use Doctrine\DBAL\Migrations\OutputWriter;
use Symfony\Component\Console\Output\ConsoleOutput;

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
    private $doctrineConnection = null;

    /**
     * @var \Rey\BitrixMigrations\Configuration\DoctrineConfiguration|null
     */
    private $doctrineConfiguration = null;

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
        if ($this->doctrineConnection === null) {
            $connection = DoctrineDriverManager::getConnection($this->getConnectionParameters());

            if ($connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySqlPlatform) {
                $abstractPlatform = $connection->getDatabasePlatform();
                $abstractPlatform->registerDoctrineTypeMapping('enum', 'string');
                $abstractPlatform->registerDoctrineTypeMapping('set', 'string');
            }

            $this->doctrineConnection = $connection;
        }

        return $this->doctrineConnection;
    }

    /**
     * {@inheritdoc}
     */
    public function getDoctrineConfiguration()
    {
        if ($this->doctrineConfiguration === null) {
            $params = $this->getMigrationsParameters();

            $output = $this->getOutputWriter();
            $config = new DoctrineConfiguration($this->getDoctrineDbConnection(), $output);

            $config->setName($params['name']);
            $config->setMigrationsDirectory($params['migrations_directory']);
            $config->setMigrationsNamespace($params['migrations_namespace']);
            $config->setMigrationsTableName($params['table_name']);
            $config->setMigrationsAbstractClass($params['abstract_class']);

            $this->doctrineConfiguration = $config;
        }

        return $this->doctrineConfiguration;
    }

    /**
     * Get Doctrine OutputWriter
     *
     * @return \Doctrine\DBAL\Migrations\OutputWriter
     */
    private function getOutputWriter()
    {
        $consoleOutput = new ConsoleOutput();
        $consoleOutput->setDecorated(true);

        $outputWriter = new OutputWriter(function($msg) use($consoleOutput) {
            //intercept errors message when empty sql
            if (strpos($msg, 'was executed but did not result in any SQL statements.</error>') !== false) {
                return;
            }

            $consoleOutput->writeln($msg);
        });

        return $outputWriter;
    }
}
