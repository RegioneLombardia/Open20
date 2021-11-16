<?php
/**
 */
namespace dosamigos\google\maps\layers;

/**
 * LabelColor
 *
 * The color of the labels displayed on the weather layer.
 *
 * @package dosamigos\google\maps\layers
 */
class LabelColor
{
    const BLACK = 'google.maps.weather.LabelColor.BLACK';
    const WHITE = 'google.maps.weather.LabelColor.WHITE';

    /**
     * Checks whether a value is a valid [LabelColor] constant.
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
                static::BLACK,
                static::WHITE
            ]
        );
    }
} 