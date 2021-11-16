<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */
$modules['admin'] = [
    'class' => 'open20\amos\admin\AmosAdmin',
    'enableRegister' => false,
    'disableFirstAccesWizard' => true,
    'bypassWorkflow' => true,
    'disableUpdateChangeStatus' => true,
    'dontCheckOneTagPresent' => true,
    'tightCoupling' => false,
    'enableDlSemplification' => true,
    'hideStandardLoginPageSection' => false,
    'defaultUserRole' => 'PC_BASIC_USER',
    'tightCouplingModel' => ['\open20\amos\events\models\EventGroupReferentMm' => 'event_group_referent_id'],
    'tightCouplingMethod' => ['\open20\amos\events\models\EventGroupReferent' => 'getTightCouplingGroups'],
    'enableAttributeChangeLog' => [
        'nome',
        'cognome',
        'presentazione_breve',
        'sesso',
        'user_profile_age_group_id',
        'telefono',
        'azienda',
        'nascita_nazioni_id',
        'nascita_comuni_id',
        'nascita_province_id',
        'nascita_data',
        'codice_fiscale',
        'privacy',
        'attivo',
    ],
    'tightCouplingRoleAdmin' => 'SUPER_USER_EVENT',
    'controllerMap' => [
        'user-profile' => [
            'class' => '\backend\modules\eventsadmin\controllers\UserProfileController',
        ],
        'default' => [
            'class' => '\backend\modules\eventsadmin\controllers\DefaultController',
        ],
    ],
    'modelMap' => [
        'UserProfileSearch' => 'backend\modules\eventsadmin\models\search\UserProfileSearch',
        'UserProfile' => 'backend\modules\pcadmin\models\UserProfile',
    ],
    'profileRequiredFields' => [
        'nome',
        'cognome',
        'status',
    ],
    'fieldsConfigurations' => [
        'boxes' => [
            'box_account_data' => ['form' => true, 'view' => true],
            'box_dati_accesso' => ['form' => true, 'view' => true],
            'box_dati_contatto' => ['form' => true, 'view' => true],
            'box_dati_fiscali_amministrativi' => ['form' => true, 'view' => true],
            'box_dati_nascita' => ['form' => true, 'view' => true],
            'box_email_frequency' => ['form' => false, 'view' => false],
            'box_facilitatori' => ['form' => false, 'view' => false],
            'box_foto' => ['form' => true, 'view' => true],
            'box_informazioni_base' => ['form' => true, 'view' => true],
            'box_presentazione_personale' => ['form' => false, 'view' => false],
            'box_prevalent_partnership' => ['form' => false, 'view' => false],
            'box_privacy' => ['form' => true, 'view' => true],
            'box_questio' => ['form' => false, 'view' => false],
            'box_role_and_area' => ['form' => false, 'view' => false],
            'box_social_account' => ['form' => true, 'view' => true],
        ],
        'fields' => [
            'attivo' => ['form' => true, 'view' => true, 'referToBox' => 'box_account_data'],
            'codice_fiscale' => ['form' => true, 'view' => true, 'referToBox' => 'box_dati_fiscali_amministrativi'],
            'cognome' => ['form' => true, 'view' => true, 'referToBox' => 'box_informazioni_base'],
            'default_facilitatore' => ['form' => true, 'view' => true],
            'email' => ['form' => true, 'view' => false, 'referToBox' => 'box_dati_contatto'],
            'email_pec' => ['form' => false, 'view' => false, 'referToBox' => 'box_dati_contatto'],
            'facebook' => ['form' => true, 'view' => true, 'referToBox' => 'box_social_account'],
            'facilitatore_id' => ['form' => false, 'view' => false, 'referToBox' => 'box_facilitatori'],
            'googleplus' => ['form' => true, 'view' => true, 'referToBox' => 'box_social_account'],
            'linkedin' => ['form' => true, 'view' => true, 'referToBox' => 'box_social_account'],
            'nascita_comuni_id' => ['form' => true, 'view' => true, 'referToBox' => 'box_dati_nascita'],
            'nascita_data' => ['form' => true, 'view' => true, 'referToBox' => 'box_dati_nascita'],
            'nascita_nazioni_id' => ['form' => true, 'view' => true, 'referToBox' => 'box_dati_nascita'],
            'nascita_province_id' => ['form' => true, 'view' => true, 'referToBox' => 'box_dati_nascita'],
            'nome' => ['form' => true, 'view' => true, 'referToBox' => 'box_informazioni_base'],
            'note' => ['form' => true, 'view' => false, 'referToBox' => 'box_informazioni_base'],
            'presentazione_breve' => ['form' => false, 'view' => false, 'referToBox' => 'box_informazioni_base'],
            'presentazione_personale' => ['form' => false, 'view' => false, 'referToBox' => 'box_presentazione_personale'],
            'prevalent_partnership_id' => ['form' => false, 'view' => false, 'referToBox' => 'box_prevalent_partnership'],
            'privacy' => ['form' => true, 'view' => true, 'referToBox' => 'box_privacy'],
            'sesso' => ['form' => true, 'view' => false, 'referToBox' => 'box_informazioni_base'],
            'telefono' => ['form' => true, 'view' => false, 'referToBox' => 'box_dati_contatto'],
            'twitter' => ['form' => true, 'view' => true, 'referToBox' => 'box_social_account'],
            'ultimo_accesso' => ['form' => true, 'view' => true, 'referToBox' => 'box_account_data'],
            'ultimo_logout' => ['form' => true, 'view' => true, 'referToBox' => 'box_account_data'],
            'username' => ['form' => true, 'view' => false, 'referToBox' => 'box_dati_accesso'],
            'user_profile_age_group_id' => ['form' => true, 'view' => true, 'referToBox' => 'box_informazioni_base'],
            'user_profile_area_id' => ['form' => true, 'view' => false, 'referToBox' => 'box_role_and_area'],
            'userProfileImage' => ['form' => true, 'view' => true, 'referToBox' => 'box_foto'],
            'user_profile_role_id' => ['form' => true, 'view' => false, 'referToBox' => 'box_role_and_area'],
        ]
    ]
];

