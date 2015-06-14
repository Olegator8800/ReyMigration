<?php

namespace Rey\BitrixMigrations\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand;

class MigrationsDiffDoctrineCommand extends DiffCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('bitrix:' . $this->getName());
    }
}
