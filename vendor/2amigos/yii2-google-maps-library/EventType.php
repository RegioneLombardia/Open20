<?php
/**
 */
namespace dosamigos\google\maps;

/**
 * EventType
 *
 * Describes the different valid event types supported.
 *
 * @package dosamigos\google\maps
 */
class EventType
{
    const DEFAULT_EVENT = 'DEFAULT';
    const DEFAULT_ONCE = 'DEFAULT_ONCE';
    const DOM = 'DOM';
    const DOM_ONCE = 'DOM_ONCE';

    /**
     * Checks whether value is a valid [EventType] constant.
     *
     * @param $value
     *
     * @return bool
     */
    public static function getIsValid($value)
    {
        return in_array(
            $value,
            [
                static::DEFAULT_EVENT,
                static::DEFAULT_ONCE,
                static::DOM,
                static::DOM_ONCE
            ]
        );
    }
} 