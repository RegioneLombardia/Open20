<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    httpdocs
 * @category   CategoryName
 */

return [
    'audit' => [
        'class' => 'open20\amos\audit\Audit',
        'db' => 'db',
        'accessRoles' => ['ADMIN'],
        'batchSave' => false,
        'ignoreActions' => [
            'debug/*',
            'ajax/*',
            'audit/*',
            'site/login',
            'admin/user-profile/cambia-password',
            'site/inserisci-dati-autenticazione',
            'site/insert-auth-data',
        ],
        //This avoid all post data in audit
        /*'panels' => [
            'audit/request' => [
                'ignoreKeys' => ['POST', 'requestBody'],
            ],
        ],*/
    ],
    'redactor' => [
        'class' => 'yii\redactor\RedactorModule',
        'uploadDir' => '@webroot/uploadfiles/redactor',
        'uploadUrl' => '@web/uploadfiles/redactor',
        'imageAllowExtensions' => ['jpg', 'png', 'gif']
    ],
    'translatemanager' => [
        'class' => 'lajax\translatemanager\Module',
        'root' => [
            '@frontend',
            '@app', // The root directory of the project scan.
            '@vendor/open20/',
        ],
        'scanRootParentDirectory' => true, // Whether scan the defined `root` parent directory, or the folder itself.
        // IMPORTANT: for detailed instructions read the chapter about root configuration.
        'layout' => 'language',             // Name of the used layout. If using own layout use 'null'.
        'allowedIPs' => ['*'],      // IP addresses from which the translation interface is accessible.
        'roles' => ['ADMIN'],               // For setting access levels to the translating interface.
        'tmpDir' => '@runtime',             // Writable directory for the client-side temporary language files.
        // IMPORTANT: must be identical for all applications (the AssetsManager serves the JavaScript files containing language elements from this directory).
        'phpTranslators' => [               // list of the php function for translating messages.
            '::t',
            '::tText',
            '::tHtml',
        ],
        'jsTranslators' => ['lajax.t'],     // list of the js function for translating messages.
        'patterns' => ['*.js', '*.php'],    // list of file extensions that contain language elements.
        'ignoredCategories' => ['yii'],     // these categories won't be included in the language database.
        'ignoredItems' => ['config'],       // these files will not be processed.
        'scanTimeLimit' => null,            // increase to prevent "Maximum execution time" errors, if null the default max_execution_time will be used
        'searchEmptyCommand' => '!',        // the search string to enter in the 'Translation' search field to find not yet translated items, set to null to disable this feature
        'defaultExportStatus' => 1,         // the default selection of languages to export, set to 0 to select all languages by default
        'defaultExportFormat' => 'json',    // the default format for export, can be 'json' or 'xml'
        'tables' => [                       // Properties of individual tables
            [
                'connection' => 'db',       // connection identifier
                'table' => '{{%language}}',         // table name
                'columns' => ['name', 'name_ascii'],// names of multilingual fields
                'category' => 'database-table-name',// the category is the database table name
            ]
        ],
    ],
    'schema' => [
        'class' => 'simialbi\yii2\schemaorg\Module',
        //'autoCreate' => false,
        //'autoRender' => false
    ]
];
