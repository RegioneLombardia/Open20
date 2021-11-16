<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\mobile\bridge
 * @category   CategoryName
 */
// Server API Key (you can get it here: https://firebase.google.com/docs/server/setup#prerequisites)
$firebaseApiKey = isset(Yii::$app->params['firebaseApiKey']) ? Yii::$app->params['firebaseApiKey'] : null;

return [
    'modules' => [
        'v1' => [
            'class' => \open20\amos\mobile\bridge\modules\v1\V1::className()
        ]
    ],
    'components' => [
        'user' => [
            'class' => 'open20\amos\core\user\AmosUser',
            'identityClass' => 'open20\amos\mobile\bridge\modules\v1\models\User',
            'enableAutoLogin' => true,
        ],
        'request' => [
            'class' => \yii\web\Request::className(),
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'fcm' => [
            'class' => 'understeam\fcm\Client',
            'apiKey' => $firebaseApiKey,
        ],
        'expo' => [
            'class' => 'open2\expo\ExpoPush'
        ],
    ]
];
