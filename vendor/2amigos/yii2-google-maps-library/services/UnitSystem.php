<?php
/**
 */
namespace dosamigos\google\maps\services;


/**
 * UnitSystem
 *
 * The valid unit systems that can be specified in a DirectionsRequest.
 *
 * @package dosamigos\google\maps\services
 */
class UnitSystem
{
    const IMPERIAL = 'google.maps.UnitSystem.IMPERIAL';
    const METRIC = 'google.maps.UnitSystem.METRIC';

    /**
     * Checks whether value is a valid [UnitSystem] constant.
     *
     * @param $value
     *
     * @return bool
     */
    public static function getIsValid($value){
        return in_array(
            $value,
            [
                static::METRIC,
                static::IMPERIAL
            ]
        );
    }
} 