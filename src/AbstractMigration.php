<?php

namespace Rey\BitrixMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration as DoctrineAbstractMigration;

/**
 * The abstract class for the implementation of migration
 * Сlass contains helper functions for working with Bitrix
 */
abstract class AbstractMigration extends DoctrineAbstractMigration
{
    /**
     * @var null|string
     */
    private $personalRoot = null;

    /**
     * Установить $_SERVER['BX_PERSONAL_ROOT']
     *
     * @param null|string $path
     */
    protected function setPersonalRoot($path)
    {
        $this->personalRoot = $path;
    }

    /**
     * Получить $_SERVER['BX_PERSONAL_ROOT']
     *
     * @return null|string
     */
    protected function getPersonalRoot()
    {
        return $this->personalRoot;
    }

    /**
     * Получить путь до корня проекта
     *
     * @return string
     */
    protected function getDocumentRoot()
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    /**
     * Подключить api Битрикса
     *
     * @param  string $siteLang Языковая версия сайта
     * @param  string $siteId   Id сайта
     */
    protected function enableBitrixAPI($siteLang = 'ru', $siteId = 's1')
    {
        global $DBType, $DBHost, $DBLogin, $DBPassword, $DBName, $DBDebug;

        ini_set('error_reporting', E_ERROR);
        $_SERVER['DOCUMENT_ROOT'] = $this->getDocumentRoot();

        if ($this->getPersonalRoot()) {
            $_SERVER['BX_PERSONAL_ROOT'] = $this->getPersonalRoot();
        }

        $_SERVER['HTTP_X_REAL_IP'] = '127.0.0.1';

        $sDateFormat = 'DD.MM.YYYY HH:MI:SS';

        define('FORMAT_DATETIME', $sDateFormat); // формат даты/времени
        define('SITE_ID', $siteId); // символьный идентификатор сайта
        define('LANG', $siteLang); // символьный идентификатор языка
        define('NO_KEEP_STATISTIC', true); //не вести статистику
        define('NOT_CHECK_PERMISSIONS', true); //не проверять права доступа
        define('BX_CLUSTER_GROUP', -1); // некоторое время агенты будут выполнять только на первичные кластерные группы (1)

        $this->disableCacheIBlock();

        require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

        //Подключение автозагрузчика Bitrix
        if(function_exists('\__autoload')){
            spl_autoload_register('\__autoload');
        }
    }

    /**
     * Выключает кеширование инфоблоков, типов инфоблоков и свойств
     */
    private function disableCacheIBlock()
    {
        define('CACHED_b_iblock_type', false);
        define('CACHED_b_iblock', false);
        define('CACHED_b_iblock_property_enum', false);
    }
}
