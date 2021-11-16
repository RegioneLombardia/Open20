<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

namespace open20\amos\discussioni\controllers;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\BreadcrumbHelper;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\discussioni\assets\ModuleDiscussioniInterfacciaAsset;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\discussioni\models\search\DiscussioniTopicSearch;
use raoul2000\workflow\base\WorkflowException;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use open20\amos\core\widget\WidgetAbstract;

/**
 * Class DiscussioniTopicController
 * DiscussioniTopicController implements the CRUD actions for DiscussioniTopic model.
 * @package open20\amos\discussioni\controllers
 */
class DiscussioniTopicController extends CrudController {

    use TabDashboardControllerTrait;

    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * @var AmosDiscussioni|null $discussioniModule
     */
    public $discussioniModule = null;

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviors = ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [                               
                                'index',
                                'all-discussions',
                                'own-interest-discussions'
                            ],
                            'roles' => ['LETTORE_DISCUSSIONI', 'AMMINISTRATORE_DISCUSSIONI', 'CREATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI',
                                'VALIDATORE_DISCUSSIONI']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'partecipa',
                            ],
                            'roles' => ['DISCUSSIONITOPIC_READ']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'created-by',
                            ],
                            'roles' => ['CREATORE_DISCUSSIONI', 'AMMINISTRATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'validate-discussion',
                                'reject-discussion',
                            ],
                            'roles' => ['AMMINISTRATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI', 'FACILITATOR', 'DiscussionValidateOnDomain',
                                'VALIDATORE_DISCUSSIONI']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'to-validate-discussions'
                            ],
                            'roles' => ['VALIDATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI', 'AMMINISTRATORE_DISCUSSIONI',
                                'DiscussionValidateOnDomain']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'admin-all-discussions'
                            ],
                            'roles' => ['AMMINISTRATORE_DISCUSSIONI']
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

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function init() {
        $this->initDashboardTrait();

        $this->setModelObj(new DiscussioniTopic());
        $this->setModelSearch(new DiscussioniTopicSearch());

        ModuleDiscussioniInterfacciaAsset::register(Yii::$app->view);

        $this->discussioniModule = Yii::$app->getModule(AmosDiscussioni::getModuleName());

        $this->viewList = [
            'name' => 'list',
            'label' => AmosIcons::show('view-list') . Html::tag('p', AmosDiscussioni::t('amosdiscussioni', 'List')),
            'url' => '?currentView=list'
        ];

//        $this->viewIcon = [
//            'name' => 'icon',
//            'label' => AmosIcons::show('view-grid') . Html::tag('p', AmosDiscussioni::t('amosdiscussioni', 'Icon')),
//            'url' => '?currentView=icon'
//        ];

        $this->viewGrid = [
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosDiscussioni::t('amosdiscussioni', 'Table')),
            'url' => '?currentView=grid'
        ];

        $defaultViews = [
            'list' => $this->viewList,
//            'icon' => $this->viewIcon,
            'grid' => $this->viewGrid,
        ];
        
        $availableViews = [];
        foreach ($this->discussioniModule->defaultListViews as $view) {
            if (isset($defaultViews[$view])) {
                $availableViews[$view] = $defaultViews[$view];
            }
        }

        $this->setAvailableViews($availableViews);

        parent::init();
        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->view->pluginIcon = 'ic ic-disc';
        }

        $this->setUpLayout();
    }

    /**
     * @param string|null $layout
     * @return string|\yii\web\Response
     */
    public function actionIndex($layout = null) {
        return $this->redirect(['/discussioni/discussioni-topic/all-discussions']);

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        //se il layout di default non dovesse andar bene si può aggiuntere il layout desiderato
        //in questo modo nel controller "return parent::actionIndex($this->layout);"
        if ($layout) {
            $this->setUpLayout($layout);
        }

        Url::remember();

        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));
        
        $this->setListViewsParams();
        $this->setTitleAndBreadcrumbs(AmosDiscussioni::t('amosdiscussioni', 'Tutte le discussioni'));

        return $this->render('index',
                [
                    'dataProvider' => $this->getDataProvider(),
                    'model' => $this->getModelSearch(),
                    'currentView' => $this->getCurrentView(),
                    'availableViews' => $this->getAvailableViews(),
        ]);
    }

    /**
     * @param int $id Discussion id.
     * @return \yii\web\Response
     */
    public function actionValidateDiscussion($id) {
        $discussione = DiscussioniTopic::findOne($id);
        try {
            $discussione->sendToStatus(DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA);
            $ok = $discussione->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', AmosDiscussioni::t('amosdiscussioni', 'Discussion validated!'));
            } else {
                Yii::$app->session->addFlash('danger',
                    AmosDiscussioni::t('amosdiscussioni', '#ERROR_WHILE_VALIDATING_DISCUSSION'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            
//            return $this->redirect(Url::previous());
        }
        
        return $this->redirect(Url::previous());
    }

    /**
     * @param int $id Discussion id.
     * @return \yii\web\Response
     */
    public function actionRejectDiscussion($id) {
        $discussione = DiscussioniTopic::findOne($id);
        try {
            $discussione->sendToStatus(DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_BOZZA);
            $ok = $discussione->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', AmosDiscussioni::t('amosdiscussioni', 'Discussion rejected!'));
            } else {
                Yii::$app->session->addFlash('danger',
                    AmosDiscussioni::t('amosdiscussioni', '#ERROR_WHILE_REJECTING_DISCUSSION'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
//
//                        return $this->redirect(Url::previous());
        }
        
        return $this->redirect(Url::previous());
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    private function setCreateNewBtnLabel() {
        $urlCreateNew = array_key_exists("noWizardNewLayout", Yii::$app->params) 
            ? '/discussioni/discussioni-topic/create' 
            : '/discussioni/discussioni-topic-wizard/introduction';

        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => AmosDiscussioni::t('amosdiscussioni', 'Add new discussion'),
            'urlCreateNew' => $urlCreateNew
        ];
    }

    /**
     * This method is useful to set all common params for all list views.
     */
    protected function setListViewsParams() {
        $this->setCreateNewBtnLabel();
        Yii::$app->session->set(AmosDiscussioni::beginCreateNewSessionKey(), Url::previous());
    }

    /**
     * Used for set page title and breadcrumbs.
     * @param string $disussionePageTitle Discussions page title (ie. Created by discussions, ...)
     */
    private function setTitleAndBreadcrumbs($disussionePageTitle) {
        $this->setNetworkDashboardBreadcrumb();
        Yii::$app->session->set('previousTitle', $disussionePageTitle);
        Yii::$app->session->set('previousUrl', Url::previous());
        Yii::$app->view->title = $disussionePageTitle;
        Yii::$app->view->params['breadcrumbs'][] = ['label' => $disussionePageTitle];
    }

    /**
     * 
     */
    public function setNetworkDashboardBreadcrumb() {
        /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
        $moduleCwh = Yii::$app->getModule('cwh');
        $scope = null;
        if (!empty($moduleCwh)) {
            $scope = $moduleCwh->getCwhScope();
        }
        
        if (!empty($scope)) {
            if (isset($scope['community'])) {
                $communityId = $scope['community'];
                $community = \open20\amos\community\models\Community::findOne($communityId);
                $dashboardCommunityTitle = AmosDiscussioni::t('amosdiscussioni', "Dashboard") . ' ' . $community->name;
                $dasbboardCommunityUrl = Yii::$app->urlManager->createUrl(['community/join', 'id' => $communityId]);
                Yii::$app->view->params['breadcrumbs'][] = ['label' => $dashboardCommunityTitle, 'url' => $dasbboardCommunityUrl];
            }
        }
    }

    /**
     * 
     * @return type
     */
    public function actionCreatedBy() {
        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        Url::remember();

        $this->setDataProvider($this->getModelSearch()->searchCreatedBy(Yii::$app->request->getQueryParams()));

        $this->setListViewsParams();
        $this->setTitleAndBreadcrumbs(AmosDiscussioni::t('amosdiscussioni', 'Discussioni create da me'));

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosDiscussioni::t('amosdiscussioni',
                    '{iconaTabella}' . Html::tag('p', AmosDiscussioni::t('amosdiscussioni', 'Table')),
                    [
                        'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ]
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));

        return parent::actionIndex();
    }

    /**
     * 
     * @return type
     */
    public function actionAllDiscussions() {
        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        Url::remember();

        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));

        $this->setListViewsParams();
        $this->setTitleAndBreadcrumbs(AmosDiscussioni::t('amosdiscussioni', 'Tutte le discussioni'));

        return parent::actionIndex();
    }

    /**
     * @return string
     */
    public function actionAdminAllDiscussions() {
        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        Url::remember();

        $this->setDataProvider($this->getModelSearch()->searchAdminAll(Yii::$app->request->getQueryParams()));

        $this->setListViewsParams();
        $this->setTitleAndBreadcrumbs(AmosDiscussioni::t('amosdiscussioni', 'Amministra discussioni'));

        return parent::actionIndex();
    }

    /**
     * @return string
     */
    public function actionToValidateDiscussions() {
        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        Url::remember();

        $this->setDataProvider($this->getModelSearch()->searchToValidate(Yii::$app->request->getQueryParams()));

        $this->setListViewsParams();
        $this->setTitleAndBreadcrumbs(AmosDiscussioni::t('amosdiscussioni', 'Discussioni da validare'));

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosDiscussioni::t('amosdiscussioni',
                    '{iconaTabella}' . Html::tag('p', AmosDiscussioni::t('amosdiscussioni', 'Table')),
                    [
                        'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ]
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));

        return parent::actionIndex();
    }

    /**
     * 
     * @return type
     */
    public function actionOwnInterestDiscussions() {
//        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        Url::remember();

        $this->setDataProvider(
            $this->getModelSearch()
            ->searchOwnInterest(Yii::$app->request->getQueryParams())
        );

        $this->setListViewsParams();
        $this->setTitleAndBreadcrumbs(AmosDiscussioni::t('amosdiscussioni', 'Discussioni di mio interesse'));

        return parent::actionIndex();
    }

    /**
     * Displays a single DiscussioniTopic model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        /** @var DiscussioniTopic $model */
        $model = $this->findModel($id);

        ///////////// restituisce in view la lista dei TAG Se uno vuole.........
        $tagModule = \Yii::$app->getModule('tag');
        if (isset($tagModule) && in_array(DiscussioniTopic::className(), $tagModule->modelsEnabled)) {
            $tagInteressiTutti = \open20\amos\tag\models\Tag::find()
                ->where('root IN (1)')
                ->addOrderBy('root, lft')
                ->asArray()
                ->all();

            $tagInteressi = [];
            if(!empty($model->tagValues1)) {
                $tagIdInteressi = explode(",", $model->tagValues1);
                foreach ($tagInteressiTutti as $value) {
                    if (in_array($value['id'], $tagIdInteressi)) {
                        $tagInteressi[] = $value;
                    }
                }
            }

            $tagAmbitoLavTutti = \open20\amos\tag\models\Tag::find()
                ->where('root IN (10)')
                ->addOrderBy('root, lft')
                ->asArray()
                ->all();
            
            $tagAmbitoLav = [];
            if(!empty($model->tagValues2)) {
                $tagIdAmbitoLav = explode(",", $model->tagValues2);
                foreach ($tagAmbitoLavTutti as $value) {
                    if (in_array($value['id'], $tagIdAmbitoLav)) {
                        $tagAmbitoLav[] = $value;
                    }
                }
            }
        }
        ////////// Fine...

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'interessi' => $tagInteressi, 'ambitilav' => $tagAmbitoLav]);
        }
        
        return $this->render(
            'view', 
            ['model' => $model]
        );
    }

    /**
     * Creates a new DiscussioniTopic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * 
     * $this->model is setup on init() function with $this->setModelObj(new DiscussioniTopic());
     * so it's un-usefull to recreate one!!!
     * 
     * @return mixed
     */
    public function actionCreate() {
        $this->setUpLayout('form');
        
        if ($this->model->load(Yii::$app->request->post())) {
            if ($this->model->validate()) {
                $validateOnSave = true;
                if ($this->model->status == DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_DAVALIDARE) {
                    $this->model->status = DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_BOZZA;
                    $this->model->save();
                    $this->model->status = DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_DAVALIDARE;
                    $validateOnSave = false;
                }
                
                if ($this->model->status == DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA) {
                    $this->model->status = DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_BOZZA;
                    $this->model->save();
                    $this->model->status = DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA;
                    $validateOnSave = false;
                }
                
                if ($this->model->save($validateOnSave)) {
                    Yii::$app->getSession()->addFlash(
                        'success',
                        AmosDiscussioni::t('amosdiscussioni', 'Discussione salvata con successo.')
                    );
                    
                    return $this->redirectOnCreate($this->model);
                } else {
                    Yii::$app->getSession()->addFlash(
                        'danger',
                        AmosDiscussioni::t('amosdiscussioni', 'Si &egrave; verificato un errore durante il salvataggio')
                    );
                }
            } else {
                Yii::$app->getSession()->addFlash(
                    'danger',
                    AmosDiscussioni::t('amosdiscussioni', 'Modifiche non salvate. Verifica l\'inserimento dei campi')
                );
            }
        }

        return $this->render(
            'create',
            [
                'model' => $this->model,
            ]
        );
    }

