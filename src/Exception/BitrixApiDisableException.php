<?php

namespace Rey\BitrixMigrations;

class BitrixApiDisableException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('You have to init Bitrix Api by calling enableBitrixAPI() method.');
    }
}
