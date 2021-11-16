<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
    $config['modules']['gii']['generators'] = [
        'giiamos-crud' => ['class' => 'open20\amos\core\giiamos\crud\Generator'],
        'giiamos-model' => ['class' => 'open20\amos\core\giiamos\model\Generator'],
        'giiamos-widgets' => ['class' => 'open20\amos\core\giiamos\widgets\Generator'],
    ];

    $config['modules']['gii']['controllerNamespace'] = 'open20\amos\core\giiamos\controllers';

    $config['modules']['gii']['allowedIPs'] = [
        '127.0.0.1',
    ];
}

return $config;
