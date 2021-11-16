<?php
/**
 */
namespace dosamigos\google\maps\layers;


/**
 * TemperatureUnit
 *
 * The temperature unit displayed by the weather layer.
 *
 * @package dosamigos\google\maps\layers
 */
class TemperatureUnit
{
    const CELSIUS = 'google.maps.weather.TemperatureUnit.CELSIUS';
    const FAHRENHEIT = 'google.maps.weather.TemperatureUnit.FAHRENHEIT';

    /**
     * Checks whether value is a valid [TemperatureUnit] constant.
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
                static::CELSIUS,
                static::FAHRENHEIT
            ]
        );
    }
} 