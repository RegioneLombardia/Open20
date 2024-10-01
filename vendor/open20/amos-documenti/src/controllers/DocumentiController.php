<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\controllers
 * @category   CategoryName
 */

namespace open20\amos\documenti\controllers;

use open20\amos\documenti\widgets\icons\WidgetIconAllDocumenti;
use open20\amos\documenti\widgets\icons\WidgetIconDocumenti;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiCategorie;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiCreatedBy;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiDaValidare;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Response;
use yii\base\Exception;
use kartik\grid\GridView;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use open20\amos\cwh\AmosCwh;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use raoul2000\workflow\base\WorkflowException;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\admin\models\UserProfile;
use open20\amos\community\models\Community;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\DocumentiCategorie;
use open20\amos\documenti\models\DocumentiCartellePath;
use open20\amos\core\helpers\BreadcrumbHelper;
use open20\amos\core\helpers\PositionalBreadcrumbHelper;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\forms\CreateNewButtonWidget;
use open20\amos\documenti\models\DocumentiAgidType;
use open20\amos\documenti\utility\DocumentsUtility;
use open20\amos\documenti\assets\ModuleDocumentiAsset;
use open20\amos\documenti\models\DocumentiAgidContentType;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\onlyoffice\models\GestioneTemplate;
use yii\web\ForbiddenHttpException;

/**
 * Class DocumentiController
 *
 * @property \open20\amos\documenti\models\Documenti $model
 * @property \open20\amos\documenti\models\search\DocumentiSearch $modelSearch
 *
 * @package open20\amos\documenti\controllers
 */
class DocumentiController extends CrudController
{

    /**
     * Uso il trait per inizializzare la dashboard a tab
     */
    use TabDashboardControllerTrait;

    /**
     * @var string $layout
     */
    public $layout = 'main';

    /**
     * @var AmosDocumenti $documentsModule
     */
    public $documentsModule = null;

    /**
     *
     * @var string icon view based on widget document_explorer
     */
    public $viewExpl = null;

    /**
     * @var AmosCwh $moduleCwh AmosCwh module reference
     */
    public $moduleCwh;
    public $scope;
    protected $myCurrentView;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();

        $this->documentsModule = Yii::$app->getModule(AmosDocumenti::getModuleName());

        $this->setModelObj($this->documentsModule->createModel('Documenti'));
        $this->setModelSearch($this->documentsModule->createModel('DocumentiSearch'));

        ModuleDocumentiAsset::register(Yii::$app->view);

        $this->moduleCwh = Yii::$app->getModule('cwh');
        if (!empty($this->moduleCwh)) {
            $this->scope = $this->moduleCwh->getCwhScope();
        }

        $this->viewList = [
            'name' => 'list',
            'label' => AmosIcons::show('view-list') . Html::tag('p', AmosDocumenti::tHtml('amosdocumenti', 'Lista')),
            'url' => '?currentView=list'
        ];

