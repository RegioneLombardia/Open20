<?php
/**
 */

namespace yii\bootstrap4;

/**
 * \yii\bootstrap4\Widget is the base class for all bootstrap widgets.
 *
 */
class Widget extends \yii\base\Widget
{
    use BootstrapWidgetTrait;

    /**
     * @var array the HTML attributes for the widget container tag.
     */
    public $options = [];
}
