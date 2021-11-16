<?php
/**
 */
namespace dosamigos\google\maps\services;

use dosamigos\google\maps\ObjectAbstract;
use dosamigos\google\maps\OptionsTrait;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * StreetViewPov
 *
 * A point of view object which specifies the camera's orientation at the Street View panorama's position. The point of
 * view is defined as heading and pitch.
 *
 * @property int heading The camera heading in degrees relative to true north. True north is 0°, east is 90°, south
 * is 180°, west is 270°.
 * @property int pitch The camera pitch in degrees, relative to the street view vehicle. Ranges from 90°
 * (directly upwards) to -90° (directly downwards).
 *
 * @package dosamigos\google\maps
 */
class StreetViewPov extends ObjectAbstract
{
    use OptionsTrait;

    /**
     * @inheritdoc
     * @param array $config
     */
    public function __construct($config = [])
    {

        $this->options = ArrayHelper::merge(
            [
                'heading' => null,
                'pitch' => null,
            ],
            $this->options
        );

        parent::__construct($config);
    }

} 