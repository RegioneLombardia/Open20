<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

$modules = [
    'amministra-utenti' => [
        'class' => 'mdm\admin\Module',
        'layout' => "@vendor/open20/amos-core/views/layouts/admin",
        //'left-menu', // it can be '@path/to/your/layout'.
        'controllerMap' => [
            'assignment' => [
                'class' => 'mdm\admin\controllers\AssignmentController',
                'userClassName' => 'common\models\User',
                'idField' => 'id'
            ],
            /*
              'other' => [
              'class' => 'path\to\OtherController', // add another controller
              ],
             */
        ],
        'menus' => [
            'assignment' => [
                'label' => 'Gestisci Assegnazioni' // TODO translate
            ],
        ]
    ],
    /*  'chat' => [
         'class' => 'open20\amos\chat\AmosChat',
    ],*/
    'comments' => [
        'class' => 'open20\amos\comments\AmosComments',
        'modelsEnabled' => [
            'open20\amos\discussioni\models\DiscussioniTopic',
            'open20\amos\documenti\models\Documenti',
            'open20\amos\events\models\Event',
            'open20\amos\news\models\News',
        ],
    ],

    'design' => [
        'class' => 'open20\design\Module',
    ],

    'dashboard' => [
        'class' => 'open20\amos\dashboard\AmosDashboard',
    ],
    'discussioni' => [
        'class' => 'open20\amos\discussioni\AmosDiscussioni',
    ],

    'layout' => [
        'class' => 'open20\amos\layout\Module',
    ],
    'mobilebridge' => [ 'class' => 'open20\amos\mobile\bridge\Module' ],

//    'myactivities' => [
//        'class' => 'open20\amos\myactivities\AmosMyActivities',
//    ],
    'privileges' => [
        'class' => 'open20\amos\privileges\AmosPrivileges',
    ],

    'tag' => [
        'class' => 'open20\amos\tag\AmosTag',
    ],
    'upload' => [
        'class' => 'open20\amos\upload\AmosUpload',
    ],
    'utility' => [
        'class' => 'open20\amos\utility\Module'
    ],
    'report' => [
        'class' => 'open20\amos\report\AmosReport',
        'modelsEnabled' => [
            'open20\amos\discussioni\models\DiscussioniTopic',
            'open20\amos\documenti\models\Documenti',
            'open20\amos\events\models\Event',
        ]
    ],
    'sitemanagement' => [
        'class' => 'amos\sitemanagement\Module',
        'enableUploadVideoSlider' => false,
        'enableTextSlider' => false
    ],
    'workflow' => [
        'class' => 'open20\amos\workflow\AmosWorkflow',
    ],
    'cmsbridge' => [
        'class' => 'amos\cmsbridge\Module',
    ],
    'campains' => [
        'class' => 'backend\modules\campains\Module',
    ],
    'statistics' => [
        'class' => 'backend\modules\statistics\Module',
    ],
    'operators' => [
        'class' => 'backend\modules\operators\Module',
    ],
    'registeredusers' => [
        'class' => 'backend\modules\registeredusers\Module',
    ],
    'rbacviewer' => [
        'class' => 'backend\modules\rbacviewer\AmosRbacViewer',
    ],
    'thematics' => [
        'class' => 'backend\modules\thematics\Module',
    ],
    'organizationalunit' => [
        'class' => 'open20\agid\organizationalunit\Module'
    ],
    'service' => [
        'class' => 'open20\agid\service\Module'
    ],
    'person' => [
        'class' => 'open20\agid\person\Module'
    ],

];


return $modules;
