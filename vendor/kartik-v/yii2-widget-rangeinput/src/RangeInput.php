<?php

/**
 * @package yii2-widgets
 * @subpackage yii2-widget-rangeinput
 * @version 1.0.2
 */

namespace kartik\range;

use Yii;
use yii\helpers\Html;

/**
 * RangeInput widget is an enhanced widget encapsulating the HTML 5 range input.
 *
 * @since 1.0
 */
class RangeInput extends \kartik\base\Html5Input
{
    /**
     * @var string the HTML5 input type
     */
    public $type = 'range';

    /**
     * @var string the orientation of the range input. If set to `vertical` will orient the range
     * sliders vertically - else will display the sliders horizontally.
     */
    public $orientation;

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->orientation == 'vertical') {
            Html::addCssClass($this->containerOptions, 'kv-range-vertical');
            $this->html5Options['orient'] = 'vertical';
        }
        return parent::run();
    }
}
