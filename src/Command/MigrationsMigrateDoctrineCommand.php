<?php

namespace Rey\BitrixMigrations\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;

class MigrationsMigrateDoctrineCommand extends MigrateCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('bx:' . $this->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->getApplication()->getHelperSet()->has('migrations')){
            $configuration = $this->getHelper('migrations')->getConfiguration();
            $configuration->registerMigrationsFromDirectory($configuration->getMigrationsDirectory());
            $this->setMigrationConfiguration($configuration);
        }

        parent::execute($input, $output);
    }
}
