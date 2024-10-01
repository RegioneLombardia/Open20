<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$modules = require(__DIR__ . '/modules.php');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$bootstrap = array_merge(
    [],
    require(__DIR__ . '/bootstrap-extra.php')
);

//Installation file
if (file_exists(__DIR__ . '/install.php')) {
    $bootstrap = array_merge(
        include(__DIR__ . '/install.php'),
        $bootstrap
    );
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$components = require(__DIR__ . '/components.php');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$params = array_merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
return yii\helpers\ArrayHelper::merge([
    'aliases' => [
        '@file' => dirname(__DIR__),
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'language' => 'it-IT',
    'timeZone' => 'Europe/Rome',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'name' => 'Amos Basic Template',
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    'bootstrap' => $bootstrap,
    'components' => $components,
    'modules' => $modules,
    'params' => $params,
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
],require(__DIR__.'/main-local.php'));
