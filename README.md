# ReyMigration

[![Join the chat at https://gitter.im/Olegator8800/ReyMigration](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/Olegator8800/ReyMigration?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Битрикс миграции на основе компонента Doctrine Migration

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Olegator8800/ReyMigration/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Olegator8800/ReyMigration/?branch=master)


Установка
------------

Composer:

    $ php composer.phar require rey/migration dev-master

Использование
------------

Создать файл н.п bin/console

    #!/usr/bin/env php
    <?php

    use Symfony\Component\Console\Application;
    use Doctrine\Common\Annotations\AnnotationRegistry;
    use Rey\BitrixMigrations\Configuration;
    use Rey\BitrixMigrations\MigrationManager;

    //указать путь до проекта
    $_SERVER['DOCUMENT_ROOT'] = __DIR__.'/../htdocs/';

    $loader = require __DIR__.'/../vendor/autoload.php';
    AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
    
    $console = new Application('console');
    $config = new Configuration();

    $config->setConnectionParameters(
                            array(
                                'dbname' => 'mydatabase',
                                'user' =>  'root',
                                'password' => '',
                                'host' => '127.0.0.1',
                                'driver' => 'pdo_mysql',
                            )
                        );

    $config->setMigrationsParameters(
                            array(
                                'migrations_directory' => __DIR__.'/../migration',
                            )
                        );

    $bitrixMigrationManager = new MigrationManager($console, $config);
    $bitrixMigrationManager->init();

    $console->run();

И запустить из консоли 

    $ php bin/console

Для генерации новой миграции выполнить команду:

    $ php bin/console bitrix:migrations:generate

Будет сгенерированная пустая миграция в дириктории %migrations_directory

Класс миграции по умолчанию унаследован от Rey\BitrixMigrations\AbstractMigration (можно переопределить параметром %abstract_class в MigrationsParameters)

Для использования api битрикса достаточно вызвать метод $this->enableBitrixAPI();

    public function up(Schema $schema)
    {
        $this->enableBitrixAPI();
        ...

Для выполнения одиночной миграции выполнить:

    $ php bin/console bitrix:migrations:execute %номер_миграции% --up

Для отката выполнить коману с ключем --down

    $ php bin/console bitrix:migrations:execute %номер_миграции% --down

Для выполнения всех ненакаченных миграцйи выполнить:

    $ php bin/console bitrix:migrations:migrate

Для просмотра статуса миграций:

    $ php bin/console bitrix:migrations:status

Для детальной информации по каждой миграции выполнить с ключом --show-versions

    $ php bin/console bitrix:migrations:status --show-versions


MySql Lite Driver
------------

При использование MySql для ускорения работы можно воспользоваться "упрощенным" драйвером. Заменить параметр driver на driverClass.

    $config->setConnectionParameters(
                            array(
                                ...
                                'driverClass' => new \Rey\BitrixMigrations\Driver\PDOMySql\LiteDriver(),
                            )
                        );
