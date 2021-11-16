<?php
/**
 */
namespace dosamigos\google\maps\overlays;


/**
 * StrokePosition
 *
 * Specifies the possible positions of the stroke on a polygon.
 *
 * @package dosamigos\google\maps\overlays
 */
class StrokePosition
{
    const CENTER = 'google.maps.StrokePosition.CENTER';
    const INSIDE = 'google.maps.StrokePosition.INSIDE';
    const OUTSIDE = 'google.maps.StrokePosition.OUTSIDE';

    /**
     * Checks whether the value is a valid [StrokePosition] constant.
     *
     * @param $value
     *
     * @return bool
     */
    public static function getIsValid($value){
        return in_array(
            $value,
            [
                static::CENTER,
                static::INSIDE,
                static::OUTSIDE,
            ]
        );
    }
} 