$modules['attachments'] = [
    'class' => 'open20\amos\attachments\FileModule',
    'webDir' => 'files',
    'tempPath' => '@common/uploads/temp',
    'storePath' => '@common/uploads/store',
    'cache_age' => '2592000',
    'disableGallery' => true
];
$modules['comuni'] = [
    'class' => 'open20\amos\comuni\AmosComuni',
];
$modules['community'] = [
    'class' => 'open20\amos\community\AmosCommunity',
];

$modules['cwh'] = [
    'class' => 'open20\amos\cwh\AmosCwh',
    'validatoriEnabled' => false,
    'cached' => false,
    'modelsEnabled' => [
        'open20\amos\news\models\News',
        'open20\amos\documenti\models\Documenti'
    ],
    'validateOnStatus' => [
        'open20\amos\news\models\News' => [ 
            'class' => 'open20\amos\news\models\News',
            'attribute' => 'status',
            'statuses' => [
                'VALIDATO',
            ]
        ],
        'open20\amos\documenti\models\Documenti' => [
            'class' => 'open20\amos\documenti\models\Documenti',
            'attribute' => 'status',
            'statuses' => [
                'VALIDATO',
            ]
        ],
    ]
    
];

$modules['documenti'] = [
    'class' => 'open20\amos\documenti\AmosDocumenti',
    'enableFolders' => true,
    'whiteListFilesExtensions' => 'txt, csv, pdf, txt, doc, docx, xls, xlsx, rtf, pptx, jpeg, jpg, png, gif',
    'disableStandardWorkflow' => false,
    'whiteListRolesCategories' => ['ADMIN', 'PC_BASIC_USER'],
    'enableCategories' =>false,
];

$modules['email'] = [
    'class' => 'open20\amos\emailmanager\AmosEmail',
    'templatePath' => '@common/mail/emails',
];

