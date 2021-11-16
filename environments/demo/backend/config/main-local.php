<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

/*---------- begin: BOOTSTRAP --------*/
$bootstrap[] = 'comments';
/*---------- end: BOOTSTRAP --------*/

/*---------- begin: COMPONENTS AMOS --------*/
/*---------- end: COMPONENTS AMOS --------*/

/*---------- begin: COMPONENTS OTHERS --------*/
$components = [
    'request' => [
        // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
        'cookieValidationKey' => '',
    ],
];
/*---------- end: COMPONENTS OTHERS --------*/

/*---------- begin: MODULES AMOS --------*/
$modules['chat'] = [
    'class' => 'open20\amos\chat\AmosChat',
];
$modules['comments'] = [
    'class' => 'open20\amos\comments\AmosComments',
    'enableMailsNotification' => false,
    'modelsEnabled' => [
        'open20\amos\discussioni\models\DiscussioniTopic',
        'open20\amos\documenti\models\Documenti',
        'open20\amos\events\models\Event',
        'open20\amos\news\models\News',
    ],
];
$modules['cwh'] = [
    'cached' => false,
];
$modules['dashboard'] = [
        'useWidgetGraphicDashboardVisible' => true,
        'useWidgetGraphicOrder' => true,
];
$modules['discussioni'] = [
    'class' => 'open20\amos\discussioni\AmosDiscussioni',
];
$modules['documenti'] = [
    'class' => 'open20\amos\documenti\AmosDocumenti',
    'params' => [
        'site_publish_enabled' => true,
        'site_featured_enabled' => true
    ],
];
$modules['events'] = [
    'class' => 'open20\amos\events\AmosEvents',
];
$modules['faq'] = [
    'class' => 'open20\amos\faq\AmosFaq',
];
$modules['favorites'] = [
    'class' => 'open20\amos\favorites\AmosFavorites',
    'modelsEnabled' => [
        'open20\amos\discussioni\models\DiscussioniTopic',
        'open20\amos\documenti\models\Documenti',
        'open20\amos\news\models\News',
    ]
];
$modules['inforeq'] = [
    'class' => 'open20\amos\inforeq\AmosInforeq',
];
$modules['news'] = [
    'class' => 'open20\amos\news\AmosNews',
    'params' => [
        'site_publish_enabled' => true,
        'site_featured_enabled' => true
    ]
];
$modules['privileges'] = [
    'class' => 'open20\amos\privileges\AmosPrivileges',
];
$modules['report'] = [
    'class' => 'open20\amos\report\AmosReport',
    'modelsEnabled' => [
        'open20\amos\discussioni\models\DiscussioniTopic',
        'open20\amos\documenti\models\Documenti',
        'open20\amos\events\models\Event',
        'open20\amos\news\models\News',
    ],
];
if (isset($modules['tag'])) {
    if (isset($modules['community'])) {
        $modules['tag']['modelsEnabled'][] = 'common\models\Community';
    }
}
/*---------- end: MODULES AMOS --------*/

/*---------- begin: MODULES OTHERS --------*/
/*---------- end: MODULES OTHERS --------*/

$config = [
    'name' => 'Amos Basic Template DEMO',
    'bootstrap' => $bootstrap,
    'components' => $components,
    'modules' => $modules,
];

return $config;
