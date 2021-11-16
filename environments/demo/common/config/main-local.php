<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

/*---------- begin: BOOTSTRAP --------*/
/*---------- end: BOOTSTRAP --------*/

/*---------- begin: COMPONENTS AMOS --------*/
/*---------- end: COMPONENTS AMOS --------*/

/*---------- begin: COMPONENTS OTHERS --------*/
$components['db'] = [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=dbname',
            'username' => 'dbuser',
            'password' => 'dbpasswd',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 88000,
            'schemaCache' => 'schemaCache',
            'attributes'=>[
                PDO::ATTR_PERSISTENT => true
            ]
        ];
$components['mailer'] = [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'email_host',
                'username' => 'email_user',
                'password' => 'email_passwd',
                'port' => 'email_port',
                //'encryption' => 'tls',
            ],
        ];
/*---------- end: COMPONENTS OTHERS --------*/

/*---------- begin: MODULES AMOS --------*/
$modules['admin'] = [
    'fieldsConfigurations' => [
        'boxes' => [
            'box_facilitatori' => ['form' => true, 'view' => true],
            'box_privacy' => ['form' => true, 'view' => true],
        ],
        'fields' => [
            'facilitatore_id' => ['form' => true, 'view' => true, 'referToBox' => 'box_facilitatori'],
            'privacy' => ['form' => true, 'view' => true, 'referToBox' => 'box_privacy'],
        ]
    ]
];
$modules['community'] = [
    'class' => 'open20\amos\community\AmosCommunity',
];
$modules['cwh'] = [
    'class' => 'open20\amos\cwh\AmosCwh',
    'rbacEnabled' => false
];
$modules['tag'] = [
    'class' => 'open20\amos\tag\AmosTag',
];
/*---------- end: MODULES AMOS --------*/

/*---------- begin: MODULES OTHERS --------*/
/*---------- end: MODULES OTHERS --------*/

$config = [
//    'bootstrap' => $bootstrap,
//    'components' => $components,
    'modules' => $modules,
];

return $config;
