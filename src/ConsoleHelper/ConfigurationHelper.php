<?php

namespace Rey\BitrixMigrations\ConsoleHelper;

use Symfony\Component\Console\Helper\Helper;
use Doctrine\DBAL\Migrations\Configuration\Configuration;

class ConfigurationHelper extends Helper
{
    /**
     * @var \Doctrine\DBAL\Migrations\Configuration\Configuration
     */
    private $configuration;

    /**
     * @param \Doctrine\DBAL\Migrations\Configuration\Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Get Configuration instance
     *
     * @return \Doctrine\DBAL\Migrations\Configuration\Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'configuration';
    }
}
