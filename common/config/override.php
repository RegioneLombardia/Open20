<?php
return [
    'params' => [
	'platform' => [
		'frontendUrl' => 'https://front-preference-agid.stage.demotestwip.it',
		'backendUrl' => 'https://preference-agid.stage.demotestwip.it',
    	],
    ],
    'components' => [
        'dbstats' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=deployer_platform_611',
            'username' => 'deployer',
            'password' => '(/H&t8g)',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 88000,
            'schemaCache' => 'schemaCache',
        ],
    'urlManager' => [
            'hostInfo' => 'https://front-preference-agid.stage.demotestwip.it',
        ],
    ]
];
