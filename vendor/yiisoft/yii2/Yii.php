<?php
/**
 */

require __DIR__ . '/BaseYii.php';

/**
 * Yii is a helper class serving common framework functionalities.
 *
 * It extends from [[\yii\BaseYii]] which provides the actual implementation.
 * By writing your own Yii class, you can customize some functionalities of [[\yii\BaseYii]].
 *
 * @since 2.0
 */
class Yii extends \yii\BaseYii
{
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = require __DIR__ . '/classes.php';
Yii::$container = new yii\di\Container();
