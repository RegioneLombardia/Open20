<?php
/**
 */

namespace yii\bootstrap;

/**
 * \yii\bootstrap\Widget is the base class for all bootstrap widgets.
 *
 * @since 2.0
 */
class Widget extends \yii\base\Widget
{
    use BootstrapWidgetTrait;

    /**
     * @var array the HTML attributes for the widget container tag.
     */
    public $options = [];
}
