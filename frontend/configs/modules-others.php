<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

use kartik\datecontrol\Module;

return [
     'userauthfrontend' => [
        'class' => 'amos\userauth\frontend\Module',
        'useAppViewPath' => false, // When enabled the views will be looked up in the @app/views folder, otherwise the views shipped with the module will be used.
        'enableSPID' => true,
        'enableSocial' => false,
        'enableRegister' => false,
        'enableUserPasswordLogin' => false,
    ],
    'admin' => [
        'class' => 'luya\admin\Module',
        'secureLogin' => false, // when enabling secure login, the mail component must be proper configured otherwise the auth token mail will not send.
        'strongPasswordPolicy' => false, // If enabled, the admin user passwords require strength input with special chars, lower, upper, digits and numbers
        'interfaceLanguage' => 'it', // Admin interface default language. Currently supported: en, de, ru, es, fr, ua, it, el, vi, pt, fa
        'apis' => [
			'api-admin-logger' => 'luya\admin\apis\LoggerController',
			'api-admin-common' => 'luya\admin\apis\CommonController',
			'api-admin-remote' => 'luya\admin\apis\RemoteController',
			'api-admin-storage' => 'luya\admin\apis\StorageController',
			'api-admin-menu' => 'luya\admin\apis\MenuController',
			'api-admin-timestamp' => 'luya\admin\apis\TimestampController',
			'api-admin-search' => 'luya\admin\apis\SearchController',
			'api-admin-user' => 'luya\admin\apis\UserController',
			'api-admin-apiuser' => 'luya\admin\apis\ApiUserController',
			'api-admin-group' => 'luya\admin\apis\GroupController',
			'api-admin-lang' => 'luya\admin\apis\LangController',
			'api-admin-effect' => 'luya\admin\apis\EffectController',
			'api-admin-filter' => 'luya\admin\apis\FilterController',
			'api-admin-tag' => 'luya\admin\apis\TagController',
			'api-admin-proxymachine' => 'luya\admin\apis\ProxyMachineController',
			'api-admin-proxybuild' => 'luya\admin\apis\ProxyBuildController',
			'api-admin-proxy' => 'luya\admin\apis\ProxyController',
			'api-admin-config' => 'luya\admin\apis\ConfigController',
			'api-admin-queuelog' => 'luya\admin\apis\QueueLogController',
			'login' => 'app\modules\cms\controllers\LoginController'
		]
    ],
    
    /*
     * Frontend module for the `cms` module.
     */
    'cms' => [
        'class' => 'luya\cms\frontend\Module',
        'overlayToolbar' => false,
        'contentCompression' => true,
        'controllerMap' => [
                'default' => 'app\modules\cms\base\DefaultController'
        ],
    ],
    
    /*
     * Admin module for the `cms` module.
     */
    'cmsadmin' => [
        'class' => 'app\modules\cms\admin\Module',
        'hiddenBlocks' => [],
        'blockVariations' => [],
    ],
    
    'crawler' => [
        'class' => 'open2\crawler\frontend\Module',
        'baseUrl' => 'https://www.openinnovation.regione.lombardia.it',
        'statisticRecipients' => ['stefano.cavazzini@open20.it'],
    ],
    'crawleradmin' => 'open2\crawler\admin\Module',
    'cmsapi' => [
        'class' => 'app\modules\cmsapi\frontend\Module',
        'css_layoutsection_with_image' => 'hero-banner'
    ],
    'cmscartapi' => 'app\modules\cmsapi\cart\Module',
    /* 'event' => [
        'class' => 'johnnymcweed\event\frontend\Module',
		//'useAppViewPath' => true, 
		'controllerMap' => [
            'deafult' => [
                'class' => 'app\modules\event\frontend\controllers\EventController',
            ],
        ],
    ],
	'eventadmin' => 'johnnymcweed\event\admin\Module', */
    
    'backendobjects' => [
        'class' => 'app\modules\backendobjects\frontend\Module',
        'modulesEnabled' => [
            'open20\amos\news\AmosNews',
            'open20\amos\faq\AmosFaq',
            'open20\amos\events\AmosEvents',
            'open20\amos\community\AmosCommunity',
            'open20\amos\discussioni\AmosDiscussioni',
            'backend\modules\landings\Module',
        ],
    //'useAppViewPath' => true, // When enabled the views will be looked up in the @app/views folder, otherwise the views shipped with the module will be used.
    ],
    'sitemap' => [
        'class' => 'app\modules\sitemap\frontend\Module',
    ],
//    'social' => [
//        // the module class
//        'class' => 'kartik\social\Module',
//        // the global settings for the google analytic plugin widget
//        'googleAnalytics' => [
//            'id' => 'UA-154141373-1',
//            'domain' => 'eventi.regione.lombardia.it',
//        ],
//    ],
    'seo' => [
        'class' => 'app\modules\seo\frontend\Module',
    ],
    'uikit' => [
        'class' => 'app\modules\uikit\Module',
    ],
    'adminuikit' => [
        'class' => 'app\modules\uikit\admin\Module',
    ],
    'translatemanager' => [
        'class' => 'lajax\translatemanager\Module',
        'root' => [
            '@frontend',
            '@app', // The root directory of the project scan.
            '@vendor/open20/',
            '@vendor/luyadev/',
        ],
        'scanRootParentDirectory' => true, // Whether scan the defined `root` parent directory, or the folder itself.
        // IMPORTANT: for detailed instructions read the chapter about root configuration.
        'layout' => 'language', // Name of the used layout. If using own layout use 'null'.
        'allowedIPs' => ['*'], // IP addresses from which the translation interface is accessible.
        'roles' => ['ADMIN'], // For setting access levels to the translating interface.
        'tmpDir' => '@runtime', // Writable directory for the client-side temporary language files.
        // IMPORTANT: must be identical for all applications (the AssetsManager serves the JavaScript files containing language elements from this directory).
        'phpTranslators' => [               // list of the php function for translating messages.
            '::t',
            '::tText',
            '::tHtml',
        ],
        'jsTranslators' => ['lajax.t'], // list of the js function for translating messages.
        'patterns' => ['*.js', '*.php'], // list of file extensions that contain language elements.
        'ignoredCategories' => ['yii'], // these categories won't be included in the language database.
        'ignoredItems' => ['config'], // these files will not be processed.
        'scanTimeLimit' => null, // increase to prevent "Maximum execution time" errors, if null the default max_execution_time will be used
        'searchEmptyCommand' => '!', // the search string to enter in the 'Translation' search field to find not yet translated items, set to null to disable this feature
        'defaultExportStatus' => 1, // the default selection of languages to export, set to 0 to select all languages by default
        'defaultExportFormat' => 'json', // the default format for export, can be 'json' or 'xml'
        'tables' => [                       // Properties of individual tables
            [
                'connection' => 'db', // connection identifier
                'table' => '{{%language}}', // table name
                'columns' => ['name', 'name_ascii'], // names of multilingual fields
                'category' => 'database-table-name', // the category is the database table name
            ]
        ],
    ],
    
    'datecontrol' => [
        'class' => 'kartik\datecontrol\Module',
        'displaySettings' => [
            Module::FORMAT_DATE => 'dd-MM-yyyy',
            Module::FORMAT_TIME => 'HH:mm',
            Module::FORMAT_DATETIME => 'php:d-m-Y H:i',
        ],
        // format settings for saving each date attribute (PHP format example)
        'saveSettings' => [
            Module::FORMAT_DATE => 'php:Y-m-d', // saves as unix timestamp
            Module::FORMAT_TIME => 'php:H:i:s',
            Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
        ],
        // set your display timezone
        'displayTimezone' => 'Europe/Rome',
        // set your timezone for date saved to db
        'saveTimezone' => 'Europe/Rome',
        'autoWidgetSettings' => [
            Module::FORMAT_DATE => ['type' => 2, 'pluginOptions' => ['autoclose' => true]], // example
        //Module::FORMAT_TIME => 'php:H:i:s',
        //Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
        ],
        'widgetSettings' => [
            Module::FORMAT_DATE => [
                'class' => 'yii\widgets\MaskedInput', // example
                'options' => [
                    'mask' => '99/99/9999',
                    'options' => ['class' => 'form-control'],
                ]
            ]
        ]
    ],
];