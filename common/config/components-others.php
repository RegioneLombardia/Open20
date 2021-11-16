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
                            // finchè non abbiamo design in BE, queste righe devono esser commentate e gestite le due configurazioni separate BE e FE
                            // '@vendor/open20/design/src/assets',
                            // '@common/web',
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
    'cache' => [
        'class' => 'yii\caching\FileCache',
    ],
    'breadcrumbcache' => [
        'class' => 'yii\caching\FileCache',
        'cachePath' => '@runtime/breadcrumbcache'
    ],
    'translateCache'  => [
        'class' => 'yii\caching\FileCache',
        'cachePath' => '@runtime/translateCache'
    ],
    'schemaCache' => [
        'class' => 'yii\caching\FileCache',
        'cachePath' => '@runtime/schemaCache'
    ],
    'rbacCache' => [
        'class' => 'yii\caching\FileCache',
        'cachePath' => '@runtime/rbacCache'
    ],
    'eventSequence' => [
        'class' => '\raoul2000\workflow\events\BasicEventSequence',
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
                'enableCaching' => true,
                'forceTranslation' => true,
                'autoUpdate' => false,
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
                    'preferencelandings' => '@backend/modules/landings/i18n'
                ],
            ],
        ],
    ],
    'translatemanager' => [
        'class' => 'lajax\translatemanager\Component'
    ],
    'urlManager' => [
        'class' => 'yii\web\UrlManager',
        // Disable index.php
        'showScriptName' => false,
        // Disable r= routes
        'enablePrettyUrl' => true,
        'rules' => array(
            '<module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>] => <module>/<controller>/<action>',
        ),
    ],
];