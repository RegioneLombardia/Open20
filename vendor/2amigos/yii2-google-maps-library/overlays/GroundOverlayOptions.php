<?php
/**
 */
namespace dosamigos\google\maps\overlays;

use dosamigos\google\maps\ObjectAbstract;
use dosamigos\google\maps\OptionsTrait;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

/**
 * GroundOverlayOptions
 *
 * Eases the configuration of a GroundOverlay
 *
 * @property boolean clickable Indicates whether this overlay handles mouse events. Defaults to true.
 * @property string map Map on which to display Circle.
 * @property int opacity TThe opacity of the overlay, expressed as a number between 0 and 1. Optional. Defaults to 1.
 *
 * @package dosamigos\google\maps
 */
class GroundOverlayOptions extends ObjectAbstract
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
                'clickable' => null,
                'map' => null,
                'opacity' => null,
            ],
            $this->options
        );

        parent::__construct($config);
    }

    /**
     * @param $value
     */
    public function setMap($value)
    {
        $this->options['map'] = new JsExpression($value);
    }

} 