//$modules['events'] = [
//    'class' => 'open20\amos\events\AmosEvents',
//    'hideViewTabs' => ['tab-overview', 'tab-organization'],
//    'enableNewWizard' => true,
//    'groupReferentAdministration' => [
//        'id' => 1,
//        'role' => 'SUPER_USER_EVENT',
//        'roleAdmin' => 'SUPER_USER'
//
//    ],
//    'mailup_configurations' => [
//        'original_message_id' => 2,
//        'mailup_list_id' => 2,
//        'dynamics_fields' => [
//            1 => 'nome',
//            2 => 'cognome',
//            3 => 'azienda',
//            4 => 'comune',
//            5 => 'provincia',
//            29 => 'codice_fiscale',
//            30 => 'sesso',
//            31 => 'eta',
//        ]
//    ],
//    'showOnlyRegionInWizard' => 3
//];

$modules['landings'] = [
    'class' => 'backend\modules\landings\Module',
];

$modules['news'] = [
    'class' => 'open20\amos\news\AmosNews',
    'newsRequiredFields' => [
        'titolo',
        // 'status',
        'descrizione',
    ],
    'defaultEnableComments' => 0,
    'disableStandardWorkflow' => false,
    'whiteListRolesCategories' => ['ADMIN', 'PC_BASIC_USER'],
];

$modules['newsletter'] = [
    'class' => 'amos\newsletter\Module',
    'client_id' => "",
    'client_secret' => "",
    'customModel' => '',
    'username' => "",
    'password' => "",
    'SMTP_username' => '',
    'SMTP_password' => '',
];

$modules['notify'] = [
    'class' => 'open20\amos\notificationmanager\AmosNotify',
];

$modules['seo'] = [
    'class' => 'open20\amos\seo\AmosSeo',
    'modelsEnabled' => [
        'open20\amos\discussioni\models\DiscussioniTopic',
        'open20\amos\events\models\Event',
        'open20\amos\news\models\News',
        'open20\amos\community\models\Community',
        'open20\amos\documenti\models\Documenti',
    ],
];


$modules['translation'] = [
    'class' => 'open20\amos\translation\AmosTranslation',
    'queryCache' => 'translateCache',
    'cached' => true,
    'translationBootstrap' => [
        'configuration' => [
            'translationLabels' => [
                'classBehavior' => \lajax\translatemanager\behaviors\TranslateBehavior::className(),
                'models' => [
                    [
                        'namespace' => 'cornernote\workflow\manager\models\Status',
                        //'connection' => 'db', //if not set it use 'db'
                        //'classBehavior' => NULL,//if not set it use default classBehavior 'lajax\translatemanager\behaviors\TranslateBehavior'
                        'attributes' => ['label'],
                    ],
                ],
            ],
            'translationContents' => [
                'classBehavior' => \open20\amos\translation\behaviors\TranslateableBehavior::className(),
                'models' => [
                    /* [
                      'namespace' => 'cornernote\workflow\manager\models\Status',
                      //'connection' => 'db', //if not set it use 'db'
                      //'classBehavior' => NULL,//if not set it use default classBehavior 'lajax\translatemanager\behaviors\TranslateBehavior'
                      'enableWorkflow' => FALSE,
                      'attributes' => ['label'],
                      'plugin' => 'workflow-manager',
                      ], */
                    ['namespace' => 'open20\amos\tag\models\Tag',
                        //'connection' => 'db', //if not set it use 'db'
                        //'classBehavior' => NULL,//if not set it use default classBehavior 'open20\amos\translation\behaviors\TranslateableBehavior'
                        'enableWorkflow' => FALSE, //if not set it use default configuration of the plugin
                        'attributes' => ['nome', 'descrizione'],
                        'plugin' => 'tag'
                    ],
                    ['namespace' => 'open20\amos\news\models\NewsCategorie',
                        //'connection' => 'db', //if not set it use 'db'
                        //'classBehavior' => NULL,//if not set it use default classBehavior 'open20\amos\translation\behaviors\TranslateableBehavior'
                        'enableWorkflow' => FALSE, //if not set it use default configuration of the plugin
                        'attributes' => ['titolo', 'sottotitolo'],
                        'plugin' => 'news'
                    ],
                    ['namespace' => 'open20\amos\documenti\models\DocumentiCategorie',
                        //'connection' => 'db', //if not set it use 'db'
                        //'classBehavior' => NULL,//if not set it use default classBehavior 'open20\amos\translation\behaviors\TranslateableBehavior'
                        'enableWorkflow' => FALSE, //if not set it use default configuration of the plugin
                        'attributes' => ['titolo', 'descrizione'],
                        'plugin' => 'documenti'
                    ],
                    ['namespace' => 'open20\amos\community\models\CommunityType',
                        //'connection' => 'db', //if not set it use 'db'
                        //'classBehavior' => NULL,//if not set it use default classBehavior 'open20\amos\translation\behaviors\TranslateableBehavior'
                        'enableWorkflow' => FALSE, //if not set it use default configuration of the plugin
                        'attributes' => ['name', 'description'],
                        'plugin' => 'community'
                    ],
                ],
            ],
        ],
    ],
    'module_translation_labels' => 'translatemanager',
    'module_translation_labels_options' => ['roles' => ['ADMIN', 'CONTENT_TRANSLATOR'],
        'root' => [
            '@vendor/amos/',
            '@vendor/',
            '@app',
            '@backend',
            '@frontend',
            '@vendor/open20/',
        ],
    ], //all the options of the translatemanager
    'components' => [
        'translatemanager' => [
            'class' => 'lajax\translatemanager\Component'
        ],
    ],
    'defaultLanguage' => 'it-IT',
    'defaultUserLanguage' => 'it-IT',
    'pathsForceTranslation' => ['*'],
];

