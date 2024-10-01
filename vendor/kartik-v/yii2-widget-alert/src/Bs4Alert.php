<?php

/**
 * @package yii2-widgets
 * @subpackage yii2-widget-alert
 * @version 1.1.5
 */

namespace kartik\alert;

use yii\bootstrap4\Alert;

/**
 * Alert widget extends the [[Alert]] widget with an easier configuration and additional styling options including
 * auto fade out.
 *
 * @since 1.0
 */
class Bs4Alert extends Alert implements AlertInterface
{
    use AlertMethodsTrait;
}
