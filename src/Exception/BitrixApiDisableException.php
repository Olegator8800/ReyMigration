<?php

namespace Rey\BitrixMigrations;

class BitrixApiDisableException extends BitrixMigrationException
{
    public function __construct()
    {
        parent::__construct('You have to init Bitrix Api by calling enableBitrixAPI() method.');
    }
}
