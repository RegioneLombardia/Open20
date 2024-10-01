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
    'workflowSource' => [
        'class' => 'open20\amos\core\workflow\ContentDefaultWorkflowDbSource',
    ],
    'imageUtility' => [
        'class' => 'open20\amos\core\components\ImageUtility',
    ],
    'formatter' => [
        'class' => 'open20\amos\core\formatter\Formatter',
        'dateFormat' => 'php:d/m/Y',
        'datetimeFormat' => 'php:d/m/Y H:i',
        'timeFormat' => 'php:H:i',
        'defaultTimeZone' => 'Europe/Rome',
        'timeZone' => 'Europe/Rome',
        'locale' => 'it-IT',
        'thousandSeparator' => '.',
        'decimalSeparator' => ',',
    ],
    'translatemanager' => [
        'class' => 'lajax\translatemanager\Component'
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
                'autoUpdate' => true,
                'extraCategoryPaths' => [
                    'amoscore' => '@vendor/open20/amos-core/i18n',
                    'amosamosadmin' => '@vendor/open20/amos-admin/src/i18n',
                    'amosnews' => '@common/translation/amosnews/i18n',
                    'amosattachments' => '@common/translation/amosattachments/i18n',
                    'amos' => '@common/translation/amos/i18n',
                    'amosplatform' => '@common/translation/amosplatform/i18n',
                    'amosapp' => '@common/translation/amosapp/i18n',
                    'cruds' => '@common/translation/cruds/i18n',
                    'giiamos' => '@common/translation/giiamos/i18n',
                    'javascript' => '@common/translation/javascript/i18n',
                    'site' => '@common/translation/site/i18n',
                    'wizard' => '@common/translation/wizard/i18n',
                    'prodottiservizi' => '@vendor/open20/prodotti-e-servizi/src/i18n'
                ],
            ],
        ],
    ],
    'authManager' => [
        'class' => 'yii\rbac\DbManager',
    ],
    'breadcrumbcache' => [
        'class' => 'yii\caching\FileCache',
        'cachePath' => '@runtime/breadcrumbcache'
    ],
    'cache' => [
        'class' => 'yii\caching\FileCache',
    ],
    'socialShare' => [
        'class' => 'open20\amos\core\components\ConfiguratorSocialShare',
    ],
    'rbacCache' => [
        'class' => 'yii\caching\FileCache',
        'cachePath' => '@runtime/rbacCache'
    ],
    'schemaCache' => [
        'class' => 'yii\caching\FileCache',
        'cachePath' => '@runtime/schemaCache'
    ],
    'translateCache' => [
        'class' => 'yii\caching\FileCache',
        'cachePath' => '@runtime/translateCache'
    ],
];