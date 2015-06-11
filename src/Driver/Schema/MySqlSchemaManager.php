<?php

namespace Rey\BitrixMigrations\Driver\Schema;

use Doctrine\DBAL\Schema\MySqlSchemaManager as DoctrineMySqlSchemaManager;
use Doctrine\DBAL\Schema\Schema;

/**
 * Надстройка над стандартным DBAL MySqlSchemaManager для ускорения работы
 * за счет отключения построения всей схемы бд
 */
class MySqlSchemaManager extends DoctrineMySqlSchemaManager
{
    /**
     * {@inheritdoc}
     */
    public function createSchema()
    {
        $sequences = array();

        if ($this->_platform->supportsSequences()) {
            $sequences = $this->listSequences();
        }

        $tables = array();

        return new Schema($tables, $sequences, $this->createSchemaConfig());
    }
}
