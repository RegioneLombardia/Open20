<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=db_name',
            'username' => 'db_user',
            'password' => 'db_passwd',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 88000,
            'schemaCache' => 'schemaCache',
            'attributes'=>[
                PDO::ATTR_PERSISTENT => true
            ]
        ],
        'mailer' => [
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
        ],
    ],
];
