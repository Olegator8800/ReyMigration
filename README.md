# ReyMigration

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

    $ php bin/console bx:migrations:generate

Будет сгенерированная пустая миграция в дириктории %migrations_directory

MySql Lite Driver
------------

При использование MySql для ускорения работы можно воспользоваться "упрощенным" драйвером. Заменить параметр driver на driverClass.

    $config->setConnectionParameters(
                            array(
                                ...
                                'driverClass' => new \Rey\BitrixMigrations\Driver\PDOMySql\LiteDriver(),
                            )
                        );
