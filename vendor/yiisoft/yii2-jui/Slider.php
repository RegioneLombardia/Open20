<?php
/**
 */

namespace yii\jui;

use yii\helpers\Html;

/**
 * Slider renders a slider jQuery UI widget.
 *
 * ```php
 * echo Slider::widget([
 *     'clientOptions' => [
 *         'min' => 1,
 *         'max' => 10,
 *     ],
 * ]);
 * ```
 *
 * @since 2.0
 */
class Slider extends Widget
{
    /**
     * @inheritDoc
     */
    protected $clientEventMap = [
        'change' => 'slidechange',
        'create' => 'slidecreate',
        'slide' => 'slide',
        'start' => 'slidestart',
        'stop' => 'slidestop',
    ];


    /**
     * Executes the widget.
     */
    public function run()
    {
        echo Html::tag('div', '', $this->options);
        $this->registerWidget('slider');
    }
}
