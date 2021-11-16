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
    'errorHandler' => [
        'errorAction' => 'error/error',
//        'class' => 'frontend\controllers\ErrorController',
    ],
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
                'logVars' => ['_SERVER'],
            ],
        ],
    ],
    'request' => [
        'csrfParam' => '_csrf-frontend',
        'enableCookieValidation' => false,
    ],
    'session' => [
        // this is the name of the session cookie used for login on the frontend
        'name' => 'advanced-frontend',
    ],
    /*
      'urlManager' => [
      'enablePrettyUrl' => true,
      'showScriptName' => false,
      'rules' => [
      ],
      ],
     */
    'user' => [
        'identityClass' => 'common\models\User',
        'enableAutoLogin' => true,
        'identityCookie' => [
            'name' => '_identity-frontend',
            'httpOnly' => true,
            'secure' => true
        ],
    ],
];
