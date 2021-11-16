<?php
/**
 */
namespace dosamigos\google\maps\layers;


use yii\base\InvalidConfigException;

/**
 * WeatherLayer
 *
 * Renders a layer that displays weather icons.
 *
 * @package dosamigos\google\maps\layers
 */
class WeatherLayer extends WeatherLayerOptions
{
    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if ($this->map == null) {
            throw new InvalidConfigException('"map" cannot be null');
        }
    }

    /**
     * Returns the required javascript code to initialize the object.
     *
     * @return string
     */
    public function getJs()
    {
        $name = $this->getName();
        $options = $this->getEncodedOptions();

        return "var {$name} = new google.maps.weather.WeatherLayer({$options});";
    }
} 