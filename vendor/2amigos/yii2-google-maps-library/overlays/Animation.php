<?php
/**
 */
namespace dosamigos\google\maps\overlays;


/**
 * Animation
 *
 * Animations that can be played on a marker. Use the setAnimation method on a [dosamigos\google\maps\overlays\Marker]
 * or the animation option to play an animation.
 *
 * @package dosamigos\google\maps\overlays
 */
class Animation
{
    const DROP = 'google.maps.Animation.DROP';
    const BOUNCE = 'google.maps.Animation.BOUNCE';

    /**
     * Checks whether value is a valid [Animation] constant.
     *
     * @param $value
     *
     * @return bool
     */
    public static function getIsValid($value){
        return in_array(
            $value,
            [
                static::DROP,
                static::BOUNCE
            ]
        );
    }
} 