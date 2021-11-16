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
    'imageUtility' => [
        'class' => 'open20\amos\core\components\ImageUtility',
    ],
    'view' => [
        'class' => 'open20\amos\core\components\AmosView',
        'theme' => [
            'pathMap' => [
                '@vendor/open20/amos-admin/src/views/user-profile' => '@app/modules/eventsadmin/views/user-profile',
                '@vendor/open20/amos-layout/src/views/layouts/parts/' => '@backend/modules/pclayout/src/views/layouts/parts',
                '@vendor/open20/amos-layout/src/views/layouts/fullsize/parts/' => '@backend/modules/pclayout/src/views/layouts/fullsize/parts',
                '@vendor/open20/amos-news/src/views/news' => '@backend/modules/landings/views/news',
                '@vendor/open20/amos-documenti/src/views/documenti' => '@backend/modules/landings/views/documenti',
                '@vendor/open20/amos-admin/src/views/security/fullsize' => '@backend/modules/pcadmin/views/security/fullsize',
            ],
        ],
    ],
    'workflowSource' => [
        'class' => 'open20\amos\core\workflow\ContentDefaultWorkflowDbSource',
    ],
];
