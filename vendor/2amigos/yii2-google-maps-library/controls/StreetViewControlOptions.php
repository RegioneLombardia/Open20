<?php
/**
 */
namespace dosamigos\google\maps\controls;

use dosamigos\google\maps\ObjectAbstract;
use dosamigos\google\maps\OptionsTrait;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * StreetViewControlOptions
 *
 * Options for the rendering of the Street View pegman control on the map.
 *
 * For further information please visit its
 * [documentation](https://developers.google.com/maps/documentation/javascript/reference#StreetViewControlOptions) at
 * Google.
 *
 * @property string position. Position id. Used to specify the position of the control on the map. The default position
 * is embedded within the navigation (zoom and pan) controls. If this position is empty or the same as that specified
 * in the zoomControlOptions or panControlOptions, the Street View control will be displayed as part of the navigation
 * controls. Otherwise, it will be displayed separately.
 *
 * @package dosamigos\google\maps
 */
class StreetViewControlOptions extends ObjectAbstract
{
    use OptionsTrait;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->options = ArrayHelper::merge(
            [
                'position' => null,
            ],
            $this->options
        );
    }

    /**
     * Sets the position and makes sure is a valid [ControlPosition] value.
     *
     * @param string $value
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function setPosition($value)
    {
        if (!ControlPosition::getIsValid($value)) {
            throw new InvalidConfigException('Unknown "position" value');
        }
        $this->options['position'] = $value;
    }
} 