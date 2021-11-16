<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */
return [
    'log' => [
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
            ],
            [
                'class' => 'yii\log\FileTarget',
                'logFile' => '@runtime/logs/dati-cron.log',
                'levels' => ['error', 'trace'],
                'except' => [
                    'yii\db\*',
                    'yii\web\*',
                    'yii\base\*',
                    'yii\rbac\*',
                ],
                'logVars' => [],
            ],
        ],
    ],
    'urlManager' => [
        'baseUrl' => '/',
        'hostInfo' => 'SITE_URL_HERE',
    ],
    'user' => [
        'class' => 'open20\amos\core\user\AmosUser',
        'identityClass' => 'open20\amos\core\user\User',
        // 'enableSession' => false,
        'enableAutoLogin' => false,
    ],
    'session' => [ // for use session in console application
        'class' => 'yii\web\Session'
    ],
    'workflowSource' => [
        'class' => 'open20\amos\core\workflow\ContentDefaultWorkflowDbSource',
    ],
];
