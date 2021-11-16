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
    'assetManager' => [
        'appendTimestamp' => true,
        'forceCopy' => false,
        'hashCallback' => function ($path) {
            return hash('md4', $path);
        },
        'converter' => [
            'class'=>'nizsheanez\assetConverter\Converter',
            'force'=> false,
            'destinationDir' => 'compiled', //at which folder of @webroot put compiled files
            'parsers' => [
                'sass' => [ // file extension to parse
                    'class' => 'nizsheanez\assetConverter\Sass',
                    'output' => 'css', // parsed output file type
                    'options' => [
                        'cachePath' => '@app/runtime/cache/sass-parser' // optional options
                    ],
                ],
                'scss' => [ // file extension to parse
                    'class' => 'open20\amos\core\converters\Scss',
                    'output' => 'css', // parsed output file type
                    'options' => [
                        'importPaths' => [
                            '@vendor/open20/design/src/assets',
                            '@common/web'
                        ], // import paths, you may use path alias here,
                        // e.g., `['@path/to/dir', '@path/to/dir1', ...]`
                        'enableCompass' => false,
                        'lineComments' => false, // if true — compiler will place line numbers in your compiled output
                        'outputStyle' => 'expanded', // May be `compressed`, `crunched`, `expanded` or `nested`,
                        // see more at http://sass-lang.com/documentation/file.SASS_REFERENCE.html#output_style
                    ],
                ],
                'less' => [ // file extension to parse
                    'class' => 'nizsheanez\assetConverter\Less',
                    'output' => 'css', // parsed output file type
                    'options' => [
                        'importDirs' => [], // import paths, you may use path alias here ex. '@app/assets/common/less'
                        'auto' => true, // optional options
                    ]
                ]
            ]
        ],
    ],
    'authManager' => [
        'class' => 'open20\amos\core\rbac\DbManagerCached',
        'cache' => 'rbacCache'
    ],
    'breadcrumbcache' => [
        'class' => 'yii\caching\FileCache',
        'cachePath' => '@runtime/breadcrumbcache'
    ],
    'cache' => [
        //'class' => 'yii\caching\FileCache',
        'class' => 'yii\caching\DummyCache',
    ],
    'composition' => [
        'hidden' => false, // no languages in your url (most case for pages which are not multi lingual)
        'pattern' => '<langShortCode:[a-z]{2}>',
        'default' => ['langShortCode' => 'it'], // the default language for the composition should match your default language shortCode in the language table.
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
     'errorHandler' => [
         'class' => 'app\modules\cms\error\ErrorHandler',
     ],
    'i18n' => [
        'translations' => [
            '*' => [
                'class' => 'open20\amos\core\i18n\MessageSource',
                'db' => 'db',
                'sourceLanguage' => 'it-IT', // Developer language
                'sourceMessageTable' => '{{%language_source}}',
                'messageTable' => '{{%language_translate}}',
                'cachingDuration' => 86400,
                'enableCaching' => false,
                'forceTranslation' => true,
                'autoUpdate' => true,
                'extraCategoryPaths' => [
                    'amoscore' => '@vendor/open20/amos-core/i18n',
                    'amos' => '@common/translation/amos/i18n',
                    'amosplatform' => '@common/translation/amosplatform/i18n',
                    'amosapp' => '@common/translation/amosapp/i18n',
                    'cruds' => '@common/translation/cruds/i18n',
                    'giiamos' => '@common/translation/giiamos/i18n',
                    'javascript' => '@common/translation/javascript/i18n',
                    'site' => '@common/translation/site/i18n',
                    'wizard' => '@common/translation/wizard/i18n',
                ],
            ],
        ],
    ],
    'rbacCache' => [
        'class' => 'yii\caching\FileCache',
        'cachePath' => '@runtime/rbacCache'
    ],
    /* 'request' => [
      'csrfParam' => '_csrf-frontend',
      ], */
    'schemaCache' => [
        'class' => 'yii\caching\FileCache',
        'cachePath' => '@runtime/schemaCache'
    ],
    /* 'session' => [
      'class' => 'yii\web\CacheSession',
      // this is the name of the session cookie used for login on the frontend
      'name' => 'advanced-frontend',
      ], */
    'storage' => [
        'class' => 'app\modules\cms\storage\AmosFileSystem'
    ],
    'translateCache' => [
        'class' => 'yii\caching\FileCache',
        'cachePath' => '@runtime/translateCache'
    ],
    'translatemanager' => [
        'class' => 'lajax\translatemanager\Component'
    ],
    'urlManager' => [
        'class' => 'app\modules\cms\base\UrlManager',
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [
            'site/login' => '/',
            'site/login-social' => 'site/login-social',
            'site/social-sign-in' => 'site/social-sign-in',
            'site/logout' => 'site/logout',
            'site/rating' => 'site/rating',
            'site/forgot-password' => 'site/forgot-password',
            'admin/security/forgot-password' => 'site/forgot-password',
            'admin/security/insert-auth-data' => 'site/insert-auth-data',
            'admin/security/register' => 'site/register',
            'amosadmin/security/register' => 'site/register',
            // 'preference-userprofile/preference/app-end-point' => 'preference-userprofile/preference/app-end-point',
        ]
    ],
    'view' => [
        'class' => 'app\modules\cms\base\CmsView',
        'theme' => [
            'pathMap' => [
                '@vendor/open20/amos-admin/src/mail/user/' => '@app/views/site',
                '@vendor/amos/cms-module-userauth/src/frontend/views/default' => '@app/views/site/default',
            ],
        ],
    ],
    'adminuser' => [
        'class' => 'app\modules\cms\components\AdminUser',
        'defaultLanguage' => 'it',
        'enableAutoLogin' => true,
    /* 'identityCookie' => [
      'name' => '_identity-luya',
      'httpOnly' => true,
      'secure' => true,
      'domain' => ".demotestwip.it",
      ], */
    ],
];
