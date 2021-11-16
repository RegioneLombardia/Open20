<?php
/**
 */
namespace dosamigos\google\maps\layers;


use yii\base\InvalidConfigException;

/**
 * PanoramioLayer
 *
 * A PanoramioLayer displays photos from Panoramio as a rendered layer.
 *
 * @package dosamigos\google\maps\layers
 */
class PanoramioLayer extends PanoramioLayerOptions
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
     * Returns the javascript code required to initialize the object
     * 
     * @return string
     */
    public function getJs()
    {
        $name = $this->getName();
        $options = $this->getEncodedOptions();

        return "var {$name} = new google.maps.PanoramioLayer({$options});";
    }
} 
