<?php

namespace Rey\BitrixMigrations\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand;

class MigrationsGenerateDoctrineCommand extends GenerateCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('bitrix:' . $this->getName());
    }

    /**
     * @param Doctrine\DBAL\Migrations\Configuration\Configuration $configuration
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param string $version
     * @param string|null $up
     * @param string|null $down
     *
     * @return string
     */
    protected function generateMigration(Configuration $configuration, InputInterface $input, $version, $up = null, $down = null)
    {
        $abstractMigrationClass = $configuration->getMigrationsAbstractClass();
        $example = "// \$this->enableBitrixAPI(); //include and configuration Bitrix API\n";

        $up = $example . $up;
        $down = $example . $down;
        $path = parent::generateMigration($configuration, $input, $version, $up, $down);

        $migrationContent = file_get_contents($path);

        $migrationContent = str_replace(
            'Doctrine\DBAL\Migrations\AbstractMigration',
            $abstractMigrationClass.' as AbstractMigration',
            $migrationContent
        );

        $migrationContent = str_replace(
            'use '.$abstractMigrationClass,
            'use CModule, CMain, CIBlock, CForm;// etc. Bitrix api classes' . "\n"
            . 'use '.$abstractMigrationClass,
            $migrationContent
        );

        file_put_contents($path, $migrationContent);

        return $path;
    }
}
