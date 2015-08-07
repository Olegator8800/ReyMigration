<?php

namespace Rey\BitrixMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration as DoctrineAbstractMigration;
use Rey\BitrixMigrations\Exception\UnexpectedTypeException;

/**
 * Абстрактный класс для миграции
 * Содержит вспомогательные функции для работы с api Битрикса
 */
abstract class AbstractMigration extends DoctrineAbstractMigration
{
    /**
     * @var string
     */
    private $siteId = 's1';

    /**
     * @var string
     */
    private $siteLanguageId = 'ru';

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
     * Установить Id сайта
     *
     * @param string $siteId
     *
     * @throws Rey\BitrixMigrations\Exception\UnexpectedTypeException Если арумент $siteId не строка
     */
    protected function setSiteId($siteId)
    {
        if (!is_string($siteId)) {
            throw new UnexpectedTypeException($siteId, 'string');
        }

        $this->siteId = $siteId;
    }

    /**
     * Получить Id сайта
     *
     * @return string
     */
    protected function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * Установить идентификатор языковой версии сайта
     *
     * @param string $siteLanguageId
     *
     * @throws Rey\BitrixMigrations\Exception\UnexpectedTypeException Если арумент $siteLanguageId не строка
     */
    protected function setSiteLanguageId($siteLanguageId)
    {
        if (!is_string($siteLanguageId)) {
            throw new UnexpectedTypeException($siteLanguageId, 'string');
        }

        $this->siteLanguageId = $siteLanguageId;
    }

    /**
     * Получить идентификатор языковой версии сайта
     *
     * @return string
     */
    protected function getSiteLanguageId()
    {
        return $this->siteLanguageId;
    }

    /**
     * Подключить api Битрикса
     */
    protected function enableBitrixAPI()
    {
        global $DBType, $DBHost, $DBLogin, $DBPassword, $DBName, $DBDebug;

        $_SERVER['DOCUMENT_ROOT'] = $this->getDocumentRoot();
        $_SERVER['BX_PERSONAL_ROOT'] = $this->getPersonalRoot();
        $_SERVER['HTTP_X_REAL_IP'] = '127.0.0.1';

        $siteId = $this->getSiteId();
        $siteLanguageId = $this->getSiteLanguageId();

        define('FORMAT_DATETIME', $this->getDateTimeFormat());
        define('SITE_ID', $siteId);
        define('LANG', $siteLanguageId);
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
