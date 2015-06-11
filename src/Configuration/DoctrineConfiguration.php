<?php

namespace Rey\BitrixMigrations\Configuration;

use Doctrine\DBAL\Migrations\Configuration\Configuration;

class DoctrineConfiguration extends Configuration
{
    /**
     * @var string
     */
    private $abstractClassName = '';

    /**
     * Set abstract class name for migrations
     *
     * @param string
     */
    public function setMigrationsAbstractClass($class)
    {
        $this->abstractClassName = $class;
    }

    /**
     * Get abstract class name for migrations
     *
     * @return string
     */
    public function getMigrationsAbstractClass()
    {
        return $this->abstractClassName;
    }
}
