<?php

namespace Rey\BitrixMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration as DoctrineAbstractMigration;

/**
 * Абстрактный класс для миграции
 * Содержит вспомогательные функции для работы с api Битрикса
 */
abstract class AbstractMigration extends DoctrineAbstractMigration
{
    private $siteId;

    /**
     * Получить формат даты и времени
     *
     * @return string
     */
    protected function getDateTimeFormat()
    {
        return 'DD.MM.YYYY HH:MI:SS';
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
     * Получить путь к personal root сайта
     *
     * Переопределить метод получения путей до дириктории PersonalRoot
     * в зависимости от Id сайта ($this->getSiteId()) при многосайтовости.
     *
     * @return null|string
     */
    protected function getPersonalRoot()
    {
        return $_SERVER['BX_PERSONAL_ROOT'];
    }

    /**
     * Подключить api Битрикса
     *
     * @param string $siteLanguage
     * @param string $siteId
     */
    protected function enableBitrixAPI($siteLanguage = 'ru', $siteId = 's1')
    {
        global $DBType, $DBHost, $DBLogin, $DBPassword, $DBName, $DBDebug;

        $this->siteId = $siteId;

        $_SERVER['DOCUMENT_ROOT'] = $this->getDocumentRoot();
        $_SERVER['BX_PERSONAL_ROOT'] = $this->getPersonalRoot();
        $_SERVER['HTTP_X_REAL_IP'] = '127.0.0.1';

        define('FORMAT_DATETIME', $this->getDateTimeFormat());
        define('SITE_ID', $siteId);
        define('LANG', $siteLanguage);
        define('NO_KEEP_STATISTIC', true);
        define('NOT_CHECK_PERMISSIONS', true);
        define('BX_CLUSTER_GROUP', -1);

        $this->disableCacheIBlock();

        require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

        //Подключение автозагрузчика Bitrix
        if (function_exists('\__autoload')) {
            spl_autoload_register('\__autoload');
        }
    }

    /**
     * @return string
     */
    protected function getSiteId()
    {
        if (!$this->siteId) {
            throw new BitrixApiDisableException();
        }

        return $this->siteId;
    }

    /**
     * Выключает кеширование инфоблоков, типов инфоблоков и свойств
     *
     * Решает проблему при создание типа инфоблока и добавление новых инфоблоков в одной миграции
     */
    private function disableCacheIBlock()
    {
        define('CACHED_b_iblock_type', false);
        define('CACHED_b_iblock', false);
        define('CACHED_b_iblock_property_enum', false);
    }
}
