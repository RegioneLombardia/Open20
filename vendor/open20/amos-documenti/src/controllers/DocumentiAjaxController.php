<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\controllers
 * @category   CategoryName
 */

namespace open20\amos\documenti\controllers;

use open20\amos\admin\models\UserProfile;
use open20\amos\attachments\models\File;
use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\models\search\CommunitySearch;
use open20\amos\cwh\models\CwhRegolePubblicazione;
use open20\amos\cwh\query\CwhActiveQuery;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\utility\DocumentsUtility;
use Yii;
use yii\base\Controller;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

class DocumentiAjaxController extends Controller
{

    public $moduleCwh;

    private $parentId = null;

    CONST DOCUMENTI_URL = '/documenti/documenti/view';

    /**
     * @var AmosDocumenti $documentsModule
     */
    public $documentsModule = null;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->documentsModule = Yii::$app->getModule(AmosDocumenti::getModuleName());
        $this->moduleCwh = Yii::$app->getModule('cwh');
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'get-documents',
                            'get-folders',
                            'create-folder',
                            'get-translations-and-options',
                            'delete-model',
                            'delete-community',
                            'get-aree',
                            'get-subcommunities',
                        ],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'sessions',
                            'reset-sessions',
                        ],
                        'roles' => ['ADMIN']
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'get-subcommunities' => ['post', 'get']
                ]
            ]
        ]);

        return $behaviors;
    }

    /**
     * @param null $scope
     * @return string
     */
    public function actionGetTranslationsAndOptions($scope = null)
    {
        $array = [];
        $array['translations'] = [
            'LABEL--CREATE-NEW-FOLDER' => AmosDocumenti::t('amosdocumenti', 'Nuova Cartella'),
            'LABEL--UPLOAD-NEW-FILES' => AmosDocumenti::t('amosdocumenti', 'Caricamento File'),
            'LABEL--UPLOAD-MULTI-FILES' => AmosDocumenti::t('amosdocumenti', 'Caricamento Multiplo'),
            'LABEL--CONDIVISI-CON-ME' => AmosDocumenti::t('amosdocumenti', 'Condivisi con me'),
            'ERROR--NOME-CARTELLA-NON-VUOTO' => AmosDocumenti::t('amosdocumenti', 'Inserire un nome per la cartella'),
        ];

        $array['foldersOptions'] = [
            'rename' => ['name' => AmosDocumenti::t('amosdocumenti', 'Rinomina')], //MEV
            'sep1' => '---------', //MEV
            'open' => ['name' => AmosDocumenti::t('amosdocumenti', 'Visualizza informazioni')],
            'edit' => ['name' => AmosDocumenti::t('amosdocumenti', 'Modifica informazioni')],
            'sep2' => '---------',
            'delete' => ['name' => AmosDocumenti::t('amosdocumenti', 'Rimuovi')],
        ];

        $array['documentsOptions'] = [
            'rename' => ['name' => AmosDocumenti::t('amosdocumenti', 'Rinomina')], //MEV
            'sep1' => '---------', //MEV
            'open' => ['name' => AmosDocumenti::t('amosdocumenti', 'Visualizza informazioni')],
            'edit' => ['name' => AmosDocumenti::t('amosdocumenti', 'Modifica informazioni')],
            'sep2' => '---------',
            'upload' => ['name' => AmosDocumenti::t('amosdocumenti', 'Carica nuova versione')],
            'download' => ['name' => AmosDocumenti::t('amosdocumenti', 'Scarica')],
            'sep3' => '---------',
            'import' => ['name' => AmosDocumenti::t('amosdocumenti', 'Importa Documenti')],
            'sep3' => '---------',
            'delete' => ['name' => AmosDocumenti::t('amosdocumenti', 'Rimuovi')],
        ];

        return Json::encode($array);
    }

    /**
     * @return string
     * @throws Yii\db\StaleObjectException
     * @throws Yii\web\NotFoundHttpException
     */
    public function actionDeleteModel()
    {
        $postParams = $this->isPostActions();

        /** @var Documenti $documentiModel */
        $documentiModel = $this->documentsModule->createModel('Documenti');
        $documenti = $documentiModel::findOne(['id' => $postParams['model-id']]);
        if ($documenti) {
            if (Yii::$app->user->can('DOCUMENTI_DELETE', ['model' => $documenti])) {
                return Json::encode($this->deleteFileOrFolder($postParams['model-id'], true));
            }

            return Json::encode([
                'success' => false,
                'message' => AmosDocumenti::t('amosdocumenti', 'Non sei autorizzato a cancellare il documento.'),
            ]);
        }

        return Json::encode([
            'success' => false,
            'message' => AmosDocumenti::t('amosdocumenti', 'Documento non più presente.'),
        ]);
    }

    /**
     * Deletes an existing Documenti model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return yii\web\Response
     * @throws \Exception
     * @throws yii\db\StaleObjectException
     * @throws yii\web\NotFoundHttpException
     */
    private function deleteFileOrFolder($id, $isAjaxRequest = false)
    {
        /** @var Documenti $documentiModel */
        $documentiModel = $this->documentsModule->createModel('Documenti');
        $model = $documentiModel::findOne(['id' => $id]);
        if ($model->is_folder) {
            $relatedDocuments = $documentiModel::findAll(['parent_id' => $id]);
            if (count($relatedDocuments) > 0) {
                if ($isAjaxRequest) {
                    return [
                        'success' => false,
                        'message' => AmosDocumenti::t('amosdocumenti', 'La cartella selezionata non è vuota. Per eliminarla, eliminare tutti i file contenuti.'),
                    ];
                }
                Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'La cartella selezionata non è vuota. Per eliminarla, eliminare tutti i file contenuti.'));

                return $this->redirect(Url::previous('index'));
            }
        }

        $model->delete();
        if (!$model->getErrors()) {
            if ($isAjaxRequest) {
                return [
                    'success' => true,
                ];
            }
            Yii::$app->getSession()->addFlash('success', AmosDocumenti::tHtml('amosdocumenti', 'Documento cancellato correttamente.'));
        } else {
            if ($isAjaxRequest) {
                return [
                    'success' => false,
                    'message' => AmosDocumenti::t('amosdocumenti', 'Non sei autorizzato a cancellare il documento.'),
                ];
            }
            Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Non sei autorizzato a cancellare il documento.'));
        }

        return $this->redirect(Url::previous('index'));
    }

    /**
     *
     * @return type
     */
    public function actionDeleteCommunity()
    {
        $postParams = $this->isPostActions();

        $community = Community::findOne(['id' => $postParams['model-id']]);
        if ($community) {
            if (Yii::$app->user->can('COMMUNITY_DELETE', ['model' => $community])) {
                return Json::encode($this->deleteCommunity($postParams['model-id']));
            }

            return Json::encode([
                'success' => false,
                'message' => AmosCommunity::t('amoscommunity', 'Non hai il permesso di eliminare la stanza.'),
            ]);
        }

        return Json::encode([
            'success' => false,
            'message' => AmosCommunity::t('amoscommunity', 'Community not found.'),
        ]);
    }

    /**
     * Deletes an existing Community model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * in Community model beforeDelete is overwritten to allow deletion of related models
     * @param integer $id
     * @return array
     *
     */
    private function deleteCommunity($id)
    {
        /** @var Community $model */
        $model = Community::findOne(['id' => $id]);
        if ($model) {
            if (Yii::$app->user->can('COMMUNITY_DELETE', ['model' => $model])) {
                try {
                    $model->delete();
                    return ['success' => true,];
                } catch (\Exception $exception) {
                    return [
                        'success' => false,
                        'message' => AmosCommunity::t('amoscommunity', $exception->getMessage()),
                    ];
                }
            }

            return [
                'success' => false,
                'message' => AmosCommunity::t('amoscommunity', 'Non hai il permesso di eliminare la stanza.'),
            ];
        }


        return [
            'success' => false,
            'message' => AmosCommunity::t('amoscommunity', 'Community not found.'),
        ];
    }

    /**
     * TODO MEV
     */
    public function actionRenameModel()
    {
    }

    /**
     *
     * @return type
     */
    public function actionGetAree()
    {
        $resetScope = Yii::$app->request->get('resetScope');
        if ($resetScope === 'true') {
            Yii::$app->session->set('stanzePath', []);
            Yii::$app->session->set('foldersPath', []);

            if ($this->isSetCwh()) {
                $this->moduleCwh->resetCwhScopeInSession();
            }
        }

        $communitySearch = new CommunitySearch();

        /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
        $scope = null;
        if ($this->isSetCwh()) {
            $scope = $this->moduleCwh->getCwhScope();
        }

        if (!empty($scope)) {
            $scopeId = $scope['community'];
            $parentId = null;
            if (array_key_exists('links', Yii::$app->session->get('foldersPath', []))) {
                if (sizeof(Yii::$app->session->get('foldersPath', [])['links']) > 0) {
                    $links = Yii::$app->session->get('foldersPath', [])['links'];
                    if (isset($links[0])) {
                        $links = array_shift($links);
                    }

                    $parentId = (($links['model-id'] == '') ? null : $links['model-id']);
                } else {
                    Yii::$app->session->set('foldersPath', [
                        'links' => [
                            [
                                'classes' => '',
                                'model-id' => '',
                                'name' => Community::findOne(['id' => $scopeId])->name
                            ],
                        ]
                    ]);
                }
            }

            $forceReset = false;
            if (empty(Community::findOne(['id' => $scopeId]))) {
                if ($this->isSetCwh()) {
                    $this->moduleCwh->resetCwhScopeInSession();
                }
                Yii::$app->session->set('stanzePath', []);
                Yii::$app->session->set('foldersPath', []);
                $forceReset = true;
                $scope = null;
                $parentId = null;
            } else {
                if (empty(Yii::$app->session->get('stanzePath', []))) {
                    $commName = Community::findOne(['id' => $scopeId])->name;
                    Yii::$app->session->set('stanzePath', [
                        [
                            'name' => $commName,
                            'scope_id' => $scopeId,
                            'isArea' => false,
                            'isRootPathStanze' => true
                        ]
                    ]);
                    Yii::$app->session->set('foldersPath', [
                        [
                            'links' => [
                                [
                                    'classes' => '',
                                    'model-id' => '',
                                    'name' => $commName
                                ]
                            ]
                        ]
                    ]);
                }
            }

            return Json::encode([
                'forceReset' => $forceReset,
                'insideSubcommunity' => true,
                'scope' => $scopeId,
                'parentId' => $parentId,
                'routeStanze' => Yii::$app->session->get('stanzePath', []),
                'breadcrumbFolders' => Yii::$app->session->get('foldersPath', [])
            ]);
        }


        $result = [
            'isArea' => true,
            'canCreate' => Yii::$app->getUser()->can('COMMUNITY_CREATE'),
        ];


        /** @var Community $area */
        $aree = $communitySearch->buildQuery(
            [],
            (Yii::$app->getUser()->can('ADMIN')
                ? 'admin-all'
                : 'all'
            )
        )
            ->orderBy('name')
            ->all();

        foreach ($aree as $area) {
            $permissions['move'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Sposta')];
            $permissions['rename'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Rinomina')];
            $permissions['open'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Visualizza informazioni')];

            if (Yii::$app->getUser()->can('COMMUNITY_UPDATE', ['model' => $area])) {
                $permissions['edit'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Modifica informazioni')];
            }

            // Da abilitare se e quando l'importatore (con node?) verra' abilitato
            /* if(Yii::$app->getUser()->can('ADMIN')) {
              $permissions['import'] = ['name' =>  AmosDocumenti::t('amosdocumenti','Importa Documenti')];
              } */

            if ($area->hasRole(Yii::$app->user->id, [
                    CommunityUserMm::ROLE_COMMUNITY_MANAGER,
                    CommunityUserMm::ROLE_EDITOR,
                    CommunityUserMm::ROLE_AUTHOR,
                ])
                || Yii::$app->getUser()->can('ADMIN')) {
                $permissions['sep2'] = '---------';
            }

            if ($area->hasRole(Yii::$app->user->id, [
                    CommunityUserMm::ROLE_COMMUNITY_MANAGER,
                ])
                || Yii::$app->getUser()->can('ADMIN')) {
                $permissions['participants'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Condividi con...')];
            }

//            if($area->hasRole(Yii::$app->user->id, [
//                    CommunityUserMm::ROLE_COMMUNITY_MANAGER,
//                    CommunityUserMm::ROLE_EDITOR,
//                    CommunityUserMm::ROLE_AUTHOR,
//                ]) ||
//                Yii::$app->getUser()->can('ADMIN')) {
//                $permissions['sharingGroups'] = ['name' => AmosDocumenti::t('amosdocumenti','Gruppi di condivisione')];
//            }

            if ($area->isCommunityManager() || Yii::$app->getUser()->can('COMMUNITY_DELETE')) {
                $permissions['sep4'] = '---------';
                $permissions['delete'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Rimuovi')];
            }

            $permissions['sep5'] = '---------';
            $permissions['cooperation'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Collaborazione')];

            $result['areas'][] = [
                'name' => $area->name,
                'description' => strlen(strip_tags($area->description)) > 100
                    ? substr(strip_tags($area->description), 0, 99) . '...'
                    : strip_tags($area->description),
                'id' => $area->id,
                'permissions' => $permissions,
            ];
        }

        return Json::encode($result);
    }

    /**
     *
     * @return type
     */
    public function actionGetSubcommunities()
    {
        $idArea = Yii::$app->request->get('idArea');
        $routeStanze = Yii::$app->request->get('routeStanze');
        $routeStanze = Json::decode($routeStanze);
        $removeStanza = Yii::$app->request->get('removeStanza');

        $this->setScope($idArea);

        $currentCommunity = Community::findOne(['id' => $idArea]);
        $currentCommunityName = CommunitySearch::findOne(['id' => $idArea])->name;

        if ($removeStanza !== 'true') {
            $routeStanze[] = [
                'name' => $currentCommunityName,
                'scope_id' => $idArea,
                'isArea' => true,
            ];
        }

        Yii::$app->session->set('stanzePath', $routeStanze);

        $result = [
            'current-community-name' => $currentCommunityName,
            'isArea' => false,
            'canCreate' => Yii::$app->getUser()->can('COMMUNITY_CREATE', ['model' => CommunitySearch::findOne(['id' => $idArea])]),
        ];

        $communitySearch = new CommunitySearch();
        $communitySearch->subcommunityMode = true;

        if (empty($currentCommunity)) {
            if ($this->isSetCwh()) {
                $this->moduleCwh->resetCwhScopeInSession();
            }
            Yii::$app->session->set('stanzePath', []);
            Yii::$app->session->set('foldersPath', []);

            return Json::encode([
                'forceReset' => true,
                'routeStanze' => Yii::$app->session->get('stanzePath', []),
                'breadcrumbFolders' => Yii::$app->session->get('foldersPath', [])
            ]);
        }

        $subcommunitiesQuery = $currentCommunity->getSubcommunities();
        if (!$currentCommunity->isCommunityManager()) {
            $subcommunitiesQuery->joinWith('communityUsers')->andWhere([CommunityUserMm::tableName() . '.user_id' => Yii::$app->user->id]);
        }

        //foreach($communitySearch->buildQuery('all',[])->orderBy('name')->all() as $subcommunity) {
        $subCommunities = $subcommunitiesQuery->all();
        foreach ($subCommunities as $subcommunity) {
            $permissions = [
                'open' => ['name' => AmosDocumenti::t('amosdocumenti', 'Visualizza informazioni')],
            ];

            if (Yii::$app->getUser()->can('COMMUNITY_UPDATE', ['model' => $subcommunity])) {
                $permissions['edit'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Modifica informazioni')];
            }

            // Da abilitare se e quando l'importatore (con node?) verra' abilitato
            /* if (Yii::$app->getUser()->can('ADMIN')) {
                $permissions['import'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Importa Documenti')];
                } */

            $showSep2 = false;
            if ($subcommunity->hasRole(Yii::$app->user->id, [
                    CommunityUserMm::ROLE_COMMUNITY_MANAGER,
                    CommunityUserMm::ROLE_EDITOR,
                    CommunityUserMm::ROLE_AUTHOR,
                ])
                || Yii::$app->getUser()->can('ADMIN')) {
                $showSep2 = true;
            }

            if ($showSep2) {
                $permissions['sep2'] = '---------';
            }

            if ($subcommunity->hasRole(Yii::$app->user->id, [
                    CommunityUserMm::ROLE_COMMUNITY_MANAGER,
                ])
                || Yii::$app->getUser()->can('ADMIN')) {
                $permissions['participants'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Condividi con...')];
            }

//                if ($subcommunity->hasRole(Yii::$app->user->id, [
//                        CommunityUserMm::ROLE_COMMUNITY_MANAGER,
//                        CommunityUserMm::ROLE_EDITOR,
//                        CommunityUserMm::ROLE_AUTHOR,
//                    ]) ||
//                    Yii::$app->getUser()->can('ADMIN')) {
//                    $permissions['sharingGroups'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Gruppi di condivisione')];
//                }

            if (Yii::$app->getUser()->can('COMMUNITY_DELETE', ['model' => $subcommunity])) {
                $permissions['sep4'] = '---------';
                $permissions['delete'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Rimuovi')];
            }

            $permissions['sep5'] = '---------';
            $permissions['cooperation'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Collaborazione')];

            $result['subcommunities'][] = [
                'name' => $subcommunity->name,
                'id' => $subcommunity->id,
                'description' => strlen(strip_tags($subcommunity->description)) > 100
                    ? substr(strip_tags($subcommunity->description), 0, 99) . '...'
                    : strip_tags($subcommunity->description),
                'permissions' => $permissions,
            ];
        }

        return Json::encode($result);
    }

    /**
     *
     * @return type
     */
    public function actionCreateFolder()
    {
        $postParams = $this->isPostActions();

        $documentiClassName = $this->documentsModule->model('Documenti');
        $cwhActiveQuery = new CwhActiveQuery($documentiClassName);
        $queryUsers = $cwhActiveQuery
            ->getRecipients(
                CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS,
                [],
                [Community::tableName() . '-' . $postParams['scope']]
            );

        $queryDestinatari = UserProfile::find()->andWhere(
            [
                'in',
                'user_id',
                $queryUsers->select('user.id')->asArray()->column()
            ])
            ->all();

        $idDestinatari = [];
        foreach ($queryDestinatari as $destinatario) {
            $idDestinatari[] = $destinatario->id;
        }

        Yii::$app->request->setBodyParams([
            Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
            'Documenti' => [
                'destinatari' => [
                    Community::tableName() . '-' . $postParams['scope'],
                ],
                'titolo' => $postParams['folder-name'],
                'regola_pubblicazione' => CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS,
            ],
            'selection-profiles' => $idDestinatari,
        ]);

        return Json::encode(
            Yii::$app->runAction('documenti/documenti/create',
                [
                    'isFolder' => true,
                    'isAjaxRequest' => true,
                    'regolaPubblicazione' => CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS,
                    'parentId' => $this->parentId
                ]
            )
        );
    }

    /**
     *
     * @param type $scopeId
     */
    public function setScope($scopeId)
    {
        $this->moduleCwh->setCwhScopeInSession([
            'community' => $scopeId, // simple cwh scope for contents filtering, required
        ],
            [
                // cwhRelation array specifying name of relation table, name of entity field on relation table and entity id field ,
                // optional for compatibility with previous versions
                'mm_name' => 'community_user_mm',
                'entity_id_field' => 'community_id',
                'entity_id' => $scopeId
            ]);
    }

    /**
     *
     * @return type
     */
    public function actionGetFolders()
    {
        $post = $this->isPostActions();

        $this->setScope($post['scope-id']);

        $routeFolders = JSON::decode($post['foldersPath']);
//        if(empty($routeFolders)) {
//            $routeFolders['links'][] = [
//                'classes' => "",
//                'model-id' => $post['parent-id'],
//                'name' => "prova",
//            ];
//        }

        Yii::$app->session->set('foldersPath', $routeFolders);

        /** @var ActiveQuery $folders */
        $folders = $this->getDataProviderFolders();

        /** @var Documenti $documentiModel */
        $documentiModel = $this->documentsModule->createModel('Documenti');
        $foldersFound = [
            'count' => $folders->count,
            'available' => $folders->count != 0,
            'folders' => [],
            'canCreate' => (
                Yii::$app->getUser()->can('DOCUMENTI_CREATE', ['model' => $documentiModel])
                && $this->documentsModule->enableFolders == true
            ),
        ];

        // PER VERIFICARE IL RUOLO DELL'UTENTE NELLA COMMUNITY/STANZA/AREA IN CUI CI SI TROVA
        // ED EVENTUALMENTE MOSTRARE SOLO CERTE OPZIONI NEI MENU CONTESTUALI
        // (puo tornare utile? :-) )
//        $scope = null;
//        $isCurrentUserCommunityManager = false;
//        if (!empty($moduleCwh)) {
//            $scope = $moduleCwh->getCwhScope();
//        }
//        if (!empty($scope)) {
//            $currentCommunity = CommunitySearch::findOne(['id' => $idArea]);
//            $isCurrentUserCommunityManager = $currentCommunity->hasRole(Yii::$app->user->id, [
//                CommunityUserMm::ROLE_COMMUNITY_MANAGER,
//            ]);
//        }

        /** @var Documenti $folder */
        $foldersModels = $folders->getModels();
        foreach ($foldersModels as $folder) {
            //$folder->par
            $permissions = [
                'open' => ['name' => AmosDocumenti::t('amosdocumenti', 'Visualizza informazioni')],
            ];

            if (Yii::$app->getUser()->can('DOCUMENTI_UPDATE', ['model' => $folder])) {
                $permissions['edit'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Modifica informazioni')];
            }

            if (Yii::$app->getUser()->can('DOCUMENTI_DELETE', ['model' => $folder])) {
                $permissions['sep4'] = "---------";
                $permissions['delete'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Rimuovi')];
            }

            $foldersFound['folders'][] = [
                'model-id' => $folder->id,
                'name' => $folder->titolo,
                'permissions' => $permissions,
            ];
        }

        return Json::encode($foldersFound);
    }

    /**
     *
     * @return type
     */
    private function isPostActions()
    {
        $postParams = Yii::$app->request->post();

        if ($postParams) {
            if (array_key_exists('parent-id', $postParams) && $postParams['parent-id'] != "") {
                $this->parentId = $postParams['parent-id'];
            }
        }

        return $postParams;
    }

    /**
     *
     * @return type
     */
    public function actionGetDocuments()
    {
        $post = $this->isPostActions();

        $this->setScope($post['scope-id']);

        /** @var ActiveQuery $folders */
        $files = $this->getDataProviderDocuments();

        /** @var Documenti $documentiModel */
        $documentiModel = $this->documentsModule->createModel('Documenti');
        $filesFound = [
            'count' => $files->count,
            'available' => $files->count != 0,
            'files' => [],
            'canCreate' => Yii::$app->getUser()->can('DOCUMENTI_CREATE', ['model' => $documentiModel]),
        ];

        // PER VERIFICARE IL RUOLO DELL'UTENTE NELLA COMMUNITY/STANZA/AREA IN CUI CI SI TROVA
        // ED EVENTUALMENTE MOSTRARE SOLO CERTE OPZIONI NEI MENU CONTESTUALI
        // (puo tornare utile? :-) )
//        $scope = null;
//        $isCurrentUserCommunityManager = false;
//        if (!empty($moduleCwh)) {
//            $scope = $moduleCwh->getCwhScope();
//        }
//        if (!empty($scope)) {
//            $currentCommunity = CommunitySearch::findOne(['id' => $idArea]);
//            $isCurrentUserCommunityManager = $currentCommunity->hasRole(Yii::$app->user->id, [
//                CommunityUserMm::ROLE_COMMUNITY_MANAGER,
//            ]);
//        }

        foreach ($files->getModels() as $file) {
            $permissions = [
                'open' => ['name' => AmosDocumenti::t('amosdocumenti', 'Visualizza informazioni')],
            ];

            if (Yii::$app->getUser()->can('DOCUMENTI_UPDATE', ['model' => $file])) {
                if (Yii::$app->getModule('documenti')->enableDocumentVersioning) {
                    $permissions['new-version'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Crea nuova versione')];
                } else {
                    $permissions['edit'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Modifica informazioni')];
                }
            }

            $permissions['sep4'] = '---------';
            $permissions['download'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Scarica documento')];

            if (Yii::$app->getUser()->can('DOCUMENTI_DELETE', ['model' => $file])) {
                $permissions['sep5'] = '---------';
                $permissions['delete'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Rimuovi')];
            }

            $link = '';
            $title = $file->titolo;
            if (!empty($file->link_document)) {
                $link = \open20\amos\core\utilities\StringUtils::shortText($file->link_document, 50);
            }

            $filesFound['files'][] = [
                'link' => $link,
                'name' => $title,
                'icon-class' => DocumentsUtility::getDocumentIcon($file, true),
                'url' => Url::toRoute([self::DOCUMENTI_URL, 'id' => $file->id]),
                'date' => date('d/m/Y', strtotime((isset($file->updated_at) && $file->updated_at != "") ? $file->updated_at : $file->created_at)),
                'size' => empty($file->link_document)
                    ? $this->getSize($file->getDocumentMainFile()->size)
                    : null,
                'model-id' => $file->id,
                'model-file-id' => empty($file->link_document)
                    ? $file->getDocumentMainFile()->id
                    : null,
                'model-hash' => empty($file->link_document)
                    ? $file->getDocumentMainFile()->hash
                    : null,
                'permissions' => $permissions,
            ];
        }

        return Json::encode($filesFound);
    }

    /**
     *
     * @param type $size
     * @return type
     */
    private function getSize($size)
    {
        $dimScale = ['b', 'kb', 'mb', 'gb', 'tb'];
        $size = intval($size);
        $power = $size > 0 ? floor(log($size, 1024)) : 0;

        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $dimScale[$power];
    }

    /**
     * @return ActiveQuery
     */
    private function baseQuery()
    {
        /** @var Documenti $documentiModel */
        $documentiModel = $this->documentsModule->createModel('Documenti');
        /** @var ActiveQuery $query */
        return $this->addCwhQuery(
            $documentiModel::find()
                ->distinct()
                ->andWhere(['parent_id' => $this->parentId])
        );
    }

    /**
     * @param ActiveQuery $query
     * @return ActiveQuery
     */
    private function addCwhQuery($query)
    {
        $cwhActiveQuery = null;
        $classname = $this->documentsModule->model('Documenti');
        if ($this->isSetCwh()) {
            /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
            $this->moduleCwh->setCwhScopeFromSession();
            $cwhActiveQuery = new CwhActiveQuery($classname, ['queryBase' => $query]);
        }

        if ($this->isSetCwh($classname)) {
            $query = $cwhActiveQuery->getQueryCwhAll();
        }

        return $query;
    }

    /**
     * @param string $classname
     * @return bool
     */
    private function isSetCwh($classname = null)
    {
        if ($classname == null) {
            return isset($this->moduleCwh);
        }

        return (isset($this->moduleCwh) && in_array($classname, $this->moduleCwh->modelsEnabled));
    }

    /**
     * @return ActiveDataProvider
     */
    private function getDataProvider($isFolderField)
    {
        return new ActiveDataProvider([
            'query' => $this
                ->baseQuery()
                ->andWhere(['is_folder' => $isFolderField]),
            'sort' => [
                'defaultOrder' => [
                    'titolo' => SORT_ASC
                ]
            ],
            'pagination' => false
        ]);
    }

    /**
     * @return ActiveDataProvider
     */
    private function getDataProviderFolders()
    {
        return $this->getDataProvider(Documenti::IS_FOLDER);
    }

    /**
     * @return ActiveDataProvider
     */
    private function getDataProviderDocuments()
    {
        return $this->getDataProvider(Documenti::IS_DOCUMENT);
    }

    /**
     *
     */
    public function actionSessions()
    {
        pr(Yii::$app->session->get('stanzePath', []), 'routeStanze');
        pr(Yii::$app->session->get('foldersPath', []), 'routeFolders');
        pr(Yii::$app->session->get('myCurrentView', []));
//        die;
    }

    /**
     *
     */
    public function actionResetSessions()
    {
        DocumentsUtility::resetRoutesDocumentsExplorer();
        // pr("reset successfully");
    }

    public function actionDowload()
    {
        $postParams = $this->isPostActions();

        if (!empty($postParams['id']) && !empty($postParams['hash']) != '') {
            $file = File::findOne([
                'id' => $postParams['id'],
                'hash' => $postParams['hash']
            ]);

            $filePath = $this->getModule()->getFilesDirPath($file->hash) . DIRECTORY_SEPARATOR . $file->hash . '.' . $file->type;

            if (file_exists($filePath)) {
                if (!in_array($file->type, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $this->addDownloadNumber($file);

                    return Json::encode(\Yii::$app->response->sendFile($filePath, "$file->name.$file->type"));
                }
            }
        }

        return Json::encode(false);
    }

}
