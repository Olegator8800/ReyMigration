<?php

namespace Rey\BitrixMigrations\Exception;

/**
 * UnexpectedTypeException called when passed type does not match
 *
 * Symfony Like
 */
class UnexpectedTypeException extends InvalidArgumentException
{
    public function __construct($value, $expectedType)
    {
        parent::__construct(sprintf('Expected argument must be "%s" type, "%s" given', $expectedType, is_object($value)?get_class($value):gettype($value)));
    }
}
