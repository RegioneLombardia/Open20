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
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=database',
            'username' => 'dbuser',
            'password' => 'dbpasswd',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 88000,
            'schemaCache' => 'schemaCache',
            //'attributes' => [PDO::ATTR_CASE => PDO::CASE_LOWER],//Enable on MySQL 8.X
            
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
            'messageConfig' => [
                'priority' => 3
            ]
        ],
    ],
];

return $config;