$modules['socialauth'] = [
    'class' => 'open20\amos\socialauth\Module',
    'enableLogin' => false,
    'enableLink' => true,
    'enableRegister' => false,
    'enableSpid' => true,
    'userOverload' => true,
    'shibbolethAutoLogin' => true,
    'shibbolethAutoRegistration' => true,
    'disableAssociationByEmail' => true,
    'shibbolethConfig' => [
        'buttonLabel' => 'Accedi con la tua identità digitale'
    ],
    'providers' => [
        "Google" => [ //socialopen20@gmail.com
            "enabled" => false,
            "keys" => [
                "id" => "",
                "secret" => "",
                "scope" => "https://www.googleapis.com/auth/userinfo.profile",
                "https://www.googleapis.com/auth/userinfo.email",
            ],
        ],
         "Facebook" => [//socialopen20@gmail.com
            "enabled" => false,
            "keys" => [
                "id" => "",
                "secret" => ""
            ],
            "scope" => "email"
        ],
        "Twitter" => [//elitedivsocial
            "enabled" => false,
            "keys" => [
                "key" => "",
                "secret" => ""
            ],
            "scope" => 'email',
            "includeEmail" => true
        ],
        "LinkedIn" => [//socialopen20@gmail.com
            "enabled" => false,
            "keys" => [
                 "id" => "",
                "secret" => ""
            ],
            "state" => md5(time()),
            "scope" => "r_liteprofile r_emailaddress w_member_social",
        ],
    ]
];


$modules['tag'] = [
    'class' => 'open20\amos\tag\AmosTag',
];

$modules['preferenceuser'] =[
        'class' => 'preference\userprofile\Module',
    ];

$modules['userimporter'] = [
    'class' => 'backend\modules\userimporter\Module',
    'url_example_file' => '/files/user_import_example.xlsx',
];

$modules['sondaggi'] = [
    'class' => 'open20\amos\sondaggi\AmosSondaggi',
    'enableDashboard' => true,
    'hideOwnInterest' => true,
    'compilationToOrganization' => false,
    'hasComunications' => false,
    'hasInvitation' => false,
    'forceOnlyFrontend' => true,
    'enableFrontendCompilation' => true,
    'enableAdvancedSettings' => false,
    'frontendControllerLayoutPath' => '@frontend/views/layouts/sondaggiFrontendCustom',
    'statisticDisableLoggedUsers' => true,
    'statisticExtractDisableNameSurnameEmail' => true,
    'sondaggioDataConfirmMessage' => 'Si raccomanda di non implementare all\'interno del sondaggio alcun campo o modulo finalizzato alla raccolta di dati personali (nome, cognome, mail, etc.)',
    'activatePoolRoles' => [
        'PC_APPROVER',
        'PC_STRUCTURE_APPROVER',
    ],
];

return $modules;
