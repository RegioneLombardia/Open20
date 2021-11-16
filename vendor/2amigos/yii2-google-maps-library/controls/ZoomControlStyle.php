<?php
/**
 */
namespace dosamigos\google\maps\controls;


/**
 * ZoomControlStyle
 *
 * Identifiers for the zoom control.
 *
 * For further information please visit its
 * [documentation](https://developers.google.com/maps/documentation/javascript/reference#ZoomControlStyle) at
 * Google.
 *
 * @package dosamigos\google\maps\controls
 */
class ZoomControlStyle
{
    const DEFAULT_STYLE = 'google.maps.ZoomControlStyle.DEFAULT';
    const LARGE = 'google.maps.ZoomControlStyle.LARGE';
    const SMALL = 'google.maps.ZoomControlStyle.SMALL';

    /**
     * @param $value
     *
     * @return bool
     */
    public static function getIsValid($value){
        return in_array(
            $value,
            [
                static::DEFAULT_STYLE,
                static::LARGE,
                static::SMALL
            ]
        );
    }
} 