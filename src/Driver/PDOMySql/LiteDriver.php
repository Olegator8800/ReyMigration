<?php

namespace Rey\BitrixMigrations\Driver\PDOMySql;

use Rey\BitrixMigrations\Driver\Schema\MySqlSchemaManager;
use Doctrine\DBAL\Driver\PDOMySql\Driver;
use Doctrine\DBAL\Connection;

/**
 * Надстройка над стандартным DBAL PDO MySql driver  для ускорения работы
 * за счет отключения построения всей схемы бд.
 */
class LiteDriver extends Driver
{
    /**
     * {@inheritdoc}
     */
    public function getSchemaManager(Connection $conn)
    {
        return new MySqlSchemaManager($conn);
    }
}
