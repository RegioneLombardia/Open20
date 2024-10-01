<?php
require(__DIR__ . '/defines.php');

$commonGeneral = require(__DIR__ . '/../../common/config/main.php');
$commonLocal = require(__DIR__ . '/../../common/config/main-local.php');

$common = yii\helpers\ArrayHelper::merge($commonGeneral, $commonLocal);


$modules = yii\helpers\ArrayHelper::merge(
    $common['modules'], require(__DIR__ . '/modules.php'));

$components = yii\helpers\ArrayHelper::merge(
    $common ['components'], require(__DIR__ . '/components.php'));

$bootstrap = yii\helpers\ArrayHelper::merge(
    $common ['bootstrap'],require(__DIR__ . '/bootstrap.php'));


$params = yii\helpers\ArrayHelper::merge(
    $common['params'],
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

$config = [
    //'controllerNamespace' => 'frontend\controllers',
    'locales' => [
        'it' => 'it-IT',
        'en' => 'en-GB',
    ],
    'timeZone' => 'Europe/Rome',
    'vendorPath' => dirname(__DIR__) . '/../vendor',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@common' => dirname(__DIR__) . '/../common',
        '@open20' => '@vendor/open20',
        '@frontend' => dirname(__DIR__),
        '@npm' => '@vendor/npm-asset',
        '@open20' => '@vendor/open20',
        '@amos' => '@vendor/amos',
    ],
    'name' => 'Open 2.0',
    /*
     * For best interoperability it is recommend to use only alphanumeric characters when specifying an application ID.
     */
    'id' => 'myproject',
    /*
     * The name of your site, will be display on the login screen
     */
    'siteTitle' => 'Amos CMS',
    /*
     * Let the application know which module should be executed by default (if no url is set). This module must be included
     * in the modules section. In the most cases you are using the cms as default handler for your website. But the concept
     * of LUYA is also that you can use a website without the CMS module!
     */
    'defaultRoute' => 'cms',
    'consoleBaseUrl' => '/',
    'consoleHostInfo' => 'http://localhost',
    /*
     * Define the basePath of the project (Yii Configration Setup)
     */
    'basePath' => dirname(__DIR__),
    'modules' => $modules,
    'components' => $components,
    'bootstrap' => $bootstrap,
    'params' => $params,
    'controllerMap' => [
        'elastic' => [
            'class' => 'open20\elasticsearch\commands\RebuildIndexController',
        ],
        'language' => [
            'class' => 'open20\amos\core\commands\LanguageSourceController',
        ],
    ],
];

if (YII_DEBUG) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = ['class' => 'yii\debug\Module', 'allowedIPs' => ['*']];

    $config['bootstrap'][] = 'gii';
    $config['modules'] ['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'] // adjust this to your needs
    ];
    $config['modules']['gii']['generators'] = [
        'giiamos-crud' => ['class' => 'open20\amos\core\giiamos\crud\Generator'],
        'giiamos-model' => ['class' => 'open20\amos\core\giiamos\model\Generator'],
        'giiamos-widgets' => ['class' => 'open20\amos\core\giiamos\widgets\Generator'],
    ];
}

return \yii\helpers\ArrayHelper::merge($config, require('env-local.php'));

