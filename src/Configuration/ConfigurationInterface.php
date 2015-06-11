<?php

namespace Rey\BitrixMigrations\Configuration;

/**
 * Interface for storing configuration
 */
interface ConfigurationInterface 
{
    /**
     * Set parameters for connect database
     *
     * @param array $params
     *
     * @api
     */
    public function setConnectionParameters(array $params);

    /**
     * Get  parameters for connect database
     *
     * @return array
     *
     * @api
     */
    public function getConnectionParameters();

    /**
     * Set parameters for migration
     *
     * @param array $params
     *
     * @api
     */
    public function setMigrationsParameters(array $params);

    /**
     * Get parameters for migration
     *
     * @return array
     *
     * @api
     */
    public function getMigrationsParameters();

    /**
     * Get instance Doctrine Connection
     *
     * @return \Doctrine\DBAL\Connection
     */
    public function getDoctrineDbConnection();

    /**
     * Get instance Doctrine Configuration
     *
     * @return \Rey\BitrixMigrations\Configuration\DoctrineConfiguration
     */
    public function getDoctrineConfiguration();
}