        $this->viewGrid = [
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosDocumenti::tHtml('amosdocumenti', 'Tabella')),
            'url' => '?currentView=grid'
        ];

        // $this->viewExpl = [
        //     'name' => 'expl',
        //     'label' => AmosIcons::show('view-comfy') . Html::tag('p', AmosDocumenti::tHtml('amosdocumenti', 'Icone')),
        //     'url' => '?currentView=expl'
        // ];

        $defaultViews = [
            'list' => $this->viewList,
            'grid' => $this->viewGrid,
            // 'expl' => $this->viewExpl,
        ];

        $availableViews = [];
        foreach ($this->documentsModule->defaultListViews as $view) {
            if (isset($defaultViews[$view])) {
                $availableViews[$view] = $defaultViews[$view];
            }
        }

        $this->setAvailableViews($availableViews);

        $this->setUpLayout();

        if (Yii::$app->getRequest()->getQueryParam('currentView')) {
            Yii::$app->session->set('myCurrentView', Yii::$app->getRequest()->getQueryParam('currentView'));
        }

        $this->myCurrentView = Yii::$app->session->get('myCurrentView', []);
        if (empty($this->myCurrentView)) {
            if (empty($this->documentsModule->defaultView)) {
                $this->myCurrentView = reset($this->documentsModule->defaultListViews);
            } else {
                $this->myCurrentView = $this->documentsModule->defaultView;
            }
        }

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'download-documento-principale',
                                'download',
                                'index',
                                'documenti',
                                'all-documents',
                                'own-interest-documents',
                                'new-document-version',
                                'delete-new-document-version',
                                'list-only',
                                'increment-count-download-link',
                                'sync-doc-file',
                                'is-google-drive-document-modified',
                                'explore-documents',
                                'move'
                            ],
                            'roles' => [
                                'LETTORE_DOCUMENTI',
                                'AMMINISTRATORE_DOCUMENTI',
                                'CREATORE_DOCUMENTI',
                                'FACILITATORE_DOCUMENTI',
                                'VALIDATORE_DOCUMENTI'
                            ]
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'own-documents',
                                'onlyoffice-edit'
                            ],
                            'roles' => ['CREATORE_DOCUMENTI', 'AMMINISTRATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'validate-document',
                                'reject-document',
                            ],
                            'roles' => [
                                'AMMINISTRATORE_DOCUMENTI',
                                'FACILITATORE_DOCUMENTI',
                                'FACILITATOR',
                                'DocumentValidateOnDomain',
                                'VALIDATORE_DOCUMENTI'
                            ]
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'to-validate-documents',
                                'onlyoffice-callback-api'
                            ],
                            'roles' => [
                                'VALIDATORE_DOCUMENTI',
                                'FACILITATORE_DOCUMENTI',
                                'AMMINISTRATORE_DOCUMENTI',
                                'DocumentValidateOnDomain'
                            ]
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'admin-all-documents'
                            ],
                            'roles' => ['AMMINISTRATORE_DOCUMENTI']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'create-multiple',
                                'upload',
                                'publish-folder',
                                'modal-publish-folder-ajax',
                                'modal-unpublish-folder-ajax',
                            ],
                            'roles' => ['CREATORE_DOCUMENTI']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'go-to-view',
                                'go-to-update',
                                'go-to-groups',
                                'go-to-participants-tab',
                                'go-to-join',
                                'go-to-view-folder',
                                'go-to-update-folder',
                                'insert-document-content-type',
                                'get-documenti-agid-type-by-content-type',
                                'get-category-annual-archiving',
                                'get-unpublishing-date',
                                'template-by-tipo'
                            ],
                            'roles' => ['@']
                        ],

                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get']
                    ]
                ]
            ]
        );


        if ($this->documentsModule->openInFrontEnd) {
            $behaviors['access']['rules'][] = [
                'allow' => true,
                'actions' => [
                    ((!empty(\Yii::$app->params['befe']) && \Yii::$app->params['befe'] == true) ? 'all-documents'
                        : 'nothing'),
                    ((!empty(\Yii::$app->params['befe']) && \Yii::$app->params['befe'] == true) ? 'index' : 'nothingindex'),
                    ((!empty(\Yii::$app->params['befe']) && \Yii::$app->params['befe'] == true) ? 'view' : 'nothingread'),
                    ((!empty(\Yii::$app->params['befe']) && \Yii::$app->params['befe'] == true) ? 'expand-folder-documents' : 'nothing-expand-folder-documents')
                ],
                'matchCallback' => function ($rule, $action) {
                    if (in_array($action->id, ['all-documents', 'index', 'expand-folder-documents'])) return true;
                    if ($action->id != 'view') return false;
                    $id = (!empty(\Yii::$app->request->get()['id']) ? Yii::$app->request->get()['id'] : null);
                    if (!empty($id)) {
                        $model = Documenti::findOne($id);

                        if (!empty($model) && $model->primo_piano == 1) {
                            return true;
                        }
                    }
                    return false;
                }
            ];
        }

        return $behaviors;
    }

    // /**
    //  * @inheritdoc
    //  */
    // public function actions()
    // {

    //     return [
    //         'error' => [
    //             'class' => 'yii\web\ErrorAction',
    //         ],
    //     ];
    // }

    /**
     * @param int $id Document id.
     * @return \yii\web\Response
     */
    public function actionValidateDocument($id, $uid = null)
    {
        // check if the user is the right
        if (($uid != null) && ($uid != Yii::$app->user->id)) {
            Yii::$app->session->addFlash(
                'danger',
                AmosDocumenti::t(
                    'amosdocumenti',
                    '#you_are_not_authorized_for_this'
                )
            );

            return $this->redirect(['/']);
        }

        /** @var Documenti $documentiModel */
        $documentiModel = $this->documentsModule->createModel('Documenti');
        $this->model = $documentiModel::findOne($id);
        try {
            $this->model->sendToStatus(Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO);
            $ok = $this->model->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', AmosDocumenti::t('amosdocumenti', 'Document validated!'));
            } else {
                Yii::$app->session->addFlash(
                    'danger',
                    AmosDocumenti::t('amosdocumenti', '#ERROR_WHILE_VALIDATING_DOCUMENT')
                );
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            //            return $this->redirect(Url::previous());
        }

        return $this->redirect(Url::previous());
    }

    /**
     *
     * @param type $id
     * @param type $udi
     * @return type
     */
    public function actionRejectDocument($id, $uid = null)
    {
        // check if the user is the right
        if (($uid != null) && ($uid != Yii::$app->user->id)) {
            Yii::$app->session->addFlash(
                'danger',
                AmosDocumenti::t(
                    'amosdocumenti',
                    '#you_are_not_authorized_for_this'
                )
            );

            return $this->redirect(['/']);
        }

        $modelClassname = $this->documentsModule->model('Documenti');
        $this->model = $modelClassname::findOne($id);

        try {
            $this->model->sendToStatus(Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA);
            $ok = $this->model->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', AmosDocumenti::t('amosdocumenti', 'Document rejected!'));
            } else {
                Yii::$app->session->addFlash(
                    'danger',
                    AmosDocumenti::t('amosdocumenti', '#ERROR_WHILE_REJECTING_DOCUMENT')
                );
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            // return $this->redirect(Url::previous());
        }

        // $this->model->save(false);
        // Yii::$app->session->addFlash('success', AmosDocumenti::t('amosdocumenti', 'Document rejected!'));
        return $this->redirect(Url::previous());
    }

    /**
     * Lists all Documenti models.
     * @return mixed
     */
    public function actionIndex($layout = null)
    {
        $parentId = Yii::$app->request->getQueryParam('parentId');
        return $this->redirect(['/documenti/documenti/all-documents', 'parentId' => $parentId]);
    }

    /**
     * @param null $communityId
     * @param null $parentId
     * @return string
     */
    public function actionCreateMultiple($communityId = null, $parentId = null)
    {
        $this->model = $this->documentsModule->createModel('Documenti');

        if (!$communityId) {
            $scope = $this->moduleCwh->getCwhScope();
            $communityId = $scope['community'];
        } else {
            $this->moduleCwh->setCwhScopeInSession(
                [
                    'community' => $communityId,
                ],
                [
                    'mm_name' => 'community_user_mm',
                    'entity_id_field' => 'community_id',
                    'entity_id' => $communityId
                ]
            );
        }

        $moduleGroups = \Yii::$app->getModule('groups');
        $enableGroupNotification = $this->documentsModule->enableGroupNotification;

        //If submitted
        if (Yii::$app->request->isPost) {
            //Session key
            $sessionKey = 'multiupload_' . $communityId;

            //already uploaded files
            $uploadedIds = \Yii::$app->session->get($sessionKey);

            $status = Yii::$app->request->post('Documenti');
            if (isset($status['status'])) {
                $status = $status['status'];
            } else {
                $status = null;
            }

            //Parse all uploaded documents
            foreach ($uploadedIds as $uploadId) {
                /** @var Documenti $documentiModel */
                $documentiModel = $this->documentsModule->createModel('Documenti');
                //Single document record
                $documentRecord = $documentiModel::findOne(['id' => $uploadId]);

                if (!empty($documentRecord)) {
                    if ($status != null) {
                        $documentRecord->status = $status;
                        $documentRecord->save(false);
                    }

                    // salvo le preferenze di invio notifica
                    $listaProfili = Yii::$app->request->post('selection-profiles');

                    if (!empty($listaProfili)) {
                        foreach ($listaProfili as $userId) {
                            /** @var DocumentiNotifichePreferenze $preferenzaDocumenti */
                            $preferenzaDocumenti = new DocumentiNotifichePreferenze();
                            $preferenzaDocumenti->documento_parent_id = (empty($documentRecord->version_parent_id) ? $documentRecord->id
                                : $documentRecord->version_parent_id);
                            $preferenzaDocumenti->user_id = $userId;
                            $preferenzaDocumenti->save(false);
                        }
                    }

                    // salvo le preferenze di invio notifica
                    $listaGruppi = Yii::$app->request->post('selection-groups');

                    if (!empty($listaGruppi)) {
                        foreach ($listaGruppi as $groupId) {
                            /** @var DocumentiNotifichePreferenze $preferenzaDocumenti */
                            $preferenzaDocumenti = new DocumentiNotifichePreferenze();
                            $preferenzaDocumenti->documento_parent_id = (empty($documentRecord->version_parent_id) ? $documentRecord->id
                                : $documentRecord->version_parent_id);
                            $preferenzaDocumenti->group_id = $groupId;
                            $preferenzaDocumenti->save(false);
                        }
                    }

                    if ($enableGroupNotification && !empty($moduleGroups)) {
                        $this->sendNotificationEmail($documentRecord);
                    }
                }
            }

            Yii::$app->getSession()->addFlash('success', AmosDocumenti::tHtml('amosdocumenti', 'Notifiche Inviate.'));
        }

        //Session key
        $sessionKey = 'multiupload_' . $communityId;

        //Set in session the new file for this community
        \Yii::$app->session->set($sessionKey, []);

        return $this->render(
            'create-multiple',
            [
                'model' => $this->model,
                'communityId' => $communityId,
                'parentId' => $parentId
            ]
        );
    }

    /**
     * Used for set page title and breadcrumbs.
     *
     * @param string $documentiPageTitle Documenti page title (ie. Created by documenti, ...)
     */
    private function setTitleAndBreadcrumbs($documentiPageTitle)
    {
        $this->setNetworkDashboardBreadcrumb();

        $parentId = Yii::$app->request->getQueryParam('parentId');
        if (isset($parentId)) {
            /** @var Documenti $documentiModel */
            $documentiModel = $this->documentsModule->createModel('Documenti');
            $folder = $documentiModel::findOne($parentId);
            if (!is_null($folder)) {
                $documentiPageTitle = $folder->getTitle();
            }
        }
        Yii::$app->session->set('previousTitle', $documentiPageTitle);
        Yii::$app->session->set('previousUrl', Url::previous());
        //Yii::$app->view->title = $documentiPageTitle;
        Yii::$app->view->params['breadcrumbs'][] = ['label' => $documentiPageTitle];

        $this->view->title = $documentiPageTitle;
        $this->view->params['titleSection'] = $this->view->title;
    }

    /**
     * This method calculate the template of the grid view action columns
     * @param string $actionId
     * @return string
     */
    public function getGridViewActionColumnsTemplate($actionId)
    {
        $actionColumnDefault = '{view}{update}{delete}';
        $actionColumnToValidate = '{validate}{reject}';
        $actionColumn = $actionColumnDefault;
        if ($actionId == 'to-validate-documents') {
            $actionColumn = $actionColumnToValidate . $actionColumnDefault;
            //cta:tutti i documenti
            $this->view->title = 'Documenti da validare';
        }
        $enableVersioning = $this->documentsModule->enableDocumentVersioning;
        if ($enableVersioning) {
            $actionColumn = '{view}{newDocVersion}{update}{delete}';
        }
        if ($this->documentsModule->enableContentDuplication) {
            $actionColumn = '{duplicateBtn}' . $actionColumn;
        }
        if ($this->documentsModule->enableMoveDoc) {
            $actionColumn = '{move}' . $actionColumn;
        }
        return $actionColumn;
    }

    /**
     *
     */
    public function setNetworkDashboardBreadcrumb()
    {
        $scope = null;
        if (!empty($this->moduleCwh)) {
            $scope = $this->moduleCwh->getCwhScope();
        }

        if (!empty($scope)) {
            if (isset($scope['community'])) {
                $communityId = $scope['community'];
                $community = \open20\amos\community\models\Community::findOne($communityId);
                $dashboardCommunityTitle = AmosDocumenti::t('amosdocumenti', "Dashboard") . ' ' . $community->name;
                $dasbboardCommunityUrl = Yii::$app->urlManager->createUrl(['community/join', 'id' => $communityId]);
                Yii::$app->view->params['breadcrumbs'][] = [
                    'label' => $dashboardCommunityTitle,
                    'url' => $dasbboardCommunityUrl
                ];
            }
        }
    }

    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = AmosDocumenti::t('amosdocumenti', 'Documenti');
            $urlLinkAll = '';

            $labelSigninOrSignup = AmosDocumenti::t('amosdocumenti', '#beforeActionCtaLoginRegister');
            $titleSigninOrSignup = AmosDocumenti::t(
                'amosdocumenti',
                '#beforeActionCtaLoginRegisterTitle',
                ['platformName' => \Yii::$app->name]
            );
            $labelSignin = AmosDocumenti::t('amosdocumenti', '#beforeActionCtaLogin');
            $titleSignin = AmosDocumenti::t(
                'amosdocumenti',
                '#beforeActionCtaLoginTitle',
                ['platformName' => \Yii::$app->name]
            );

            $labelLink = $labelSigninOrSignup;
            $titleLink = $titleSigninOrSignup;
            $socialAuthModule = Yii::$app->getModule('socialauth');
            if ($socialAuthModule && ($socialAuthModule->enableRegister == false)) {
                $labelLink = $labelSignin;
                $titleLink = $titleSignin;
            }

            $ctaLoginRegister = Html::a(
                $labelLink,
                isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                    : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                [
                    'title' => $titleLink
                ]
            );
            $subTitleSection = Html::tag(
                'p',
                AmosDocumenti::t(
                    'amosdocumenti',
                    '#beforeActionSubtitleSectionGuest',
                    ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]
                )
            );
        } else {
            $titleSection = AmosDocumenti::t('amosdocumenti', 'Documenti');
            $labelLinkAll = AmosDocumenti::t('amosdocumenti', 'Tutti i documenti');
            $urlLinkAll = '/documenti/documenti/all-documents';
            $titleLinkAll = AmosDocumenti::t('amosdocumenti', 'Visualizza la lista dei documenti');

            $subTitleSection = Html::tag('p', AmosDocumenti::t('amosdocumenti', '#beforeActionSubtitleSectionLogged'));
        }

        $labelCreate = AmosDocumenti::t('amosdocumenti', 'Nuovo Documento');
        $titleCreate = AmosDocumenti::t('amosdocumenti', 'Crea una nuovo documento');
        $labelManage = AmosDocumenti::t('amosdocumenti', 'Gestisci');
        $titleManage = AmosDocumenti::t('amosdocumenti', 'Gestisci i documenti');
        $urlCreate = '/documenti/documenti/create';
        $urlManage = null;

        $urlSecondAction = '/documenti/documenti/create?isFolder=1';
        $labelSecondAction = AmosDocumenti::t('amosdocumenti', 'Nuova Cartella');
        $titleSecondAction = AmosDocumenti::t('amosdocumenti', 'Crea una nuova cartella');

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'documenti',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'urlLinkAll' => $urlLinkAll,
            'labelLinkAll' => $labelLinkAll,
            'titleLinkAll' => $titleLinkAll,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
            'urlSecondAction' => $urlSecondAction,
            'labelSecondAction' => $labelSecondAction,
            'titleSecondAction' => $titleSecondAction,
            'hideSecondAction' => $this->documentsModule->enableFolders
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }


    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    private function setCreateNewBtnLabel()
    {
        //        $hideWidard = $this->documentsModule->hideWizard;
        $parentId = null;
        $btnNewFolder = '';
        $isDriveFolder = false;

        if (!is_null(Yii::$app->request->getQueryParam('parentId'))) {
            $parentId = Yii::$app->request->getQueryParam('parentId');
        }

        $linkCreateNewButton = (array_key_exists("noWizardNewLayout", Yii::$app->params)) ? [
            '/documenti/documenti/create',
            'parentId' => $parentId
        ] : ['/documenti/documenti-wizard/introduction', 'parentId' => $parentId];

        if ($this->documentsModule->hideWizard) {
            $linkCreateNewButton = ['/documenti/documenti/create', 'parentId' => $parentId];
        }

        $createNewBtnParams = [
            'createNewBtnLabel' => AmosDocumenti::t('amosdocumenti', 'Nuovo'),
            'urlCreateNew' => $linkCreateNewButton,
            'otherOptions' => ['title' => AmosDocumenti::t('amosdocumenti', 'Crea nuovo'), 'class' => 'btn btn-primary']
        ];

        if ($this->documentsModule->enableFolders) {

            $btnBack = '';
            // find Url to navigate previous folder
            if (!is_null($parentId)) {
                /** @var Documenti $documentiModel */
                $documentiModel = $this->documentsModule->createModel('Documenti');
                $parent = $documentiModel::findOne($parentId);
                if (!is_null($parent)) {
                    $searchParams = Yii::$app->request->getQueryParams()['DocumentiSearch'];
                    $url = [$this->action->id, 'parentId' => $parent->parent_id, 'DocumentiSearch' => $searchParams];
                    $btnBack = Html::a(
                        Html::tag('span', '', ['class' => 'mdi mdi-arrow-left']) .
                            AmosDocumenti::tHtml('amosdocumenti', 'Livello superiore'),
                        $url,
                        ['class' => 'btn btn-secondary', 'title' => AmosDocumenti::t('amosdocumenti', 'Livello superiore')]
                    );
                    if ($parent->drive_file_id) {
                        $isDriveFolder = true;
                    }
                }
            }

            $btnNewFolder = CreateNewButtonWidget::widget([
                'createNewBtnLabel' => AmosDocumenti::t('amosdocumenti', '#btn_new_folder'),
                'urlCreateNew' => ['/documenti/documenti/create', 'isFolder' => true, 'parentId' => $parentId],
                'otherOptions' => ['title' => AmosDocumenti::t('amosdocumenti', '#btn_new_folder')]
            ]);

            if (!Yii::$app->user->can('DOCUMENTI_CREATE')) {
                $layout = $btnBack;
            } else {
                if (!$isDriveFolder) {
                    $this->view->params['urlSecondAction'] = Yii::$app->urlManager->createUrl(['/documenti/documenti/create', 'isFolder' => true, 'parentId' => $parentId]);
                    $this->view->params['labelSecondAction'] = AmosDocumenti::t('amosdocumenti', '#btn_new_folder');
                    $this->view->params['titleSecondAction'] = AmosDocumenti::t('amosdocumenti', '#btn_new_folder');
                    $this->view->params['hideSecondAction'] = false;
                }

                $layout = $btnBack;
                if (!$isDriveFolder) {
                    $layout .= "{buttonCreateNew}" . $btnNewFolder;
                }
            }

            $createNewBtnParams = ArrayHelper::merge(
                $createNewBtnParams,
                ['layout' => $layout,]
            );
        }

        Yii::$app->view->params['createNewBtnParams'] = $createNewBtnParams;
        Yii::$app->view->params['additionalButtons'] = [
            'htmlButtons' => [$btnBack]
        ];
    }

    /**
     * This method is useful to set all common params for all list views.
     */
    protected function setListViewsParams()
    {
        $this->setCreateNewBtnLabel();
        Yii::$app->session->set(AmosDocumenti::beginCreateNewSessionKey(), Url::previous());
    }

    /**
     * @param Documenti $model
     * @return mixed|\yii\web\Response
     */
    public function getFormCloseUrl($model)
    {
        $isNewVersion = false;
        if (!empty(\Yii::$app->request->get('isNewVersion')) && \Yii::$app->request->get('isNewVersion') == 1) {
            $isNewVersion = true;
        }
        if ($this->documentsModule->enableDocumentVersioning && !$model->isNewRecord && !is_null($model->version) && ($model->version
            > 1) && $isNewVersion) {
            return ['/documenti/documenti/delete-new-document-version', 'id' => (!is_null($model) ? $model->id : $this->model->id)];
        } else {
            return Yii::$app->session->get('previousUrl');
        }
    }

    /**
     * @param Documenti $model
     * @return string
     */
    public function getFormCloseLabel($model)
    {
        $label = '';
        if ($this->documentsModule->enableDocumentVersioning && !$model->isNewRecord && !is_null($model->version) && ($model->version
            > 1)) {
            $label = AmosDocumenti::t('amosdocumenti', '#CANCEL_NEW_VERSION');
        }
        return $label;
    }

    /**
     * Displays a single Documenti model.
     *
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);
        $isFolder = $this->model->isFolder();

        if ($isFolder) {
            if ($this->documentsModule->enableExploreDocumentsInIndex == true && $this->scope) {
                return $this->redirect(['admin-all-documents', 'parentId' => $this->model->id]);
            }
            return $this->redirect(['all-documents', 'parentId' => $this->model->id]);
        }

        return $this->render(
            'view',
            [
                'model' => $this->model,
                'isFolder' => $isFolder
            ]
        );
    }

    public function actionTemplateByTipo()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $ids = Yii::$app->request->post('depdrop_parents');
        if (empty($ids)) {
            return ['output' => '', 'selected' => ''];
        }
        $out = [];
        list($idTipoStr) = $ids;
        if (empty($idTipoStr)) {
            return ['output' => '', 'selected' => ''];
        }

        $moduleOnlyOffice = \open20\onlyoffice\Module::instance();

        if (!array_key_exists($idTipoStr, $moduleOnlyOffice->tipiGestibiliToTipiTemplate)) {
            return ['output' => '', 'selected' => ''];
        }
        $idTipo = $moduleOnlyOffice->tipiGestibiliToTipiTemplate[$idTipoStr];

        // GestioneTemplate::find()->select(['id', 'nome'])->andWhere(['tipo' => 1])->orderBy('nome')->asArray()->all(), 'id', 'nome')
        foreach ((GestioneTemplate::find()
            ->andWhere(['tipo' => $idTipo])
            ->select(['id', 'nome'])->orderBy('nome')
            ->asArray()->all()) as $datiArr) {
            $out[] = [
                'id' => $datiArr['id'],
                'name' => $datiArr['nome'],
            ];
        }
        return ['output' => $out, 'selected' => ''];
    }

    /**
     * Creates a new Documenti model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate(
        $isFolder = null,
        $isAjaxRequest = null,
        $regolaPubblicazione = null,
        $parentId = null,
        $to = null,
        $urlRedirect = null
    ) {
        $this->setUpLayout('form');
        $this->model = $this->documentsModule->createModel('Documenti');

        $moduleGroups = \Yii::$app->getModule('groups');
        $enableGroupNotification = $this->documentsModule->enableGroupNotification;

        if ($this->documentsModule->hidePubblicationDate) {
            $this->model = $this->documentsModule->createModel(
                'Documenti',
                ['scenario' => Documenti::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE]
            );
        } else {
            $this->model = $this->documentsModule->createModel(
                'Documenti',
                ['scenario' => Documenti::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE]
            );
        }

        $params = Yii::$app->request->getQueryParams();
        if (isset($params['isFolder'])) {
            $this->model->setScenario(Documenti::SCENARIO_FOLDER);
            $this->model->is_folder = Documenti::IS_FOLDER;
        }

        if (isset($params['parentId'])) {
            $this->model->parent_id = $params['parentId'];
        }

        if (isset($isAjaxRequest) && $isAjaxRequest = true) {
            $this->model->regola_pubblicazione = $regolaPubblicazione;
            $this->model->destinatari = Yii::$app->request->post()['Documenti']['destinatari'];
            if (!$this->documentsModule->hidePubblicationDate) {
                $this->model->data_pubblicazione = date("Y-m-d");
            }
            $this->model->setScenario(Documenti::SCENARIO_FOLDER);
            $this->model->is_folder = Documenti::IS_FOLDER;
            $this->model->validatori = "community-2";
            $this->model->status = Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO;
        }

        $this->model->typeMainDocument = Documenti::MAIN_DOCUMENT_TYPE_FILE;

        if ($this->documentsModule->enableAnnualArchiving) {
            $this->model->enable_annual_archiving = 1;
            $this->model->year = date('Y', strtotime($this->model->data_pubblicazione));

            if ($this->documentsModule->enableCategories) {
                $categories = DocumentsUtility::getDocumentiCategorie()->orderBy('titolo')->all();
                if (count($categories) == 1) {
                    $firstCategory = array_pop($categories);
                    $this->model->enable_annual_archiving = $firstCategory->enable_annual_archiving;
                }
            }
        }

        if ($this->documentsModule->enableCountdownUnpublishing) {

            $this->model->enable_countdown_unpublishing = 1;

            $years = $this->documentsModule->yearsCountdownUnpublishing;
            $this->model->data_rimozione = date('Y-m-d', strtotime("+$years years", strtotime($this->model->data_pubblicazione)));

            if ($this->documentsModule->enableCategories) {
                $categories = DocumentsUtility::getDocumentiCategorie()->orderBy('titolo')->all();
                if (count($categories) == 1) {
                    $firstCategory = array_pop($categories);
                    if ($firstCategory->enable_countdown_unpublishing)
                        $years = $firstCategory->years_countdown_unpublishing;
                    $this->model->data_rimozione = date('Y-m-d', strtotime("+$years years", strtotime($this->model->data_pubblicazione)));
                }
            }
        }

        if ($this->model->load(Yii::$app->request->post())) {
            $fileId = \Yii::$app->request->post('fileid');
            $GoogleDriveManager = null;
            if (!empty($fileId)) {
                $GoogleDriveManager = new \open20\amos\documenti\utility\GoogleDriveManager(['model' => $this->model]);
            }

            if ($this->model->enable_annual_archiving == 0) {
                $this->model->year = null;
                $this->model->date_start_archiving = null;
                $this->model->stop_archiving = null;
            }

            if ($this->model->validate()) {

                $validateOnSave = true;
                if ($this->model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE) {
                    $this->model->status = Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA;
                    $ok = $this->model->save();
                    if ($ok) {
                        if ($this->documentIsFolder($this->model)) {
                            $this->model->setScenario(Documenti::SCENARIO_FOLDER);
                        } else {
                            $this->model->setScenario(Documenti::SCENARIO_UPDATE);
                        }
                    }
                    $this->model->status = Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE;
                    $validateOnSave = false;
                }

                if ($this->model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) {
                    $this->model->status = Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA;
                    $ok = $this->model->save();
                    if ($ok) {
                        if ($this->documentIsFolder($this->model)) {
                            $this->model->setScenario(Documenti::SCENARIO_FOLDER);
                        } else {
                            $this->model->setScenario(Documenti::SCENARIO_UPDATE);
                        }
                    }
                    $this->model->status = Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO;
                    $validateOnSave = false;
                }

                if ($this->documentsModule->enableCountdownUnpublishing && $this->model->enable_countdown_unpublishing) {

                    if (empty($this->model->data_rimozione)) {
                        $this->model->data_rimozione = $this->model->getUnpublishingDate();
                    }
                }


                if ($this->model->save($validateOnSave)) {

                    /*
                     * solo in caso di creazione di un documento
                     * con Only Office
                     */
                    if ($this->model->typeMainDocument == Documenti::MAIN_DOCUMENT_TYPE_ONLYOFFICE) {
                        if (!empty($this->model->idTemplate)) {
                            $this->model->uploadTemplate($this->model->idTemplate);
                        } else {
                            $this->model->uploadDefaultFile();
                        }
                    }

                    /*************/

                    if (!empty($GoogleDriveManager)) {
                        $GoogleDriveManager->shareWithUser($fileId);
                        $GoogleDriveManager->getResourcesAndSave($fileId);
                        if ($this->model->drive_file_id) {
                            $this->model->changeStatusFolderRecursive($this->model->status);
                        }
                    }
                    if ((!isset($isAjaxRequest)) || (isset($isAjaxRequest) && $isAjaxRequest = false)) {
                        Yii::$app->getSession()->addFlash(
                            'success',
                            AmosDocumenti::tHtml('amosdocumenti', 'Documenti salvati con successo.')
                        );
                    } else {
                        return ['success' => true];
                    }

                    //salvo il path 
                    $result = DocumentiCartellePath::generatePath($this->model, 1, []);
                    DocumentiCartellePath::savePath($result, $this->model->id);

                    // Setto la categoria dell'allegato principale come quella del documento se abilitata la feature
                    if($this->documentsModule->enableAutomaticDocumentMainFileCategory) {
                        DocumentsUtility::categorizeMainDocumentAttachment($this->model);
                    }

                    if ($enableGroupNotification && !empty($moduleGroups)) {
                        $this->sendNotificationEmail();
                    }
                    if (!empty($urlRedirect)) {
                        return $this->redirect($urlRedirect);
                    }
                    if (!empty($to)) {
                        return $this->redirect($to);
                    }
                    return $this->redirectOnCreate($this->model);
                } else {
                    if ((!isset($isAjaxRequest)) || (isset($isAjaxRequest) && $isAjaxRequest = false)) {
                        Yii::$app->getSession()->addFlash(
                            'danger',
                            AmosDocumenti::tHtml(
                                'amosdocumenti',
                                'Si &egrave; verificato un errore durante il salvataggio'
                            )
                        );
                    } else {
                        return [
                            'success' => false,
                            'message' => AmosDocumenti::t(
                                'amosdocumenti',
                                'Si &egrave; verificato un errore durante il salvataggio'
                            )
                        ];
                    }
                    return $this->render(
                        'create',
                        [
                            'model' => $this->model,
                            'scope' => $this->scope
                        ]
                    );
                }
            } else {
                if ((!isset($isAjaxRequest)) || (isset($isAjaxRequest) && $isAjaxRequest = false)) {
                    Yii::$app->getSession()->addFlash(
                        'danger',
                        AmosDocumenti::tHtml('amosdocumenti', "Modifiche non salvate. Verifica l'inserimento dei campi")
                    );
                } else {
                    return [
                        'success' => false,
                        'message' => AmosDocumenti::t(
                            'amosdocumenti',
                            "Modifiche non salvate. Verifica l'inserimento dei campi"
                        )
                    ];
                }
            }
        }

        return $this->render(
            'create',
            [
                'model' => $this->model,
                'scope' => $this->scope
            ]
        );
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws WorkflowException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionNewDocumentVersion($id)
    {
        $this->model = $this->findModel($id);
        $ok = $this->model->makeNewDocumentVersion();
        $this->model->status = $this->model->getWorkflowSource()->getWorkflow(Documenti::DOCUMENTI_WORKFLOW)->getInitialStatusId();
        $this->model->save(false);
        $url = ['update', 'id' => $this->model->id, 'isNewVersion' => 1];
        if (!$ok) {
            $url = Yii::$app->session->get(AmosDocumenti::beginCreateNewSessionKey());
            Yii::$app->getSession()->addFlash(
                'danger',
                AmosDocumenti::tHtml('amosdocumenti', 'Errore durante la creazione della nuova versione.')
            );
        }
        return $this->redirect($url);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDeleteNewDocumentVersion($id)
    {
        $this->model = $this->findModel($id);
        $ok = $this->model->deleteNewDocumentVersion();
        if (!$ok) {
            Yii::$app->getSession()->addFlash(
                'danger',
                AmosDocumenti::tHtml('amosdocumenti', 'Errore durante la cancellazione della nuova versione.')
            );
        }
        return $this->redirect(Yii::$app->session->get(AmosDocumenti::beginCreateNewSessionKey()));
    }

    /**
     * Updates an existing Documenti model.
     *
     * @param integer $id
     * @param bool|false $backToEditStatus Save the model with status Editing in progress before form rendering
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionUpdate($id, $backToEditStatus = false)
    {
        Url::remember();
        $moduleGroups = \Yii::$app->getModule('groups');
        $enableGroupNotification = $this->documentsModule->enableGroupNotification;

        $this->setUpLayout('form');
        $this->model = $this->findModel($id);
        $this->model->typeMainDocument = Documenti::MAIN_DOCUMENT_TYPE_FILE;

        if (!empty($this->model->link_document)) {
            $this->model->typeMainDocument = Documenti::MAIN_DOCUMENT_TYPE_LINK;
        }

        if ($this->documentIsFolder($this->model)) {
            $this->model->setScenario(Documenti::SCENARIO_FOLDER);
        } else {
            $this->model->setScenario(Documenti::SCENARIO_UPDATE);
        }

        if (Yii::$app->request->post()) {
            $previousStatus = $this->model->status;
            if ($this->model->load(Yii::$app->request->post())) {
                $GoogleDriveManager = null;
                $fileId = \Yii::$app->request->post('fileid');
                if (!empty($fileId)) {
                    $GoogleDriveManager = new \open20\amos\documenti\utility\GoogleDriveManager(['model' => $this->model]);
                }

                if ($this->model->enable_annual_archiving == 0) {
                    $this->model->year = null;
                    $this->model->date_start_archiving = null;
                    $this->model->stop_archiving = null;
                }

                if ($this->model->validate()) {


                    if ($this->documentsModule->enableCountdownUnpublishing && $this->model->enable_countdown_unpublishing) {

                        if (empty($this->model->data_rimozione)) {
                            $this->model->data_rimozione = $this->model->getUnpublishingDate();
                        }
                    }

                    if ($this->model->save()) {
                        if (!empty($GoogleDriveManager)) {
                            $GoogleDriveManager->shareWithUser($fileId);
                            $GoogleDriveManager->getResourcesAndSave($fileId);
                        }

                        if (!(empty($this->model->documentMainFile))) {
                            $this->model->link_document = '';
                            $this->model->save();
                        }
                        $this->model->publishAndUnpublishFolder(\Yii::$app->request->post());
                        Yii::$app->getSession()->addFlash(
                            'success',
                            AmosDocumenti::tHtml('amosdocumenti', 'Documento aggiornato con successo.')
                        );

                        if (!$this->model->is_folder) {
                            if ($enableGroupNotification && !empty($moduleGroups)) {
                                $this->sendNotificationEmail();
                            }
                        }

                        // Setto la categoria dell'allegato principale come quella del documento se abilitata la feature
                        if($this->documentsModule->enableAutomaticDocumentMainFileCategory) {
                            DocumentsUtility::categorizeMainDocumentAttachment($this->model);
                        }

                        return $this->redirectOnUpdate($this->model, $previousStatus);
                    } else {
                        Yii::$app->getSession()->addFlash(
                            'danger',
                            AmosDocumenti::tHtml(
                                'amosdocumenti',
                                'Si &egrave; verificato un errore durante il salvataggio'
                            )
                        );
                        return $this->render(
                            'update',
                            [
                                'model' => $this->model,
                                'scope' => $this->scope
                            ]
                        );
                    }
                } else {
                    Yii::$app->getSession()->addFlash(
                        'danger',
                        AmosDocumenti::tHtml('amosdocumenti', 'Modifiche non salvate. Verifica l\'inserimento dei campi')
                    );
                }
            }
        } else {
            if ($backToEditStatus && ($this->model->status != $this->model->getDraftStatus() && !Yii::$app->user->can(
                'DocumentValidate',
                ['model' => $this->model]
            ))) {
                $this->model->status = $this->model->getDraftStatus();
                $ok = $this->model->save(false);
                if (!$ok) {
                    Yii::$app->getSession()->addFlash(
                        'danger',
                        AmosDocumenti::tHtml('amosdocumenti', 'Si &egrave; verificato un errore durante il salvataggio')
                    );
                }
            }
        }

        return $this->render(
            'update',
            [
                'model' => $this->model,
                'scope' => $this->scope
            ]
        );
    }

    /**
     * Private method to download a file.
     *
     * @param string $path A path to a file.
     * @param string $file A filename
     * @param array $extensions
     * @param string $titolo
     * @return bool
     */
    private function downloadFile($path, $file, $extensions = [], $titolo = null)
    {
        if (is_file($path)) {
            $file_info = pathinfo($path);
            $extension = $file_info["extension"];

            if (is_array($extensions)) {
                foreach ($extensions as $e) {
                    if ($e === $extension) {
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        $titolo = $titolo ? $titolo : 'Allegato_documenti';
                        header('Content-Disposition: attachment; filename="' . $titolo . '.' . $extension . '"');
                        header('Content-Transfer-Encoding: binary');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($path));
                        readfile($path);
                        ob_clean();
                        flush();

                        return true; //Yii::$app->response->sendFile($path);
                    }
                }
            }
        }
        return false;
    }

    /**
     * Deletes an existing Documenti model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->documentsModule->enableFolders) {
            return $this->enabledFoldersDelete();
        } else {
            return $this->standardDelete();
        }
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\db\StaleObjectException
     */
    protected function standardDelete()
    {
        $this->model->delete();
        $isFolder = $this->model->isFolder();
        if (!$this->model->getErrors()) {
            $successMessage = ($isFolder ?
                AmosDocumenti::tHtml('amosdocumenti', 'Cartella cancellata correttamente.') :
                AmosDocumenti::tHtml('amosdocumenti', 'Documento cancellato correttamente.'));
            Yii::$app->getSession()->addFlash('success', $successMessage);
        } else {
            $errorMessage = ($isFolder ?
                AmosDocumenti::tHtml('amosdocumenti', 'Non sei autorizzato a cancellare la cartella.') :
                AmosDocumenti::tHtml('amosdocumenti', 'Non sei autorizzato a cancellare il documento.'));
            Yii::$app->getSession()->addFlash('danger', $errorMessage);
        }
        if (!empty(Yii::$app->request->get('urlRedirect'))) {
            return $this->redirect(Yii::$app->request->get('urlRedirect'));
        } else {
            return $this->redirect(Url::previous());
        }
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\db\StaleObjectException
     */
    protected function enabledFoldersDelete()
    {
        $allOk = $this->model->deleteAllChildren();
        if (!$allOk) {
            return $this->redirect(Url::previous());
        }
        return $this->standardDelete();
    }


    public function actionMove()
    {

        if (Yii::$app->request->post()) {
            $id = Yii::$app->request->post()['docId'];
            $parentId = Yii::$app->request->post()['destinationFolder'];
            $this->model = $this->findModel($id);
            $this->model->parent_id = $parentId;
            if ($parentId == 0)
                $this->model->parent_id = null;
            if ($this->model->save(false)) {
                Yii::$app->getSession()->addFlash(
                    'success',
                    AmosDocumenti::tHtml('amosdocumenti', 'Documento spostato correttamente')
                );
            } else {
                Yii::$app->getSession()->addFlash(
                    'danger',
                    AmosDocumenti::tHtml('amosdocumenti', 'Si &egrave; verificato un errore durante il salvataggio')
                );
            }
        } else {
            Yii::$app->getSession()->addFlash(
                'danger',
                AmosDocumenti::tHtml('amosdocumenti', 'Si &egrave; verificato un errore durante il salvataggio')
            );
        }
        return $this->redirect(Url::previous());
    }

    /**
     * Action to search only for own documents
     *
     * @param int|null $parentId - id of document folder
     * @return string
     */
    public function actionOwnDocuments($parentId = null)
    {
        Url::remember();

        $params = Yii::$app->request->getQueryParams();
        $modelSearch = $this->getModelSearch();
        if (!is_null($parentId)) { //set parent Id to filter documents within a folder
            $modelSearch->parentId = $parentId;
        } else {
            /**
             * FIX per RC-372. Parametro presente anche altrove in questo file. Se questo metodo viene usato nei widget grafici,
             * questo parametro (commentato) dev'essere settato là dentro e non qua. Quindi se serve questo parametro spostarlo nel posto corretto
             * e si deve rimuovere questo else.
             */
            //            $params['fromWidgetGraphic'] = true;
            //            $params['validByScopeIgnoreStatus'] = true;
            //            $params['validByScopeIgnoreDates'] = true;
        }
        $this->setModelSearch($modelSearch);

        $this->setDataProvider($this->getModelSearch()->searchOwnDocuments($params));
        $this->view->title = AmosDocumenti::t('amosdocumenti', 'Documenti creati da me');
        $this->view->params['titleSection'] = $this->view->title;
        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', '#page_title_created_by_me'));


        if (empty($this->getCurrentView())) {
            $this->setCurrentView($this->getAvailableView('list'));
        }
        $this->setListViewsParams();

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();


        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null
            ]
        );
    }

    /**
     * Action to search only for own interest documents
     *
     * @param int|null $parentId - id of document folder
     * @return string
     */
    public function actionOwnInterestDocuments($currentView = null, $parentId = null)
    {
        if ($this->documentsModule->enableExploreDocumentsInIndex == true && $this->scope) {
            return $this->redirect(['/documenti/documenti/explore-documents']);
        }

        Url::remember();

        Yii::$app->session->set('stanzePath', []);
        Yii::$app->session->set('foldersPath', []);

        $params = Yii::$app->request->getQueryParams();
        if (!is_null($parentId)) { //set parent Id to filter documents within a folder
            $modelSearch = $this->getModelSearch();
            $modelSearch->parentId = $parentId;
            $this->setModelSearch($modelSearch);
        } else {
            /**
             * FIX per RC-372. Parametro presente anche altrove in questo file. Se questo metodo viene usato nei widget grafici,
             * questo parametro (commentato) dev'essere settato là dentro e non qua. Quindi se serve questo parametro spostarlo nel posto corretto
             * e si deve rimuovere questo else.
             */
            //            $params['fromWidgetGraphic'] = true;
        }

        $this->setCurrentView($this->getAvailableView($this->myCurrentView));

        if (empty($this->getCurrentView())) {
            $this->setCurrentView($this->getAvailableView('list'));
        }

        $this->setDataProvider($this->getModelSearch()->searchOwnInterest($params));

        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', '#page_title_own_interest'));

        $this->setListViewsParams();

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        $this->view->title = AmosDocumenti::t('amosdocumenti', 'Documenti di mio interesse');
        $this->view->params['titleSection'] = $this->view->title;

        $this->view->params['bulletCount'] = $this->model->getBullet(\open20\amos\core\record\Record::BULLET_TYPE_OWN, true);

        if (!\Yii::$app->user->isGuest) {
            $this->view->params['titleSection'] = AmosDocumenti::t('amosdocumenti', 'Documenti di mio interesse');
            $this->view->params['labelLinkAll'] = AmosDocumenti::t('amosdocumenti', 'Tutti i documenti');
            $this->view->params['urlLinkAll'] = '/documenti/documenti/all-documents';
            $this->view->params['titleLinkAll'] = AmosDocumenti::t('amosdocumenti', 'Visualizza la lista dei documenti');
        }

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null
            ]
        );
    }

    /**
     * Action to search to validate documents.
     *
     * @param int|null $parentId - id of document folder
     * @return string
     */
    public function actionToValidateDocuments($parentId = null)
    {
        Url::remember();

        $params = Yii::$app->request->getQueryParams();
        if (!is_null($parentId)) { //set parent Id to filter documents within a folder
            $modelSearch = $this->getModelSearch();
            $modelSearch->parentId = $parentId;
            $this->setModelSearch($modelSearch);
        } else {
            /**
             * FIX per RC-372. Parametro presente anche altrove in questo file. Se questo metodo viene usato nei widget grafici,
             * questo parametro (commentato) dev'essere settato là dentro e non qua. Quindi se serve questo parametro spostarlo nel posto corretto
             * e si deve rimuovere questo else.
             */
            //            $params['fromWidgetGraphic'] = true;
        }

        $this->setDataProvider($this->getModelSearch()->searchToValidateDocuments($params));
        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', '#page_title_to_validate'));

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag(
                    'p',
                    AmosDocumenti::tHtml('amosdocumenti', 'Tabella')
                ),
                'url' => '?currentView=grid'
            ]
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setListViewsParams();

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();


        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null
            ]
        );
    }

    /**
     * Action for search all documenti.
     *
     * @param int|null $parentId - id of document folder
     * @return string
     * @throws InvalidConfigException
     */
    public function actionAllDocuments($currentView = null, $parentId = null)
    {
        if ($this->documentsModule->enableExploreDocumentsInIndex == true && $this->scope) {
            return $this->redirect(['/documenti/documenti/explore-documents']);
        }
        Url::remember();

        Yii::$app->session->set('stanzePath', []);
        Yii::$app->session->set('foldersPath', []);
        if (!is_null($parentId)) {
            $folder = Documenti::findOne($parentId);
            $modelSearch = $this->getModelSearch();
            $modelSearch->parentId = $parentId;
            $this->setModelSearch($modelSearch);


            if (isset($parentId) && !is_null($folder->parent_id)) {
                $this->view->params['breadcrumbs'][] = ['label' => Yii::t('amosdocumenti', 'Tutti i documenti'), 'url' => ['index']];
            }
            $this->view->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
            $this->view->params['breadcrumbs'][] = AmosDocumenti::t('amosdocumenti', 'Aggiorna');

            $this->view->params['forceBreadcrumbs'][] = [
                'label' => AmosDocumenti::t('amosdocumenti', 'Documenti'),
                'url' => Yii::$app->session->get('previousUrl'),
            ];

            $this->view->params['forceBreadcrumbs'][] = [
                'label' => AmosDocumenti::t('amosdocumenti', ucfirst($folder->getTitle())),
            ];
        }

        $dataProvider = $this->modelSearch->searchAllDocuments(Yii::$app->request->getQueryParams());
        $this->setDataProvider($dataProvider);

        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', '#page_title_all'));
        $this->setCurrentView($this->getAvailableView($this->myCurrentView));
        $this->setListViewsParams();

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
        $this->view->title = 'Tutti i documenti';
        \Yii::$app->params['titleSection'] = $this->view->title;

        $this->view->params['bulletCount'] = $this->model->getBullet(\open20\amos\core\record\Record::BULLET_TYPE_ALL, true);


        if (!\Yii::$app->user->isGuest) {
            $this->view->params['titleSection'] = AmosDocumenti::t('amosdocumenti', 'Tutti i documenti');
            $this->view->params['labelLinkAll'] = AmosDocumenti::t('amosdocumenti', 'Documenti di mio interesse');
            $this->view->params['urlLinkAll'] = AmosDocumenti::t('amosdocumenti', '/documenti/documenti/own-interest-documents');
            $this->view->params['titleLinkAll'] = AmosDocumenti::t('amosdocumenti', 'Visualizza la lista dei documenti di mio interesse');
        }

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null
            ]
        );
    }

    public static function getCwhScopeActive()
    {
        $cwh = \Yii::$app->getModule("cwh");

        if (!empty($cwh)) {
            $scope = $cwh->getCwhScope();
            if (!empty($scope)) {
                return true;
            }
        }
        return false;
    }


    public function actionExploreDocuments()
    {
        if ($this->documentsModule->enableExploreDocumentsInIndex != true || !$this->scope) {
            return $this->redirect(['/documenti/documenti/all-documents']);
        }


        Url::remember();

        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', 'Documenti'));
        $this->setListViewsParams();

        $this->setUpLayout('list');

        $this->view->params['titleSection'] = AmosDocumenti::t('amosdocumenti', 'Esplora i Documenti');
        $this->view->params['labelLinkAll'] = '';
        $this->view->params['urlLinkAll'] = '';
        $this->view->params['titleLinkAll'] = '';
        $this->view->params['hideCreate'] = $this->documentsModule->hideCreateOnExploreDocuments;
        $this->view->params['hideSecondAction'] = $this->documentsModule->hideSecondActionOnExploreDocuments;

        return $this->render('explorer_befe');
    }

    /**
     * Get all the documents without any visibility/status filters
     *
     * @param null $currentView
     * @param int|null $parentId - id of document folder
     * @return string
     */
    public function actionAdminAllDocuments($currentView = null, $parentId = null)
    {
        $previousDocumentParentIds = $this->getParentsFromPreviousDocument();

        Url::remember();

        if (!is_null($parentId)) { //set parent Id to filter documents within a folder
            $modelSearch = $this->getModelSearch();
            $modelSearch->parentId = $parentId;
            $this->setModelSearch($modelSearch);
        }

        $dataProvider = $this->modelSearch->searchAdminAll(Yii::$app->request->getQueryParams());
        $this->setDataProvider($dataProvider);

        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', '#page_title_all_admin'));

        //$this->setCurrentView($this->getAvailableView($this->myCurrentView));
        // Force only grid view
        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setAvailableViews(['grid' => $this->viewGrid]);

        $this->setListViewsParams();

        $this->setUpLayout('list');

        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        $this->view->params['containerFullWidth'] = true;
        $this->view->title = 'Amministra documenti';

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null,
                'previousDocumentParentIds' => $previousDocumentParentIds
            ]
        );
    }

    /**
     * If a given document model is a folder or not
     * @param Documenti $model
     * @return bool
     */
    public function documentIsFolder($model)
    {
        return (isset($model->is_folder) && $model->is_folder);
    }

    /**
     * Render the sub-table of version, it's called with ajax
     * @return string
     * @throws \Exception
     */
    public function actionListOnly()
    {
        $expandRowKey = \Yii::$app->request->post('expandRowKey');
        $actionId = $this->action->id;
        $hidePubblicationDate = $this->documentsModule->hidePubblicationDate;

        $queryParams['parent_id'] = $expandRowKey;
        $dataProvider = $this->getModelSearch()->searchVersions($queryParams);
        $dataProvider->sort = false;
        $canUpdate = Yii::$app->user->can('DOCUMENTI_UPDATE', ['model' => $this->model]);

        $btnCreate = '';
        if ($canUpdate) {
            $btnCreate = CreateNewButtonWidget::widget([
                'model' => $this->model,
                'createNewBtnLabel' => AmosDocumenti::t('amosdocumenti', 'Create new version'),
                'urlCreateNew' => ['/documenti/documenti/new-document-version', 'id' => $expandRowKey],
                'btnClasses' => 'btn btn-success pull-right',
                'otherOptions' => ['title' => AmosDocumenti::t('amosdocumenti', 'Create new version')]
            ]);
        }

        try {
            return GridView::widget([
                'id' => 'product-gridview',
                'dataProvider' => $dataProvider,
                'responsive' => true,
                'export' => false,
                'pjax' => true,
                'pjaxSettings' => [
                    'options' => [
                        'id' => 'product-grid',
                        'timeout' => (isset(\Yii::$app->params['timeout']) ? \Yii::$app->params['timeout'] : 1000),
                        'enablePushState' => false
                    ]
                ],
                'columns' => [
                    [
                        'label' => AmosDocumenti::t('amosdocumenti', '#type'),
                        'format' => 'html',
                        'value' => function ($model) {
                            return DocumentsUtility::getDocumentIcon($model);
                        },
                    ],
                    [
                        'attribute' => 'titolo',
                        'format' => 'html',
                        'value' => function ($model) use ($actionId) {
                            /** @var Documenti $model */
                            $title = $model->titolo;
                            if ($model->is_folder) {
                                $url = [$actionId, 'parentId' => $model->id];
                            } else {
                                $file = $model->getDocumentMainFile();
                                if (!empty($file)) {
                                    $url = $model->getDocumentMainFile()->getUrl();
                                } else {
                                    $url = $model->link_document;
                                }
                            }

                            return Html::a(
                                $title,
                                $url,
                                ['title' => AmosDocumenti::t('amosdocumenti', 'Scarica il documento') . '"' . $model->titolo . '"']
                            );
                        }
                    ],
                    [
                        'attribute' => 'createdUserProfile',
                        'label' => AmosDocumenti::t('amosdocumenti', '#updated_by'),
                        'value' => function ($model) {
                            return Html::a(
                                $model->createdUserProfile->nomeCognome,
                                ['/amosadmin/user-profile/view', 'id' => $model->createdUserProfile->id],
                                [
                                    'title' => AmosDocumenti::t(
                                        'amosdocumenti',
                                        'Apri il profilo di {nome_profilo}',
                                        ['nome_profilo' => $model->createdUserProfile->nomeCognome]
                                    )
                                ]
                            );
                        },
                        'format' => 'html'
                    ],
                    //                    [
                    //                        'attribute' => 'updatedUserProfile',
                    //                        'label' => AmosDocumenti::t('amosdocumenti', '#updated_by'),
                    //                        'value' => function($model){
                    //                            return Html::a($model->updatedUserProfile->nomeCognome, ['/amosadmin/user-profile/view', 'id' => $model->updatedUserProfile->id ], [
                    //                                'title' => AmosDocumenti::t('amosdocumenti', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $model->updatedUserProfile->nomeCognome])
                    //                            ]);
                    //                        },
                    //                        'format' => 'html'
                    //                    ],
                    'data_pubblicazione' => [
                        'attribute' => 'data_pubblicazione',
                        'value' => function ($model) {
                            /** @var Documenti $model */
                            return (is_null($model->data_pubblicazione)) ? 'Subito' : Yii::$app->formatter->asDate($model->data_pubblicazione);
                        },
                        'label' => AmosDocumenti::t('amosdocumenti', '#uploaded_at'),
                    ],
                    'version',
                    [
                        'class' => 'open20\amos\core\views\grid\ActionColumn',
                        'template' => '{view}',
                    ],
                ],
                'panelHeadingTemplate' => '<div class="pull-right">
                    </div>
                    <h3 class="panel-title">
                        ' . AmosDocumenti::t('amosdocumenti', 'Versions') . '
                    </h3>
                    <div class="clearfix"></div>',
                'panel' => [
                    'before' => false,
                    'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i>&nbsp;' . AmosDocumenti::t(
                        'amosdocumenti',
                        'Old versions'
                    ) . '</h3>'
                        . $btnCreate,
                    'type' => 'success',
                    'after' => false,
                    'footer' => false,
                ],
            ]);
        } catch (\Exception $e) {
            pr($e->getTraceAsString());
            return $e->getMessage();
        }
    }

    /**
     * @param string $view
     * @return string
     * @throws \Exception
     */
    public function actionExpandFolderDocuments($view = '_subFolderGridView')
    {
        $expandRowKey = \Yii::$app->request->post('expandRowKey');
        // Params from original action
        $actionId = Yii::$app->request->post('actionId');
        $queryParams = Yii::$app->request->post('queryParams');
        $previousDocumentParentIds = Yii::$app->request->post('previousDocumentParentIds');

        $modelSearch = $this->modelSearch;
        $modelSearch->parentId = $expandRowKey;

        switch ($actionId) {
            case 'own-interest-documents':
                $dataProvider = $modelSearch->searchOwnInterest($queryParams);
                break;
            case 'to-validate-documents':
                $dataProvider = $modelSearch->searchToValidateDocuments($queryParams);
                break;
            case 'admin-all-documents':
                $dataProvider = $modelSearch->searchAdminAll($queryParams);
                break;
            default:
                $dataProvider = $modelSearch->searchAllDocuments($queryParams);
                break;
        }

        $dataProvider->pagination = false;

        \Yii::$app->request->setQueryParams(['aid' => $expandRowKey]);

        return Yii::$app->controller->renderAjax($view, [
            'dataProvider' => $dataProvider,
            'actionId' => $actionId,
            'previousDocumentParentIds' => $previousDocumentParentIds
        ]);
    }

    public function sendNotificationEmail()
    {
        $idGroupsToMail = [];
        $idUserToMail = [];
        if (!empty(Yii::$app->request->post('selection-groups'))) {
            $idGroupsToMail = Yii::$app->request->post('selection-groups');
        }
        if (!empty(Yii::$app->request->post('selection-profiles'))) {
            $user_profiles_ids = Yii::$app->request->post('selection-profiles');
            foreach ($user_profiles_ids as $id) {
                $profile = UserProfile::findOne($id);
                if ($profile) {
                    $idUserToMail[] = $profile->user->id;
                }
            }
        }

        foreach ($idGroupsToMail as $idGroup) {
            $group = \open20\amos\groups\models\Groups::findOne($idGroup);
            if ($group) {
                $members = $group->groupsMembers;
                /** @var  $member \open20\amos\groups\models\GroupsMembers */
                foreach ($members as $member) {
                    $idUserToMail[] = $member->user_id;
                }
            }
        }

        // if you have not selected  any groups or users, send the notification to all member of community
        //        if(empty(Yii::$app->request->post('selection-groups')) && empty(Yii::$app->request->post('selection-profiles'))) {
        //            $cwh = Yii::$app->getModule("cwh");
        //            $community = Yii::$app->getModule("community");
        //            if (isset($cwh) && isset($community)) {
        //                $cwh->setCwhScopeFromSession();
        //                if (!empty($cwh->userEntityRelationTable)) {
        //                    $entityId = $cwh->userEntityRelationTable['entity_id'];
        //                    $community = \open20\amos\community\models\Community::findOne($entityId);
        //                    if(!empty($community)) {
        //                        $usersMms = $community->communityUserMms;
        //                        foreach ($usersMms as $memberComm){
        //                            $idUserToMail []= $memberComm->user_id;
        //                        }
        //                    }
        //                }
        //            }
        //        }
        // deleted duplicated id
        $idUserToMail = array_unique($idUserToMail);
        $controller = \Yii::$app->controller;
        $modelCreator = UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
        $ris = $controller->renderMailPartial(
            'email' . DIRECTORY_SEPARATOR . 'content',
            [
                'modelCreator' => $modelCreator,
                'modelDocument' => $this->model,
            ]
        );
        DocumentsUtility::sendEmail($idUserToMail, AmosDocumenti::t('amosdocumenti', 'Document uploaded'), $ris, []);
    }

    /**
     * @param $model
     * @param null $previousStatus
     * @return \yii\web\Response
     */
    protected function redirectOnUpdate($model, $previousStatus = null)
    {
        // if you have the permission of update or you can validate the content you will be redirected on the update page
        // otherwise you will be redirected on the index page
        $redirectToUpdatePage = false;
        if (Yii::$app->getUser()->can('DOCUMENTI_UPDATE', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }
        if (Yii::$app->getUser()->can('DocumentValidate', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }

        if (!$previousStatus) {
            $redirectToUpdatePage = false;
        }
        if ($redirectToUpdatePage) {
            if ($model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) {
                if (!empty(\Yii::$app->params['enablePositionalBreadcrumb']) && \Yii::$app->params['enablePositionalBreadcrumb'] == true) {
                    return $this->redirect(PositionalBreadcrumbHelper::lastCrumbUrl());
                } else {
                    return $this->redirect(BreadcrumbHelper::lastCrumbUrl());
                }
            } elseif (($model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA) && ($previousStatus == Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE)) {
                if (!empty(\Yii::$app->params['enablePositionalBreadcrumb']) && \Yii::$app->params['enablePositionalBreadcrumb'] == true) {
                    return $this->redirect(PositionalBreadcrumbHelper::lastCrumbUrl());
                } else {
                    return $this->redirect(BreadcrumbHelper::lastCrumbUrl());
                }
            } else {
                return $this->redirect(['/documenti/documenti/update', 'id' => $model->id]);
            }
        } else {
            if (!empty(\Yii::$app->params['enablePositionalBreadcrumb']) && \Yii::$app->params['enablePositionalBreadcrumb'] == true) {
                return $this->redirect(PositionalBreadcrumbHelper::lastCrumbUrl());
            } else {
                return $this->redirect(BreadcrumbHelper::lastCrumbUrl());
            }
        }
    }

    /**
     * @param $model
     * @return \yii\web\Response
     */
    protected function redirectOnCreate($model)
    {
        // if you have the permission of update or you can validate the content you will be redirected on the update page
        // otherwise you will be redirected on the index page with the contents created by you
        $redirectToUpdatePage = false;

        if (Yii::$app->getUser()->can('DOCUMENTI_UPDATE', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }

        if (Yii::$app->getUser()->can('DocumentValidate', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }

        if ($redirectToUpdatePage) {
            return $this->redirect(['/documenti/documenti/update', 'id' => $model->id]);
        } else {
            return $this->redirect('/documenti/documenti/own-documents');
        }
    }

    /**
     * @param $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionPublic($id)
    {
        $model = $this->findModel($id);
        $this->layout = 'form';
        if ($this->isContentShared($id)) {
            return $this->render('public', ['model' => $model]);
        }
    }

    /**
     * Provides upload file
     *
     * @param $attribute
     * @param $communityId
     * @param $parentId
     *
     * @return mixed
     */
    public function actionUpload($attribute, $communityId, $parentId = null, $status = null)
    {
        //Json format for this response as is required for FileInput
        \Yii::$app->response->format = Response::FORMAT_JSON;

        //The uploaded file (we prey is only one)
        if (isset($_FILES['files'])) {
            $file = $_FILES['files'];

            //Base file path
            $filePath = realpath(\Yii::getAlias("@app/../common/uploads/temp/"));

            //New File Location
            $fileLocation = $filePath . DIRECTORY_SEPARATOR . $file['name'];

            //Move the filed to valid cms location
            move_uploaded_file($file['tmp_name'], $fileLocation);

            $muController = new MultipleUploaderController('default', $this->module);

            //Setup env
            $muController->setupEnv();

            $parentDoc = null;

            //If the parent is set
            if ($parentId) {
                /** @var Documenti $documentiModel */
                $documentiModel = $this->documentsModule->createModel('Documenti');
                $parentDoc = $documentiModel::findOne(['id' => $parentId]);
            }

            //Create a new document withoud any notification
            $documento = $muController->createDocument(
                [
                    'name' => $file['name'],
                    'path' => urlencode($fileLocation)
                ],
                $parentDoc,
                false,
                $communityId,
                urldecode($status)
            );

            //If the document is created return a completed message
            if (!empty($documento) && $documento->id) {
                //Session key
                $sessionKey = 'multiupload_' . $communityId;

                //already uploaded files
                $uploadedIds = Yii::$app->session->get($sessionKey);

                //Add the new id
                $uploadedIds[] = $documento->id;

                //Set in session the new file for this community
                Yii::$app->session->set($sessionKey, $uploadedIds);

                //
                return [
                    'documentId' => $documento->id,
                    'confirm' => true
                ];
            } elseif ($documento) {
                return $documento->getErrors();
            } else {
                throw new Exception('Unable to create the document');
            }
        }

        return ['error' => 'failed-upload'];
    }

    /**
     * @param $fileHash
     * @param $useStorePath
     * @return string
     */
    public function getFilesDirPath($fileHash, $useStorePath = true)
    {
        if ($useStorePath) {
            $path = $this->getStorePath() . DIRECTORY_SEPARATOR . $this->getSubDirs($fileHash);
        } else {
            $path = DIRECTORY_SEPARATOR . $this->getSubDirs($fileHash);
        }

        FileHelper::createDirectory($path, 0777);

        return $path;
    }

    /**
     * @return bool|string
     */
    public function getStorePath()
    {
        return \Yii::getAlias($this->storePath);
    }

    /**
     * @param $fileHash
     * @param int $depth
     * @return string
     */
    public function getSubDirs($fileHash, $depth = 3)
    {
        $depth = min($depth, 9);
        $path = '';

        for ($i = 0; $i < $depth; $i++) {
            $folder = substr($fileHash, $i * 3, 2);
            $path .= $folder;
            if ($i != $depth - 1) $path .= DIRECTORY_SEPARATOR;
        }

        return $path;
    }

    /**
     *
     * @param type $scopeId
     */
    private function setScope($scopeId)
    {
        $this->moduleCwh->setCwhScopeInSession(
            [
                'community' => $scopeId, // simple cwh scope for contents filtering, required
            ],
            [
                // cwhRelation array specifying name of relation table, name of entity field on relation table and entity id field ,
                // optional for compatibility with previous versions
                'mm_name' => 'community_user_mm',
                'entity_id_field' => 'community_id',
                'entity_id' => $scopeId
            ]
        );
    }

    /**
     *
     * @param type $id
     */
    private function setRouteStanze($id)
    {
        $routeStanze = Yii::$app->session->get('stanzePath', []);
        if (sizeof($routeStanze) > 0) {
            $routeStanze[] = [
                'name' => Community::findOne(['id' => $id])->name,
                'scope_id' => $id,
                'isArea' => 0,
            ];
            Yii::$app->session->set('stanzePath', $routeStanze);
        } else {
            Yii::$app->session->set(
                'stanzePath',
                [
                    [
                        'name' => Community::findOne(['id' => $id])->name,
                        'scope_id' => $id,
                        'isArea' => 1,
                    ]
                ]
            );
        }

        $this->setScope($id);
    }

    /**
     *
     * @param type $id
     */
    private function setFoldersPath($id)
    {
        $foldersPath = Yii::$app->session->get('foldersPath', []);
        /** @var Documenti $documentiModel */
        $documentiModel = $this->documentsModule->createModel('Documenti');
        if (array_key_exists('links', $foldersPath)) {
            if (sizeof($foldersPath['links']) > 0) {
                $foldersPath['links'][sizeof($foldersPath['links']) - 1]['classes'] = 'link';
                $foldersPath['links'][sizeof($foldersPath['links']) - 1]['isNotLast'] = true;
                $foldersPath['links'][] = [
                    'classes' => '',
                    'model-id' => $id,
                    'name' => $documentiModel::findOne(['id' => $id])->titolo,
                ];
                Yii::$app->session->set('foldersPath', $foldersPath);
            } else {
                Yii::$app->session->set(
                    'foldersPath',
                    [
                        'links' => [
                            [
                                'classes' => '',
                                'model-id' => $id,
                                'name' => $documentiModel::findOne(['id' => $id])->titolo,
                            ],
                        ]
                    ]
                );
            }
        } else {
            Yii::$app->session->set(
                'foldersPath',
                [
                    'links' => [
                        [
                            'classes' => '',
                            'model-id' => $id,
                            'name' => $documentiModel::findOne(['id' => $id])->titolo,
                        ],
                    ]
                ]
            );
        }
    }

    /**
     *
     * @param type $scopeId
     */
    private function resetFoldersPath($scopeId)
    {
        Yii::$app->session->set(
            'foldersPath',
            [
                'links' => [
                    [
                        'classes' => '',
                        'model-id' => '',
                        'name' => Community::findOne(['id' => $scopeId])->name
                    ],
                ]
            ]
        );
    }

    /**
     *
     * @param type $id
     * @param type $openScheda
     * @return type
     */
    public function actionGoToView($id, $openScheda = false)
    {
        $this->setRouteStanze($id);
        $this->resetFoldersPath($id);

        return $this->redirect('/community/community/view?id=' . $id . ($openScheda ? '#tab-registry' : ''));
    }

    /**
     *
     * @param type $id
     * @return type
     */
    public function actionGoToParticipantsTab($id)
    {
        $this->setRouteStanze($id);
        $this->resetFoldersPath($id);

        return $this->redirect('/community/community/update?id=' . $id . '&tabActive=tab-participants');
    }

    /**
     *
     * @param type $id
     * @return type
     */
    public function actionGoToUpdate($id)
    {
        $this->setRouteStanze($id);
        $this->resetFoldersPath($id);

        return $this->redirect('/community/community/update?id=' . $id);
    }

    /**
     *
     * @param type $id
     * @return type
     */
    public function actionGoToGroups($id)
    {
        $this->setRouteStanze($id);
        $this->resetFoldersPath($id);
        return $this->redirect('/groups/groups');
    }

    /**
     * @param type $id
     */
    public function actionGoToJoin($id)
    {
        $this->setRouteStanze($id);
        $this->resetFoldersPath($id);
        return $this->redirect('/community/join?id=' . $id);
    }

    /**
     * @param type $id
     */
    public function actionGoToUpdateFolder($id)
    {
        $this->setFoldersPath($id);

        return $this->redirect('/documenti/documenti/update?id=' . $id . '&from=dashboard');
    }

    /**
     * @param type @id
     */
    public function actionGoToViewFolder($id)
    {
        $this->setFoldersPath($id);

        return $this->redirect('/documenti/documenti/view?id=' . $id);
    }

    /**
     * @param $id
     * @return bool
     */
    public function actionIncrementCountDownloadLink($id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $this->model = $this->findModel($id);
        $this->model->detachBehaviors();
        $this->model->count_link_download = $this->model->count_link_download + 1;
        $this->model->save(false);
        return true;
    }

    public function actionGetCategoryAnnualArchiving()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $response = true;
        $id = Yii::$app->request->post('id', 0);
        $model = DocumentiCategorie::findOne(['id' => $id]);
        if ($model)
            $response = (bool)$model->enable_annual_archiving;

        return ['value' => $response];
    }

    public function actionGetUnpublishingDate()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $module = \Yii::$app->getModule(AmosDocumenti::getModuleName());
        $response = $module->enableCountdownUnpublishing;
        $years = $module->yearsCountdownUnpublishing;

        $id = Yii::$app->request->post('id', 0);
        $from_date = Yii::$app->request->post('from', '');

        $model = DocumentiCategorie::findOne(['id' => $id]);
        if ($model) {
            $years = $model->years_countdown_unpublishing;
        }

        $date = (new \DateTime($from_date))->modify("+$years years")->format('Y-m-d');
        $date_format = (new \DateTime($from_date))->modify("+$years years")->format('d-m-Y');
        return ['response' => $response, 'value' => $date, 'value_format' => $date_format, 'years' => $years];
    }

    /**
     * @param $id
     * @return Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionSyncDocFile($id)
    {
        $this->model = $this->findModel($id);
        $googleDriveManager = new \open20\amos\documenti\utility\GoogleDriveManager(['model' => $this->model, 'useServiceAccount' => true]);
        $googleDriveManager->getFileAndSave($this->model->drive_file_id);
        if (\Yii::$app->request->referrer) {
            return $this->redirect(\Yii::$app->request->referrer);
        }
        return $this->redirect(['update', 'id' => $this->model->id]);
    }

    /**
     * @param $id
     * @return bool
     */
    public function actionIsGoogleDriveDocumentModified($id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $this->model = $this->findModel($id);
        $googleDriveManager = new \open20\amos\documenti\utility\GoogleDriveManager([
            'model' => $this->model,
            'useServiceAccount' => true
        ]);
        return $googleDriveManager->isDocumentUpdated();
    }

    /**
     * Write here the operations before duplicate the content.
     * @return bool
     */
    protected function beforeDuplicateContent()
    {
        $isDocument = $this->model->isDocument();
        if (!$isDocument) {
            Yii::$app->getSession()->addFlash(
                'danger',
                AmosDocumenti::t('amosdocumenti', '#duplicate_content_document_before_duplicate_error_is_folder')
            );
        }
        return $isDocument;
    }

    /**
     * action per l'inserimento veloce dei dati dentro le tabelle
     * documenti_agid_content_type, documenti_agid_type
     *
     * @return void
     */
    public function actionInsertDocumentContentType()
    {

        // insert value in to table documenti_agid_content_type

        $documenti_agid_content_type = [
            'Bandi',
            'Curriculum Vitae',
            'Atto di nomina',
            'Ordinanze',
            'Modulistica',
            'Atti normativi',
            'Documenti (tecnici) di supporto',
            'Amministrazione trasparente'
        ];

        foreach ($documenti_agid_content_type as $key => $value) {

            // check if already exist
            if (null == DocumentiAgidContentType::find()->where(["name" => $value])->one()) {

                $model_documenti_agid_content_type = new DocumentiAgidContentType;
                $model_documenti_agid_content_type->name = $value;

                $model_documenti_agid_content_type->save();


                if (strcmp($value, 'Bandi') == 0) {

                    $documenti_agid_type['Bandi'] = [
                        'Bandi di concorso',
                        'Nomine in società ed enti',
                        'Bandi immobiliari',
                        'Bandi per contributi',
                        'Altri bandi e avvisi'
                    ];

                    // insert value in to table documenti_agid_type

                    foreach ($documenti_agid_type['Bandi'] as $key => $value) {

                        $model_documenti_agid_type = new DocumentiAgidType;
                        $model_documenti_agid_type->name = $value;
                        $model_documenti_agid_type->agid_document_content_type_id = $model_documenti_agid_content_type->id;

                        $model_documenti_agid_type->save();
                    }
                } elseif (strcmp($value, 'Curriculum Vitae') == 0) {

                    $documenti_agid_type['Curriculum Vitae'] = [
                        'Curriculum Vitae'
                    ];

                    foreach ($documenti_agid_type['Curriculum Vitae'] as $key => $value) {

                        $model_documenti_agid_type = new DocumentiAgidType;
                        $model_documenti_agid_type->name = $value;
                        $model_documenti_agid_type->agid_document_content_type_id = $model_documenti_agid_content_type->id;

                        $model_documenti_agid_type->save();
                    }
                } elseif (strcmp($value, 'Atto di nomina') == 0) {

                    $documenti_agid_type['Atto di nomina'] = [
                        'Atto di nomina'
                    ];

                    foreach ($documenti_agid_type['Atto di nomina'] as $key => $value) {

                        $model_documenti_agid_type = new DocumentiAgidType;
                        $model_documenti_agid_type->name = $value;
                        $model_documenti_agid_type->agid_document_content_type_id = $model_documenti_agid_content_type->id;

                        $model_documenti_agid_type->save();
                    }
                } elseif (strcmp($value, 'Ordinanze') == 0) {

                    $documenti_agid_type['Ordinanze'] = [
                        'Ordinanze'
                    ];

                    foreach ($documenti_agid_type['Ordinanze'] as $key => $value) {

                        $model_documenti_agid_type = new DocumentiAgidType;
                        $model_documenti_agid_type->name = $value;
                        $model_documenti_agid_type->agid_document_content_type_id = $model_documenti_agid_content_type->id;

                        $model_documenti_agid_type->save();
                    }
                } elseif (strcmp($value, 'Modulistica') == 0) {

                    $documenti_agid_type['Modulistica'] = [
                        'Modulistica'
                    ];

                    foreach ($documenti_agid_type['Modulistica'] as $key => $value) {

                        $model_documenti_agid_type = new DocumentiAgidType;
                        $model_documenti_agid_type->name = $value;
                        $model_documenti_agid_type->agid_document_content_type_id = $model_documenti_agid_content_type->id;

                        $model_documenti_agid_type->save();
                    }
                } elseif (strcmp($value, 'Atti normativi') == 0) {

                    $documenti_agid_type['Atti normativi'] = [
                        'Atti normativi',
                        'Statuto comunale',
                        'Regolamenti'
                    ];

                    foreach ($documenti_agid_type['Atti normativi'] as $key => $value) {

                        $model_documenti_agid_type = new DocumentiAgidType;
                        $model_documenti_agid_type->name = $value;
                        $model_documenti_agid_type->agid_document_content_type_id = $model_documenti_agid_content_type->id;

                        $model_documenti_agid_type->save();
                    }
                } elseif (strcmp($value, 'Documenti (tecnici) di supporto') == 0) {

                    $documenti_agid_type['Documenti (tecnici) di supporto'] = [
                        'Pianificazione urbanistica',
                        'Autorizzazioni paesaggistiche',
                        'Pubblicazioni statistiche'
                    ];

                    foreach ($documenti_agid_type['Documenti (tecnici) di supporto'] as $key => $value) {

                        $model_documenti_agid_type = new DocumentiAgidType;
                        $model_documenti_agid_type->name = $value;
                        $model_documenti_agid_type->agid_document_content_type_id = $model_documenti_agid_content_type->id;

                        $model_documenti_agid_type->save();
                    }
                } elseif (strcmp($value, 'Amministrazione trasparente') == 0) {

                    $documenti_agid_type['Amministrazione trasparente'] = [
                        "Disposizioni generali",
                        "Disposizioni generali - Piano triennale per la prevenzione della corruzione e della trasparenza",
                        "Disposizioni generali - Atti generali",
                        "Disposizioni generali - Documenti di programmazione strategico-gestionale",
                        "Disposizioni generali - Burocrazia zero",
                        "Disposizioni generali - Oneri informativi per cittadini e imprese",
                        "Disposizioni generali - Scadenzario dei nuovi obblighi amministrativi",
                        "Organizzazione",
                        "Organizzazione - Titolari di incarichi politici, di amministrazione, di direzione o di governo",
                        "Organizzazione - Dichiarazioni 2019",
                        "Organizzazione - Mandato 2014-2019",
                        "Organizzazione - Sanzioni per mancata comunicazione dei dati",
                        "Organizzazione - Articolazione degli uffici",
                        "Organizzazione - Telefono e posta elettronica",
                        "Organizzazione - Rendiconti gruppi consiliari regionali/provinciali",
                        "Consulenti e collaboratori",
                        "Consulenti e collaboratori - Titolari di incarichi di collaborazione o consulenza",
                        "Consulenti e collaboratori - Collegio dei Revisori dei Conti – triennio 2015-2018",
                        "Consulenti e collaboratori - Collegio dei Revisori dei Conti – triennio 2018-2021",
                        "Personale",
                        "Personale - Titolari di incarichi dirigenziali amministrativi di vertice",
                        "Personale - Titolari di incarichi dirigenziali (dirigenti non generali)",
                        "Personale - Archivio dichiarazioni insussistenza cause di incompatibilità e inconferibilità",
                        "Personale - Archivio estremi degli atti di conferimento degli incarichi dirigenziali",
                        "Personale - Dirigenti cessati",
                        "Personale - Posizioni Organizzative",
                        "Personale - Archivio Posizioni Organizzative",
                        "Personale - Posizioni Organizzative cessate",
                        "Personale - Dotazione organica",
                        "Personale - Personale non a tempo indeterminato",
                        "Personale - Tassi di assenza",
                        "Personale - Archivio Tassi di assenza",
                        "Personale - Incarichi conferiti e autorizzati ai dipendenti (dirigenti e non dirigenti)",
                        "Personale - Contrattazione collettiva",
                        "Personale - Contrattazione integrativa",
                        "Personale - OIV",
                        "Personale - Sanzioni per mancata comunicazione dei dati",
                        "Bandi di concorso",
                        "Performance",
                        "Performance - Sistema di misurazione e valutazione della Performance",
                        "Performance - Piano della Performance",
                        "Performance - Piano della Performance - 2019-2021",
                        "Performance - Piano della Performance - 2018-2020",
                        "Performance - Piano della Performance - 2017-2019",
                        "Performance - Piano della Performance - 2016-2018",
                        "Performance - Piano della Performance - 2015-2017",
                        "Performance - Piano della Performance - 2014-2016",
                        "Performance - Piano della Performance - Anno 2013",
                        "Performance - Relazione sulla Performance",
                        "Performance - Ammontare complessivo dei premi",
                        "Performance - Dati relativi ai premi",
                        "Performance - Benessere organizzativo",
                        "Enti controllati",
                        "Enti controllati - Enti pubblici vigilati",
                        "Enti controllati - Società partecipate",
                        "Enti controllati - Enti di diritto privato controllati",
                        "Enti controllati - Rappresentazione grafica",
                        "Attività e procedimenti",
                        "Attività e procedimenti - Dati aggregati attività amministrativa",
                        "Attività e procedimenti - Tipologie di procedimento",
                        "Attività e procedimenti - Monitoraggio tempi procedimentali",
                        "Attività e procedimenti - Dichiarazioni sostitutive e acquisizione d'ufficio dei dati",
                        "Provvedimenti",
                        "Provvedimenti - Provvedimenti organi indirizzo politico",
                        "Provvedimenti - Provvedimenti dirigenti amministrativi",
                        "Controlli sulle imprese",
                        "Bandi di gara e contratti",
                        "Bandi di gara e contratti - Informazioni sulle singole procedure in formato tabellare",
                        "Bandi di gara e contratti - Atti delle amministrazioni aggiudicatrici e degli enti aggiudicatori distintamente per ogni procedura",
                        "Bandi di gara e contratti - Atti relativi a procedimenti per i quali non è richiesta l'acquisizione del CIG",
                        "Sovvenzioni, contributi, sussidi, vantaggi economici",
                        "Sovvenzioni, contributi, sussidi, vantaggi economici - Criteri e modalità",
                        "Sovvenzioni, contributi, sussidi, vantaggi economici - Atti di concessione",
                        "Bilanci",
                        "Bilanci - Bilancio preventivo e consuntivo",
                        "Bilanci - Piano degli indicatori e dei risultati attesi di bilancio",
                        "Beni immobili e gestione patrimonio",
                        "Beni immobili e gestione patrimonio - Patrimonio immobiliare",
                        "Beni immobili e gestione patrimonio - Canoni di locazione o affitto",
                        "Controlli e rilievi sull'amministrazione",
                        "Controlli e rilievi sull'amministrazione - Organismi indipendenti di valutazione, nuclei di valutazione o altri organismi con funzioni analoghe",
                        "Controlli e rilievi sull'amministrazione - Organi di revisione amministrativa e contabile",
                        "Controlli e rilievi sull'amministrazione - Corte dei conti",
                        "Servizi erogati",
                        "Servizi erogati - Carta dei servizi e standard di qualità",
                        "Servizi erogati - Costi contabilizzati",
                        "Servizi erogati - Class action",
                        "Servizi erogati - Servizi in rete",
                        "Pagamenti dell'amministrazione",
                        "Pagamenti dell'amministrazione - IBAN e pagamenti informatici",
                        "Pagamenti dell'amministrazione - Indicatore di tempestività dei pagamenti",
                        "Pagamenti dell'amministrazione - Dati sui pagamenti",
                        "Opere pubbliche",
                        "Opere pubbliche - Atti di programmazione delle opere pubbliche",
                        "Opere pubbliche - Tempi costi e indicatori di realizzazione delle opere pubbliche",
                        "Pianificazione e governo del territorio",
                        "Informazioni ambientali",
                        "Interventi straordinari e di emergenza",
                        "Altri contenuti",
                        "Altri contenuti - Prevenzione della Corruzione",
                        "Altri contenuti - Aggiornamento del Piano triennale di prevenzione della corruzione e della trasparenza del Comune di Ferrara - Avviso pubblico",
                        "Altri contenuti - Giornata della trasparenza - anno 2020",
                        "Altri contenuti - Accesso civico",
                        "Altri contenuti - Accessibilità e Catalogo di dati metadati e banche dati",
                        "Altri contenuti - Dati ulteriori",
                        "Altri contenuti - Rilevazione Auto comunali",
                        "Altri contenuti - Referto di controllo di gestione",
                        "Altri contenuti - Destinazione fondi quota 5 per mille IRPEF"
                    ];

                    foreach ($documenti_agid_type['Amministrazione trasparente'] as $key => $value) {

                        $model_documenti_agid_type = new DocumentiAgidType;
                        $model_documenti_agid_type->name = $value;
                        $model_documenti_agid_type->agid_document_content_type_id = $model_documenti_agid_content_type->id;

                        $model_documenti_agid_type->save();
                    }
                }
            } else {

                echo "<br>errore inserimento: " . $value;
            }
        }
    }

    /**
     * action per la restitselect option documenti_agid_type per documenti_agid_content_type
     *
     * @return string
     */
    public function actionGetDocumentiAgidTypeByContentType()
    {

        $post_request = \Yii::$app->request->post();

        $documenti_agid_types = DocumentiAgidType::find()->orderBy(['name' => SORT_ASC])
            ->andWhere([
                'agid_document_content_type_id' => $post_request['documenti_agid_content_type_id']
            ])->andWhere([
                'deleted_at' => null
            ])->all();


        $select_option = '<option value="">Seleziona ...</option>';

        foreach ($documenti_agid_types as $key => $documenti_agid_type) {

            if ($post_request['documenti_agid_type_id'] != $documenti_agid_type->id) {

                $select_option .= "<option value=" . $documenti_agid_type->id . ">" . $documenti_agid_type->name . "</option>";
            } else {

                $select_option .= "<option value=" . $documenti_agid_type->id . " selected>" . $documenti_agid_type->name . "</option>";
            }
        }

        return json_encode($select_option);
    }

    public function actionOnlyofficeEdit($id)
    {
        Url::remember();
		
		 if (!Yii::$app->getUser()->can('DOCUMENTI_UPDATE', ['model' => $this->model])) {
			  throw new ForbiddenHttpException(Yii::t('amosdocumenti', 'Non sei autorizzato a visualizzare questa pagina'));
            //$redirectToUpdatePage = true;
        }

        $this->setUpLayout('form');
        $this->model = $this->findModel($id);
        if (empty($this->model->documentMainFile)) {
            Yii::$app->getSession()->addFlash(
                'danger',
                AmosDocumenti::t('amosdocumenti', 'Prima di poter visualizzare il documento salvare')
            );
            return $this->redirect(['update', 'id' => $id]);
        }

        return $this->render(
            'onlyoffice_edit',
            [
                'model' => $this->model,
            ]
        );
    }

    /**
     *
     * @return array
     */
    public static function getManageLinks()
    {

        $module = \Yii::$app->getModule(AmosDocumenti::getModuleName());

        if (\Yii::$app->user->can(WidgetIconDocumentiCreatedBy::class)) {

            $links[] = [
                'title' => AmosDocumenti::t('amosdocumenti', 'Visualizza i documenti creati da me'),
                'label' => AmosDocumenti::t('amosdocumenti', 'Creati da me'),
                'url' => '/documenti/documenti/own-documents'
            ];
        }

        if ($module->enableExploreDocumentsInIndex == true && self::getCwhScopeActive()) {
            if (\Yii::$app->user->can(WidgetIconAllDocumenti::class)) {
                $links[] = [
                    'title' => AmosDocumenti::t('amosdocumenti', 'Esplora i documenti'),
                    'label' => AmosDocumenti::t('amosdocumenti', 'Esplora tutti i documenti'),
                    'url' => '/documenti/documenti/explore-documents'
                ];
            }
        } else {
            if (\Yii::$app->user->can(WidgetIconDocumenti::class)) {

                $links[] = [
                    'title' => AmosDocumenti::t('amosdocumenti', 'Visualizza i documenti di mio interesse'),
                    'label' => AmosDocumenti::t('amosdocumenti', 'Di mio interesse'),
                    'url' => '/documenti/documenti/own-interest-documents'
                ];
            }

            if (\Yii::$app->user->can(WidgetIconAllDocumenti::class)) {

                $links[] = [
                    'title' => AmosDocumenti::t('amosdocumenti', 'Visualizza tutti i documenti'),
                    'label' => AmosDocumenti::t('amosdocumenti', 'Tutti i documenti'),
                    'url' => '/documenti/documenti/all-documents'
                ];
            }
        }

        if (\Yii::$app->user->can(WidgetIconDocumentiDaValidare::class)) {
            $links[] = [
                'title' => AmosDocumenti::t('amosdocumenti', 'Visualizza i documenti da validare'),
                'label' => AmosDocumenti::t('amosdocumenti', 'Da validare'),
                'url' => '/documenti/documenti/to-validate-documents'
            ];
        }

        if (\Yii::$app->user->can(\open20\amos\news\widgets\icons\WidgetIconAdminAllNews::class)) {
            $links[] = [
                'title' => AmosDocumenti::t('amosdocumenti', 'Amministra tutte i documenti'),
                'label' => AmosDocumenti::t('amosdocumenti', 'Amministra'),
                'url' => '/documenti/documenti/admin-all-documents'
            ];
        }
        if ($module->enableCategories && \Yii::$app->user->can(WidgetIconDocumentiCategorie::class)) {
            $links[] = [
                'title' => AmosDocumenti::t('amosdocumenti', 'Visualizza le categorie dei documenti'),
                'label' => AmosDocumenti::t('amosdocumenti', 'Categorie'),
                'url' => '/documenti/documenti-categorie/index',
            ];
        }
        return $links;
    }


    /**
     * @param $id
     * @param $redirect
     * @return Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionPublishFolder($id, $redirect = null)
    {
        $this->model = $this->findModel($id);
        if ($this->model->is_folder) {
            $post = \Yii::$app->request->post();
            if ($post) {
                if ($post['selection']) {
                    $documenti = Documenti::find()
                        ->andWhere(['id' => $post['selection']])->all();
                    $i = 0;
                    foreach ($documenti as $document) {
                        if ($document->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA) {
                            $document->status = Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE;
                            $document->save(false);

                            $document->status = Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO;
                            if ($document->save(false)) {
                                $i++;
                            }
                        } else if ($document->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA) {
                            $document->status = Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO;
                            if ($document->save(false)) {
                                $i++;
                            }
                        }
                    }
                    \Yii::$app->session->addFlash('success', AmosDocumenti::t('amosdocumenti', "La cartella e {n} documenti al suo interno sono stati pubblicati"), [
                        'n' => $i
                    ]);
                }
            }
        }
        if ($redirect) {
            return $this->redirect($redirect);
        } else {
            return $this->redirect(['/documenti/documenti/index']);
        }
    }

    /**
     * @param $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionModalPublishFolderAjax($id = null, $page = null)
    {
        if (!empty($id)) {
            $this->model = $this->findModel($id);
        } else {
            $this->model = new Documenti();
        }
        return $this->renderPartial('_modal_publish_folder_grid', ['model' => $this->model, 'page' => $page]);
    }

    /**
     * @param $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionModalUnpublishFolderAjax($id = null)
    {
        if (!empty($id)) {
            $this->model = $this->findModel($id);
        } else {
            $this->model = new Documenti();
        }
        return $this->renderPartial('_modal_publish_folder_unpublish', ['model' => $this->model]);
    }

    /**
     * If the user comes from the update of a document, get parent id from the document if exists.
     * Method useful to auto expand folders in gridview when user comes from update of a document.
     * @return array
     */
    public function getParentsFromPreviousDocument()
    {
        $previousDocumentParentIds = [];
        if (Url::previous() && strpos(Url::previous(), 'documenti/documenti/update') !== false) {
            $previousUrlParsed = parse_url(Url::previous(), PHP_URL_QUERY);
            parse_str($previousUrlParsed, $previousUrlParams);
            if (isset($previousUrlParams['id'])) {
                $document = Documenti::findOne($previousUrlParams['id']);
                if ($document && !empty($document->parent_id)) {
                    $allParents = $document->getAllParents();
                    foreach ($allParents as $parent) {
                        $previousDocumentParentIds[] = $parent->id;
                    }
                }
            }
        }
        return $previousDocumentParentIds;
    }
}
