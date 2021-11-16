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
    'treemanager' => [
        'class' => '\kartik\tree\Module',
        // enter other module properties if needed
        // for advanced/personalized configuration
        // (refer module properties available below)
        'dataStructure' => ['nameAttribute' => 'nome']
    ],
    'workflow-manager' => [
        'class' => 'cornernote\workflow\manager\Module',
    ],
    'gridview' => [
        'class' => '\kartik\grid\Module'
    ],
    'social' => [
        // the module class
        'class' => 'kartik\social\Module',
        // the global settings for the google analytic plugin widget
        'googleAnalytics' => [
            'id' => 'UA-88133453-2',
            'domain' => 'appdemoweb.org',
        ],
    ],
    /****************DO NOT REMOVE****************/
];
