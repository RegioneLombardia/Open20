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
    'admin' => [
        'userIdleTimeout' => 60 * 120,
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
            'login' => 'app\modules\cms\controllers\LoginController',
            'login-cms-admin' => 'app\modules\cms\controllers\LoginController',
            'default' => 'app\modules\cms\admin\controllers\DefaultController',
        ]
    ],
    'adminuikit' => [
        'class' => 'app\modules\uikit\admin\Module',
    ],
    'amministra-utenti' => [
        'class' => 'open20\amos\admin\RoleManager',
        'layout' => "@vendor/open20/amos-core/views/layouts/admin",
        //'left-menu', // it can be '@path/to/your/layout'.
        'controllerMap' => [
            'assignment' => [
                'class' => 'mdm\admin\controllers\AssignmentController',
                'userClassName' => 'common\models\User',
                'idField' => 'id'
            ],
        ],
        'menus' => [
            'assignment' => [
                'label' => 'Gestisci Assegnazioni' // TODO translate
            ],
        ]
    ],
    'audit' => [
        'class' => 'open20\amos\audit\Audit',
        'db' => 'db',
        'accessRoles' => ['ADMIN'],
        'ignoreActions' => [
            '*',
        ],
    ],
    'backendobjects' => [
        'class' => 'app\modules\backendobjects\frontend\Module',
        'modulesEnabled' => [
            'open20\elasticsearch\Module',
            'open20\amos\tag\AmosTag',
            'open20\amos\news\AmosNews',
            'open20\amos\events\AmosEvents',
            'open20\amos\community\AmosCommunity',
            'open20\elasticsearch\Module',
            'open20\amos\discussioni\AmosDiscussioni',
            'open20\amos\documenti\AmosDocumenti',
            'open20\amos\sondaggi\AmosSondaggi'
        ],
        'modelsDetailMapping' => [
        ]
    //'useAppViewPath' => true, // When enabled the views will be looked up in the @app/views folder, otherwise the views shipped with the module will be used.
    ],
    /*
     * Frontend module for the `cms` module.
     */
    'cms' => [
        'class' => 'luya\cms\frontend\Module',
        'overlayToolbar' => false,
        'contentCompression' => true,
        'controllerMap' => [
            'default' => 'app\modules\cms\base\DefaultController',
            'preview' => 'app\modules\cms\base\PreviewController',
        ],
    ],
    /*
     * Admin module for the `cms` module.
     */
    'cmsadmin' => [
        'class' => 'app\modules\cms\admin\Module',
        'hiddenBlocks' => [],
        'blockVariations' => [],
        'controllerMap' => [
            'default' => 'app\modules\cms\cmsadmin\controllers\DefaultController',
            'page' => 'app\modules\cms\cmsadmin\controllers\PageController'
        ]
    ],
    'cmsapi' => [
        'class' => 'app\modules\cmsapi\frontend\Module',
        'css_layoutsection_with_image' => 'hero-banner'
    ],
    'cmsbridge' => [
        'class' => 'amos\cmsbridge\Module'
    ],
    'cmscartapi' => 'app\modules\cmsapi\cart\Module',
    'cmsseo' => [
        'class' => 'app\modules\seo\frontend\Module',
    ],
    'seocontent' => [
        'class' => 'open20\amos\seo\AmosSeo',
        'moduleName' => 'seocontent',
        'modelsEnabled' => [
            'open20\amos\discussioni\models\DiscussioniTopic',
            'open20\amos\events\models\Event',
            'open20\amos\news\models\News',
            'open20\amos\community\models\Community',
            'open20\amos\documenti\models\Documenti',
            'amos\podcast\models\Podcast',
            'amos\podcast\models\PodcastEpisode',
        ],
    ],
    'crawler' => [
        'class' => 'open2\crawler\frontend\Module',
        'baseUrl' => 'https://front-agid-test.devel.demotestwip.it',
        'statisticRecipients' => ['example@lorem.ipsum'],
    ],
    'crawleradmin' => 'open2\crawler\admin\Module',
    'chat' => [
        'class' => 'open20\amos\chat\AmosChat',
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
    'design' => [
        'class' => 'open20\design\Module',
    ],
    'gridview' => [
        'class' => '\kartik\grid\Module'
    ],
    'layout' => [
        'class' => 'open20\amos\layout\Module',
        'open2CommunityOrganizationView' => true,
    ],
    'organizationalunit' => [
        'class' => 'open20\agid\organizationalunit\Module',
        'useFrontendView' => true,
    ],
    'partnershipprofiles' => [
        'class' => 'open20\amos\partnershipprofiles\Module',
        'pluginCustomIcon' => 'een-platform',
        'fieldsConfigurations' => [
            'required' => [
                'title',
                'short_description',
                'expected_contribution',
                'partnership_profile_date',
                'expiration_in_months',
                'attrPartnershipProfilesTypesMm'
            ],
            'tabs' => [
                'tab-more-information' => true,
                'tab-attachments' => true
            ],
            'fields' => [
                //tab general
                'title' => true,
                'short_description' => true,
                'extended_description' => true,
                'advantages_innovative_aspects' => true,
                'expected_contribution' => true,
                'partnership_profile_date' => true,
                'expiration_in_months' => true,
                'attrPartnershipProfilesTypesMm' => true,
                'other_prospect_desired_collab' => true,
                'contact_person' => true,
                // tab other information
                'english_title' => true,
                'english_short_description' => true,
                'english_extended_description' => true,
                'attrPartnershipProfilesCountriesMm' => true,
                'willingness_foreign_partners' => true,
                'work_language_id' => true,
                'other_work_language' => true,
                'development_stage_id' => true,
                'other_development_stage' => true,
                'intellectual_property_id' => true,
                'other_intellectual_property' => true
            ],
        ]
    ],
    'podcast' => [
        'class' => 'amos\podcast\Module',
        'canCreateOnlyCommunityManager' => true,
        'basicUserCannotCreate' => true
    ],
    'service' => [
        'class' => 'open20\agid\service\Module',
        'useFrontendView' => true,
    ],
    'sondaggi' => [
        'class' => 'open20\amos\sondaggi\AmosSondaggi',
    ],
    'person' => [
        'class' => 'open20\agid\person\Module',
        'useFrontendView' => true,
    ],
    'dataset' => [
        'class' => 'open20\agid\dataset\Module',
        'useFrontendView' => true,
    ],
    'discussioni' => [
        'class' => 'open20\amos\discussioni\AmosDiscussioni',
        'disableReportFlag' => true,
        'usePrettyUrl' => true,
    ],
    'invitations' => [
        'class' => 'open20\amos\invitations\Module',
    ],
    'metrics' => [
        'class' => 'frontend\modules\metrics\Module',
    ],
    'redactor' => [
        'class' => 'yii\redactor\RedactorModule',
        'uploadDir' => '@webroot/uploadfiles/redactor',
        'uploadUrl' => '@web/uploadfiles/redactor',
        'imageAllowExtensions' => ['jpg', 'png', 'gif']
    ],
    'report' => [
        'class' => 'open20\amos\report\AmosReport',
        'modelsEnabled' => [
//            'open20\amos\news\models\News',
//            'open20\amos\documenti\models\Documenti',
//            'open20\amos\discussioni\models\DiscussioniTopic',
//            'open20\amos\events\models\Event',
        ]
    ],
    'seo' => [
        'class' => 'open20\amos\seo\AmosSeo',
        'modelsEnabled' => [
            'open20\amos\discussioni\models\DiscussioniTopic',
            'open20\amos\events\models\Event',
            'open20\amos\news\models\News',
            'open20\amos\community\models\Community',
            'open20\amos\documenti\models\Documenti',
        ],
    ],
    'slideshow' => [
        'class' => 'open20\amos\slideshow\AmosSlideshow',
    ],
    'sitemanagement' => [
        'class' => 'amos\sitemanagement\Module',
        'enableUploadVideoSlider' => false,
        'enableTextSlider' => false
    ],
    'sitemap' => [
        'class' => 'app\modules\sitemap\frontend\Module',
    ],
    'social' => [
        // the module class
        'class' => 'kartik\social\Module',
        // the global settings for the google analytic plugin widget
        // 'googleAnalytics' => [
        //     'id' => '',
        //     'domain' => '',
        // ],
    ],
    'treemanager' => [
        'class' => '\kartik\tree\Module',
        // enter other module properties if needed
        // for advanced/personalized configuration
        // (refer module properties available below)
        'dataStructure' => ['nameAttribute' => 'nome']
    ],
    'uikit' => [
        'class' => 'app\modules\uikit\Module',
        'disableDevelopmentGroup' => true,
        'disableAnalizeSeoButton' => true
    ],
    'userauthfrontend' => [
        'class' => 'amos\userauth\frontend\Module',
        'enableSPID' => true,
        'useAppViewPath' => false, // When enabled the views will be looked up in the @app/views folder, otherwise the views shipped with the module will be used.
        'hideResetPasswordLogin' => true,
        'enableDlSemplificationLight' => true,
        'preventAdminLogin' => true
    ],
    'utility' => [
        'class' => 'open20\amos\utility\Module'
    ],
    'workflow' => [
        'class' => 'open20\amos\workflow\AmosWorkflow'
    ],
    'myactivities' => [
        'class' => 'open20\amos\myactivities\AmosMyActivities',
    ],
    'dashboards' => [
        'class' => 'open20\cms\dashboard\Module',
        'enableContainerFilterForEditor' => false,
        'enableProfile' => true,
        'mapPermissionContainer' => [
            'REDATTORECMS' => [
                'default',
                'footer'
            ],
            'CAPOREDATTORECMS' => [
                'default',
                'footer',
                'system',
                'footeruno',
                'footer-due',
                'footer-tre',
                'myopen'
            ],
        ]
    ],
    'socialwall' => [
        'class' => 'open20\socialwall\Module',
//        'modulesIdClassMap' => [
//            'open20\amos\news\models\News' => 'news',
//        ]
    ],
    'moodle' => [
        'class' => 'open20\amos\moodle\AmosMoodle',
        'moodleUrl' => '',
        'moodleAdministratorToken' => '',
        'moodleOpen20baseRoleId' => 9,
        'secretKey' => '!',
        'adminUsername' => '',
        'enableAddStudentRoleAfterLogin' => false,
        'skipPlatformGotoMoodleDirectly' => false,
    ],
    'workflow-manager' => [
        'class' => 'cornernote\workflow\manager\Module',
    ],
];