//    /**
//     * Creates a new DiscussioniTopic model.
//     * If creation is successful, the browser will be redirected to the 'view' page.
//     * @return mixed
//     */
//    public function actionCreate() {
//        $this->setUpLayout('form');
//        
//        $model = new DiscussioniTopic();
//        $this->model = $model;
//
//        if ($model->load(Yii::$app->request->post())) {
//            if ($model->validate()) {
//                $validateOnSave = true;
//                if ($model->status == DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_DAVALIDARE) {
//                    $model->status = DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_BOZZA;
//                    $model->save();
//                    $model->status = DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_DAVALIDARE;
//                    $validateOnSave = false;
//                }
//                if ($model->status == DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA) {
//                    $model->status = DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_BOZZA;
//                    $model->save();
//                    $model->status = DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA;
//                    $validateOnSave = false;
//                }
//                if ($model->save($validateOnSave)) {
//                    Yii::$app->getSession()->addFlash('success',
//                        AmosDiscussioni::t('amosdiscussioni', 'Discussione salvata con successo.'));
//                    return $this->redirectOnCreate($model);
//                } else {
//                    Yii::$app->getSession()->addFlash('danger',
//                        AmosDiscussioni::t('amosdiscussioni', 'Si &egrave; verificato un errore durante il salvataggio'));
//                }
//            } else {
//                Yii::$app->getSession()->addFlash('danger',
//                    AmosDiscussioni::t('amosdiscussioni', 'Modifiche non salvate. Verifica l\'inserimento dei campi'));
//            }
//        }
//
//        return $this->render('create', [
//                'model' => $model,
//        ]);
//    }

    /**
     * Updates an existing DiscussioniTopic model.
     *
     * @param integer $id
     * @param bool|false $backToEditStatus Save the model with status Editing in progress before form rendering
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id, $backToEditStatus = false) {
        $this->setUpLayout('form');

        /** @var DiscussioniTopic $model */
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $previousStatus = $model->status;
            $model->load(Yii::$app->request->post());

            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->getSession()->addFlash('success',
                        AmosDiscussioni::t('amosdiscussioni', 'Modifiche salvate con successo.'));
                    return $this->redirectOnUpdate($model, $previousStatus);
                } else {
                    Yii::$app->getSession()->addFlash('danger',
                        AmosDiscussioni::t('amosdiscussioni', 'Si &egrave; verificato un errore durante il salvataggio'));
                }
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    AmosDiscussioni::t('amosdiscussioni', 'Modifiche non salvate. Verifica l\'inserimento dei campi'));
            }
        } else {
            if ($backToEditStatus && ($model->status != $model->getDraftStatus() && !Yii::$app->user->can('DiscussionValidate',
                    ['model' => $model]))) {
                $model->status = $model->getDraftStatus();
                $ok = $model->save(false);
                if (!$ok) {
                    Yii::$app->getSession()->addFlash('danger',
                        AmosDiscussioni::t('amosdiscussioni', 'Si &egrave; verificato un errore durante il salvataggio'));
                }
            }
        }

        return $this->render(
            'update', 
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Deletes an existing DiscussioniTopic model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->addFlash(
            'success',
            AmosDiscussioni::t('amosdiscussioni', 'Discussione cancellata correttamente.')
        );
        
        return $this->redirect(Url::previous());
    }

    /**
     * @param int $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionPartecipa($id) {
        $this->setUpLayout('main');
        Url::remember();

        /** @var DiscussioniTopic $model */
        $model = $this->findModel($id);
        $model->detachBehavior('seoBehavior');
        $model->detachBehavior('SeoContentBehavior');
        $model->detachBehavior('TimestampBehavior');
        $model->detachBehavior('BlameableBehavior');
        $model->detachBehavior('NotifyBehavior');
        $model->hints++;
        $model->save(false);

        // TODO: possibile bug perché la find non ha parametri quindi qui sta cercando tutto.
        $listaAllegati = $model->getdiscussionsAttachments();
        //        $listaAllegati = DiscussioniAllegati::find()->andWhere(['discussioni_topic_id' => $id])->asArray()->all();  // TODO: correzione alla riga sopra ma tutto da testare. Per ora commentato.

        $ultimaRisposta = $model->getDiscussionComments()->orderBy('id DESC')->one();

        $query = $model->getDiscussionComments()->orderBy('id DESC');
        $countQuery = clone $query;

        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pages->setPageSize(5);
        $discussioniRisposte = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('partecipa', [
            'model' => $model,
            'discussioniRisposte' => $discussioniRisposte,
            'pages' => $pages,
            'ultimaRisposta' => $ultimaRisposta,
            'listaAllegati' => $listaAllegati,
        ]);
    }

    /**
     * @param $model
     * @param null $previousStatus
     * @return \yii\web\Response
     */
    protected function redirectOnUpdate($model, $previousStatus = null) {
        // if you have the permission of update or you can validate the content you will be redirected on the update page
        // otherwise you will be redirected on the index page
        $redirectToUpdatePage = false;
        
        if (Yii::$app->getUser()->can('DISCUSSIONITOPIC_UPDATE', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }
        
        if (Yii::$app->getUser()->can('DiscussionValidate', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }
        
        if ($redirectToUpdatePage) {
            Yii::$app->getSession()->addFlash(
                'success',
                AmosDiscussioni::t('amosdiscussioni', 'Discussione aggiornata con successo.')
            );
            
            if ($model->status == DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA) {
                return $this->redirect(BreadcrumbHelper::lastCrumbUrl());
            } elseif (($model->status == DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_BOZZA) && ($previousStatus == DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_DAVALIDARE)) {
                return $this->redirect(BreadcrumbHelper::lastCrumbUrl());
            } else {
                return $this->redirect(['/discussioni/discussioni-topic/update', 'id' => $model->id]);
            }
        }
        
        return $this->redirect(['/discussioni/discussioni-topic/own-interest-discussions']);
    }

    /**
     * if you have the permission of update or you can validate the content 
     * you will be redirected on the update page otherwise you will be redirected 
     * on the index page with the contents created by you
     * 
     * @param $model
     * @return \yii\web\Response
     */
    protected function redirectOnCreate($model) {
        $redirectToUpdatePage = false;

        if (Yii::$app->getUser()->can('DISCUSSIONITOPIC_UPDATE', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }

        if (Yii::$app->getUser()->can('DiscussionValidate', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }

        if ($redirectToUpdatePage) {
            return $this->redirect(['/discussioni/discussioni-topic/update', 'id' => $model->id]);
        } else {
            return $this->redirect('/discussioni/discussioni-topic/created-by');
        }
    }

    /**
     * @param $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionPublic($id) {
        $model = $this->findModel($id);
        $this->layout = 'form';
        if ($this->isContentShared($id)) {
            return $this->render('public', ['model' => $model]);
        }
    }

}
