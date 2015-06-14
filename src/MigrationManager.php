<?php

namespace Rey\BitrixMigrations;

use Rey\BitrixMigrations\Configuration\ConfigurationInterface;
use Rey\BitrixMigrations\ConsoleHelper\ConfigurationHelper;
use Rey\BitrixMigrations\Command;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\DialogHelper;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper as DoctrineConnectionHelper;


/**
 * Class extends the Doctrine migration for use with Bitrix cms
 *
 * @api
 */
class MigrationManager
{
    /**
     * @var \Symfony\Component\Console\Application
     */
    private $console;

    /**
     * @var \Rey\BitrixMigrations\Configuration
     */
    private $config;

    /**
     * Create new instance
     *
     * @param Application            $console \Symfony\Component\Console\Application
     * @param ConfigurationInterface $config  \Rey\BitrixMigrations\Configuration
     *
     * @api
     */
    public function __construct(Application $console, ConfigurationInterface $config)
    {
        $this->console = $console;
        $this->config = $config;
    }

    /**
     * Initialize Bitrix migration
     *
     * @api
     */
    public function init()
    {
        $this->initializeHelpers();
        $this->registerCommands();
    }

    /**
     * Get Configuration
     *
     * @return \Rey\BitrixMigrations\Configuration
     *
     * @api
     */
    public function getConfiguration()
    {
        return $this->config;
    }

    /**
     * Get console instance
     *
     * @return \Symfony\Component\Console\Application
     */
    protected function getConsole()
    {
        return $this->console;
    }

    /**
     * Get Console HelperSet instance
     *
     * @return \Symfony\Component\Console\Helper\HelperSet
     */
    protected function getHelperSet()
    {
        $console = $this->getConsole();

        if (!$helperSet = $console->getHelperSet()) {
            $helperSet = new HelperSet();
        }

        return $helperSet;
    }

    /**
     * Initialize helpers for console
     */
    protected function initializeHelpers()
    {
        $config = $this->getConfiguration();
        $console = $this->getConsole();
        $helperSet = $this->getHelperSet();

        if (!$helperSet->has('dialog')) {
            $helperSet->set(new DialogHelper(), 'dialog');
        }

        if (!$helperSet->has('db')) {
            $connection = $config->getDoctrineDbConnection();
            $helperSet->set(new DoctrineConnectionHelper($connection), 'db');
        }

        if (!$helperSet->has('migrations')) {
            $migrationConfiguration = $config->getDoctrineConfiguration();
            $migrationConfiguration->registerMigrationsFromDirectory($migrationConfiguration->getMigrationsDirectory());

            $helperSet->set(new ConfigurationHelper($migrationConfiguration), 'migrations');
        }

        if ($helperSet->has('em')) {
            $console->add(new Command\MigrationsDiffDoctrineCommand());
        }
    }

    /**
     * Get a list of commands
     *
     * @return string[] Command name
     */
    protected function getCommandsList()
    {
        return array(
                'MigrationsExecuteDoctrineCommand',
                'MigrationsGenerateDoctrineCommand',
                'MigrationsMigrateDoctrineCommand',
                'MigrationsStatusDoctrineCommand',
                'MigrationsVersionDoctrineCommand',
            );
    }

    /**
     * Register commands for console
     */
    protected function registerCommands()
    {
        $console = $this->getConsole();
        $commandsList = $this->getCommandsList();
        $doctrineConfig = $this->getConfiguration()->getDoctrineConfiguration();

        foreach ($commandsList as $commandName) {
            $commandClass = sprintf('\Rey\BitrixMigrations\Command\%s', $commandName);
            $command = new $commandClass();
            $command->setMigrationConfiguration($doctrineConfig);

            $console->add($command);
        }
    }
}
