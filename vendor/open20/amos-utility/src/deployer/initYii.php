<?php
//Autoloader
use yii\console\Application;
use yii\helpers\ArrayHelper;

require(__DIR__ . '/../../../../autoload.php');
require(__DIR__ . '/../../../../yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../../../../common/config/bootstrap.php');
require(__DIR__ . '/../../../../../console/config/bootstrap.php');

// Ensure we get report on all possible php errors
error_reporting(-1);

// Define debug constants
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

// Init the console application
$config = ArrayHelper::merge(
    require(__DIR__ . '/../../../../../common/config/main.php'),
    require(__DIR__ . '/../../../../../common/config/main-local.php'),
    require(__DIR__ . '/../../../../../console/config/main.php'),
    require(__DIR__ . '/../../../../../console/config/main-local.php')
);

//Init Yii Application to get access to the framework
\Yii::$app = new Application($config);

//Router params
$yiiRouter = Yii::$app->request->resolve();