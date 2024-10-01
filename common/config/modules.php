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
    'amosadmin' => [
        'class' => 'open20\amos\admin\AmosAdmin',
        'moduleName' => 'amosadmin',
        'defaultUserRole' => 'BASIC_USER',
        'enableRegister' => true,
        'disableFirstAccesWizard' => true,
        'bypassWorkflow' => true,
        'enableUserContacts' => true,
        'completeBypassWorkflow' => true,
        'disableUpdateChangeStatus' => true,
        'dontCheckOneTagPresent' => false,
        'disablePrivilegesEnableProfiles' => true,
        'requireAdminPassword' => false,
        'defaultProfiles' => [3], // Utente
        'enableForceRoleByProfiles' => true,
        'idProfileClassesOperators' => [1, 2],
        'enableOnlineUsers' => true,
        'profileRequiredFields' => [
            'status',
            'nome',
            'codice_fiscale',
        ],
        'fieldsConfigurations' => [
            'boxes' => [
                'box_account_data' => ['form' => true, 'view' => true],
                'box_dati_accesso' => ['form' => true, 'view' => true],
                'box_dati_contatto' => ['form' => true, 'view' => true],
                'box_dati_fiscali_amministrativi' => ['form' => true, 'view' => false],
                'box_dati_nascita' => ['form' => true, 'view' => true],
                'box_email_frequency' => ['form' => true, 'view' => true],
                'box_facilitatori' => ['form' => false, 'view' => false],
                'box_foto' => ['form' => true, 'view' => true],
                'box_informazioni_base' => ['form' => true, 'view' => true],
                'box_presentazione_personale' => ['form' => true, 'view' => true],
                'box_privacy' => ['form' => true, 'view' => true],
                'box_questio' => ['form' => false, 'view' => false],
                'box_role_and_area' => ['form' => false, 'view' => false],
                'box_social_account' => ['form' => true, 'view' => true],
				'box_prevalent_partnership' => ['form' => true, 'view' => true],
            ],
            'fields' => [
                'attivo' => ['form' => true, 'view' => true, 'referToBox' => 'box_account_data'],
                'codice_fiscale' => ['form' => true, 'view' => true, 'referToBox' => 'box_dati_fiscali_amministrativi'],
                'cognome' => ['form' => true, 'view' => true, 'referToBox' => 'box_informazioni_base'],
                'serial_number' => ['form' => false, 'view' => false, 'referToBox' => 'box_informazioni_base'],
                'start_service_date' => ['form' => false, 'view' => false, 'referToBox' => 'box_informazioni_base'],
                'end_service_date' => ['form' => false, 'view' => false, 'referToBox' => 'box_informazioni_base'],
                'default_facilitatore' => ['form' => true, 'view' => true],
                'email' => ['form' => true, 'view' => false, 'referToBox' => 'box_dati_contatto'],
                'email_pec' => ['form' => false, 'view' => false, 'referToBox' => 'box_dati_contatto'],
                'facebook' => ['form' => true, 'view' => true, 'referToBox' => 'box_social_account'],
                'facilitatore_id' => ['form' => true, 'view' => true, 'referToBox' => 'box_facilitatori'],
                'googleplus' => ['form' => true, 'view' => true, 'referToBox' => 'box_social_account'],
                'linkedin' => ['form' => true, 'view' => true, 'referToBox' => 'box_social_account'],
                'nascita_comuni_id' => ['form' => false, 'view' => false, 'referToBox' => 'box_dati_nascita'],
                'nascita_data' => ['form' => false, 'view' => false, 'referToBox' => 'box_dati_nascita'],
                'nascita_nazioni_id' => ['form' => false, 'view' => false, 'referToBox' => 'box_dati_nascita'],
                'nascita_province_id' => ['form' => false, 'view' => false, 'referToBox' => 'box_dati_nascita'],
                'nome' => ['form' => true, 'view' => true, 'referToBox' => 'box_informazioni_base'],
                'note' => ['form' => true, 'view' => false, 'referToBox' => 'box_informazioni_base'],
                'presentazione_breve' => ['form' => false, 'view' => false, 'referToBox' => 'box_informazioni_base'],
                'presentazione_personale' => ['form' => false, 'view' => false, 'referToBox' => 'box_presentazione_personale'],
                'privacy' => ['form' => true, 'view' => true, 'referToBox' => 'box_privacy'],
                'sesso' => ['form' => false, 'view' => false, 'referToBox' => 'box_informazioni_base'],
                'telefono' => ['form' => false, 'view' => false, 'referToBox' => 'box_dati_contatto'],
                'twitter' => ['form' => true, 'view' => true, 'referToBox' => 'box_social_account'],
                'ultimo_accesso' => ['form' => true, 'view' => true, 'referToBox' => 'box_account_data'],
                'ultimo_logout' => ['form' => true, 'view' => true, 'referToBox' => 'box_account_data'],
                'username' => ['form' => true, 'view' => false, 'referToBox' => 'box_dati_accesso'],
                'user_profile_age_group_id' => ['form' => false, 'view' => false, 'referToBox' => 'box_informazioni_base'],
                'user_profile_area_id' => ['form' => false, 'view' => false, 'referToBox' => 'box_role_and_area'],
                'userProfileImage' => ['form' => true, 'view' => true, 'referToBox' => 'box_foto'],
                'user_profile_role_id' => ['form' => false, 'view' => false, 'referToBox' => 'box_role_and_area'],
				'prevalent_partnership_id' => ['form' => true, 'view' => true, 'referToBox' => 'box_prevalent_partnership'],
            ]
        ]
    ],
    'attachments' => [
        'class' => 'open20\amos\attachments\FileModule',
        'webDir' => 'files',
        'tempPath' => '@common/uploads/temp',
        'storePath' => '@common/uploads/store',
        'cache_age' => '2592000',
        'disableGallery' => false,
        'showLuyaGallery' => true,
        'enableDatabankFile' => true,
        'luyaGalleryFolderId' => 3,
        'luyaDatabankFileFolderId' => 1,
        'autoSaveInDatabanks' => true,
        'shutterstockConfigs' => [
            'enable' => false,
            'enableSandbox' => true,
        ],
        'categorizationEnabled' => true,
        'categorizationModelsEnabled' => [
            'open20\amos\news\models\News',
            'open20\amos\discussioni\models\DiscussioniTopic',
            'open20\prodottiservizi\models\ProdottiServizi'
        ],
    ],
    'audit' => [
        'class' => 'open20\amos\audit\Audit',
        'db' => 'db',
        'accessRoles' => ['ADMIN'],
        'ignoreActions' => [
            '*',
        ],
    ],
    'chrono' => [
        'class' => 'amos\chrono\Module',
    ],
    'community' => [
        'class' => 'open20\amos\community\AmosCommunity',
        'showSubcommunitiesWidget' => true,
        'showSubcommunities' => true,
        'enableConfigureCommunityDashboard' => true,
        'forceWorkflowSingleCommunity' => true,
        'hideContentsModels' => [
        ],
        'enableOpenJoin' => true,
        'activateChat' => true,
        'activateLinks' => true,
    ],
    'comuni' => [
        'class' => 'open20\amos\comuni\AmosComuni',
    ],
    'cwh' => [
        'class' => 'open20\amos\cwh\AmosCwh',
        'cached' => false,
        'regolaPubblicazioneFilter' => false,
        'enabelDefaultByPrevalentPartnership' => true,
    ],
    'dashboard' => [
        'class' => 'open20\amos\dashboard\AmosDashboard',
    ],
    'documenti' => [
        'class' => 'open20\amos\documenti\AmosDocumenti',
        'cmsSync' => true,
        'params' => [
            'site_featured_enabled' => true,
            'site_publish_enabled' => true,
            'containerFullWidth' => true
        ],
        'enableCategories' => true,
        'enableDocumentVersioning' => true,
        'requireModalMoveFile' => false,
        'openInFrontEnd' => true,
        'searchByTags' => true,
        'enableFolders' => true,
        'enableDragAndDrop' => false,
        'hidePubblicationDate' => true,
        'enableExtensionFilter' => false,
        'enableFoldersFilter' => false
    ],
    /*'onlyoffice' => [
        'class' => '\open20\onlyoffice\Module',
        'docServSiteUrl' => '',
        'docServJwtSecret' => '',
        'keyFilePrefixName' => 'open2',
    ],*/
    'events' => [
        'class' => 'open20\amos\events\AmosEvents',
        'searchByTags' => true,
        'dropdownEventTypeDisabled' => true,
        'params' => [
            'site_featured_enabled' => true,
            'site_publish_enabled' => true
        ],
        'enableGdpr' => false,
        'enableCommunitySections' => false,
        'enableQrCode' => false,
        'enableTickets' => false,
        'enableFiscalCode' => false,
    ],
    'videoconference' => [
        'class' => 'open20\amos\videoconference\AmosVideoconference',
        'rbacEnabled' => false,
        'jitsiDomain' => 'meet.test.demotestwip.it',
        'onlyNetworkUsers' => false
    ],
    'sondaggi' => [
        'class' => 'open20\amos\sondaggi\AmosSondaggi',
        'enableFrontendCompilation' => true,
    ],
    'podcast' => [
        'class' => 'amos\podcast\Module',
        'canCreateOnlyCommunityManager' => true,
        'basicUserCannotCreate' => true
    ],
    'comments' => [
        'class' => 'open20\amos\comments\AmosComments',
        'enableNotifyCommentForDiscussions' => false,
        'modelsEnabled' => [
            'open20\amos\events\models\Event',
            'open20\amos\news\models\News',
            'open20\amos\documenti\models\Documenti',
            'open20\amos\discussioni\models\DiscussioniTopic',
        ],
        'enableBlacklistWordsFilter' => true,
        'enableModeration' => true,
        'bellNotificationEnabledClasses' => [
            'open20\amos\community\models\Community',
            'open20\amos\news\models\News',
            'open20\amos\discussioni\models\DiscussioniTopic',
            'open20\amos\events\models\Event',
            'open20\amos\documenti\models\Documenti',
        ],
        'showBellIconInContentView' => true,
    ],
    'email' => [
        'class' => 'open20\amos\emailmanager\AmosEmail',
        'templatePath' => '@common/mail/emails',
    ],
    'news' => [
        'class' => 'open20\amos\news\AmosNews',
        'modelMap' => [
            'NewsSearch' => 'frontend\modules\flnews\models\search\NewsSearch'
        ],
        'hidePubblicationDate' => false,
        'newsRequiredFields' => [
            'titolo',
            'status',
            'descrizione',
        ],
        'params' => [
            'site_featured_enabled' => true,
            'site_publish_enabled' => true,
            'publication_always_enabled' => true,
        ],
        'defaultEnableComments' => false,
        'useFrontendView' => false,
        'enableMap' => true,
        'usePrettyUrl' => true,
        'enableOnlyRedactional' => false,
        'showCategory' => true,
        'addTags' => false,
        'rteToolbar' => "styleselect | bold italic strikethrough | link unlink image | alignleft aligncenter | numlist bullist outdent indent | removeformat",
        'dateFormatNews' => \kartik\datecontrol\Module::FORMAT_DATETIME,
        'enableLikeWidget' => true,
        'enableCustomStatusLabel' => true,
        'viewPathEmailSummary' => [
            'open20\amos\news\models\News' => '@frontend/modules/flnews/views/email/notify_summary'
        ]
    ],
    'notify' => [
        'class' => 'open20\amos\notificationmanager\AmosNotify',
        'batchFromDate' => '2023-09-01',
        'confirmEmailNotification' => true,
    ],
    'organizzazioni' => [
        'class' => 'open20\amos\organizzazioni\Module',
        'enabled_widget_sedi' => false,
        'enabled_widget_organizzazioni' => true,
        'enableSocial' => true,
        'enableCommunityCreation' => true,
        'createCommunityAutomatically' => true,
        'employeesListView' => 'icon'
    ],
    
    'favorites' => [
        'class' => 'open20\amos\favorites\AmosFavorites',
		'enableFavoritesUrl' => true,
		'modelsEnabledFavoritesUrl' => [
            'open20\amos\discussioni\models\DiscussioniTopic',
            'open20\amos\documenti\models\Documenti',
            'open20\amos\news\models\News',
        ]
    ],
    'privileges' => [
        'class' => 'open20\amos\privileges\AmosPrivileges',
    ],
    'video' => [
        'class' => 'open20\video\Module',
    ],
    
    'gamification' => [
        'class' => 'open20\gamification\Module',
    ],
    'prodotti-e-servizi' => [
        'class' => 'open20\prodottiservizi\Module',
    ],
    'schema' => [
        'class' => 'simialbi\yii2\schemaorg\Module',
    ],
    'seo' => [
        'class' => 'open20\amos\seo\AmosSeo',
        'modelsEnabled' => [
        ],
    ],
    'socialauth' => [
        'class' => 'open20\amos\socialauth\Module',
        'enableLogin' => true,
        'enableLink' => false,
        'enableRegister' => true,
        'enableSpid' => false,
        'shibbolethAutoLogin' => false,
        'shibbolethAutoRegistration' => false,
        'userOverload' => true,
        'disableAssociationByEmail' => true,
        'providers' => [
            "Facebook" => [
                "enabled" => true,
                "keys" => [
                    "id" => "YOUR_ID",
                    "secret" => "YOUR_SECRET"
                ],
                "scope" => "email"
            ],
        ],
    ],
    'ticket' => [
        'class' => 'open2\amos\ticket\AmosTicket',
        'disableTicketOrganization' => true,
        'categoryReferentsHide' => ['true'],
        'name' => 'FAQ',
        'categoryFieldsHide' => ['abilita_ticket', 'tecnica', 'enable_dossier_id', 'enable_phone', 'abilita_per_community'],
        'controllerMap' => [
            'default' => 'frontend\modules\ticket\controllers\DefaultController',
            'assistenza' => 'frontend\modules\ticket\controllers\AssistenzaController',
            'ticket-categorie' => 'frontend\modules\ticket\controllers\TicketCategorieController',
            'ticket-faq' => 'frontend\modules\ticket\controllers\TicketFaqController',
        ],
    ],
    'tag' => [
        'class' => 'open20\amos\tag\AmosTag',
        'modelsEnabled' => [
            'open20\amos\news\models\News',
            'open20\amos\discussioni\models\DiscussioniTopic',
            'open20\amos\documenti\models\Documenti',
            'open20\amos\events\models\Event',
            'open20\amos\community\models\Community',
            'open20\amos\organizzazioni\models\Profilo',
            'open20\amos\organizzazioni\models\ProfiloSedi',
            'amos\podcast\models\PodcastEpisode',
            'open20\amos\admin\models\UserProfile',
            'open20\amos\community\models\Bookmarks',
        ],
        'modelsTagFree' => [
            'app\modules\cms\models\Nav' => 1,
            'open20\amos\news\models\News' => 2,
        ],
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
        'phpTranslators' => [// list of the php function for translating messages.
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
        'tables' => [// Properties of individual tables
            [
                'connection' => 'db', // connection identifier
                'table' => '{{%language}}', // table name
                'columns' => ['name', 'name_ascii'], // names of multilingual fields
                'category' => 'database-table-name', // the category is the database table name
            ]
        ],
    ],
    'translation' => [
        'class' => 'open20\amos\translation\AmosTranslation',
        'modelNs' => 'frontend\models\translations',
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
                /*'translationContents' => [
                    'classBehavior' => \open20\amos\translation\behaviors\TranslateableBehavior::className(),
                    'models' => [
                        ['namespace' => 'open20\amos\tag\models\Tag',
                            //'connection' => 'db', //if not set it use 'db'
                            //'classBehavior' => NULL,//if not set it use default classBehavior 'open20\amos\translation\behaviors\TranslateableBehavior'
                            'enableWorkflow' => FALSE, //if not set it use default configuration of the plugin
                            'attributes' => ['nome', 'descrizione'],
                            'plugin' => 'tag'
                        ],
                    ],
                ],*/
            ],
        ],
        'module_translation_labels' => 'translatemanager',
        'module_translation_labels_options' => ['roles' => ['ADMIN', 'CONTENT_TRANSLATOR'],
            'root' => [
                '@vendor/amos/',
                '@vendor/openinnovation/',
                '@vendor/',
                '@app',
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
    ],
    'utility' => [
        'class' => 'open20\amos\utility\Module',
        'params' => [
            'master_domain' => 'https://deployer.open20.it'
        ]
    ],
    'FLcommands' => [
        'class' => 'frontend\modules\FLcommands\Module'
    ],
    'userprofiling' => [
        'class' => 'frontend\modules\userprofiling\Module'
    ]
];
