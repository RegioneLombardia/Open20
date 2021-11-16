<?php
/**
 */
namespace extensions\google\controls\maps;


use dosamigos\google\maps\ObjectAbstract;
use dosamigos\google\maps\OptionsTrait;
use yii\helpers\ArrayHelper;

/**
 * OverviewMapControlOptions
 *
 * Options for the rendering of the Overview Map control.
 *
 * For further information please visit its
 * [documentation](https://developers.google.com/maps/documentation/javascript/reference#OverviewMapControlOptions) at
 * Google.
 *
 * @property boolean opened Whether the control should display in opened mode or collapsed (minimized) mode. By default,
 * the control is closed.
 *
 * @package extensions\google\controls\maps
 */
class OverviewMapControlOptions extends ObjectAbstract
{
    use OptionsTrait;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->options = ArrayHelper::merge(['opened' => null], $this->options);
    }

} 