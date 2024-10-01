<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

use open20\amos\core\applications\CmsBoot;

defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

require(__DIR__ . '/../../vendor/autoload.php');

$boot = new CmsBoot();
$boot->setBaseYiiFile(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
$boot->run();
