<?php
/**
 */
namespace dosamigos\google\maps\layers;

use dosamigos\google\maps\ObjectAbstract;
use dosamigos\google\maps\OptionsTrait;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

/**
 * KmlLayerOptions
 *
 * This object defines the properties that can be set on a [KmlLayer] object.
 *
 * @property boolean clickable If true, the layer receives mouse events. Default value is true.
 * @property string map The map on which to display the layer.
 * @property boolean preserveViewport By default, the input map is centered and zoomed to the bounding box of the
 * contents of the layer. If this option is set to true, the viewport is left unchanged, unless the map's center and
 * zoom were never set.
 * @property boolean screenOverlays Whether to render the screen overlays. Default true.
 * @property boolean suppressInfoWindows Suppress the rendering of info windows when layer features are clicked.
 * @property string url The URL of the KML document to display.
 * @property int zIndex The z-index of the layer.
 *
 * @package dosamigos\google\maps\layers
 */
class KmlLayerOptions extends ObjectAbstract
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
                'preserveViewPort' => null,
                'screenOverlays' => null,
                'suppressInfoWindows' => null,
                'url' => null,
                'zIndex' => null
            ],
            $this->options
        );

        parent::__construct($config);
    }

    /**
     * Sets the map, making sure is not going to be converted into a js string.
     *
     * @param $value
     */
    public function setMap($value)
    {
        $this->options['map'] = new JsExpression($value);
    }
}