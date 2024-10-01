<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\controllers
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\Risposte;
use open20\amos\sondaggi\models\search\SondaggiSearch;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiDomande;
use open20\amos\sondaggi\models\SondaggiDomandeCondizionate;
use open20\amos\sondaggi\models\SondaggiDomandePagine;
use open20\amos\sondaggi\models\SondaggiRisposte;
use open20\amos\sondaggi\models\SondaggiRispostePredefinite;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;
use open20\amos\sondaggi\utility\SondaggiUtility;
use open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral;
use open20\amos\upload\models\FilemanagerMediafile;
use kartik\mpdf\Pdf;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;
use yii\helpers\BaseInflector;

/**
 * Class SondaggiController
 * SondaggiController implements the CRUD actions for Sondaggi model.
 *
 * @property \open20\amos\sondaggi\models\Sondaggi $model
 * @property \open20\amos\sondaggi\models\search\SondaggiSearch $modelSearch
 *
 * @package open20\amos\sondaggi\controllers
 */
class SondaggiController extends CrudController
{

    use TabDashboardControllerTrait;
    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * @var \open20\amos\cwh\AmosCwh $moduleCwh
     */
    public $moduleCwh;

    /**
     * @var array $scope
     */
    public $scope;

    /**
     * @var AmosEvents $sondaggiModule
     */
    public $sondaggiModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();
        $this->sondaggiModule = AmosSondaggi::instance();

        $this->setModelObj($this->sondaggiModule->createModel('Sondaggi'));
        $this->setModelSearch($this->sondaggiModule->createModel('SondaggiSearch'));

        $this->scope     = null;
        $this->moduleCwh = Yii::$app->getModule('cwh');

        if (!empty($this->moduleCwh)) {
            $this->scope = $this->moduleCwh->getCwhScope();
        }

        $this->setAvailableViews([
            'list' => [
                'name' => 'list',
                'label' => AmosSondaggi::t('amossondaggi', '{iconaLista}'.Html::tag('p', 'Lista'),
                    [
                    'iconaLista' => AmosIcons::show('view-list')
                ]),
                'url' => '?currentView=list'
            ],
            'grid' => [
                'name' => 'grid',
                'label' => AmosSondaggi::t('amossondaggi',
                    '{tableIcon}'.Html::tag('p', AmosSondaggi::t('amossondaggi', 'Table')),
                    [
                    'tableIcon' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ]
        ]);

        parent::init();

        if ($this->sondaggiModule->enableDashboard) {
            \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
            \Yii::$app->view->params['showSidebarForm']        = true;
        }

        $this->setUpLayout('form');
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(),
                [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'risultati-live',
                            ],
                            'roles' => ['@']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'risultati',
                                'extract-sondaggi',
                            ],
                            'roles' => ['AMMINISTRAZIONE_SONDAGGI', 'SONDAGGI_READ']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'clone',                                
                            ],
                            'roles' => ['AMMINISTRAZIONE_SONDAGGI', 'SONDAGGI_UPDATE']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'manage',
                                'created-by-me'
                            ],
                            'roles' => ['SONDAGGI_MANAGE']
                        ]
                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'risultati' => ['post', 'get']
                    ]
                ]
        ]);
        return $behaviors;
    }

    /**
     * This method is useful to set all common params for all list views.
     * @param bool $setCurrentDashboard
     * @param bool $hideCreateNewBtn
     */
    protected function setListViewsParams($setCurrentDashboard = true, $hideCreateNewBtn = false)
    {
        $this->setCreateNewBtnParams($hideCreateNewBtn);
        $this->setUpLayout('list');

        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        $this->child_of                         = WidgetIconSondaggiGeneral::className();

        Yii::$app->session->set(AmosSondaggi::beginCreateNewSessionKey(), Url::previous());
        Yii::$app->session->set(AmosSondaggi::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     * @param bool $hideBtn
     */
    protected function setCreateNewBtnParams($hideBtn = false)
    {
        if ($this->sondaggiModule->enableDashboard == false) {
            Yii::$app->view->params['createNewBtnParams'] = [
                'createNewBtnLabel' => AmosSondaggi::t('amossondaggi', 'Add new survey'),
            ];
        }
        if ($hideBtn) {
            Yii::$app->view->params['createNewBtnParams']['layout'] = '';
        }
    }

    /**
     * Used for set page title and breadcrumbs.
     * @param string $pageTitle News page title (ie. Created by news, ...)
     */
    public function setTitleAndBreadcrumbs($pageTitle)
    {
        Yii::$app->view->title                 = $pageTitle;
        // Yii::$app->view->params['breadcrumbs'] = [
        //     ['label' => $pageTitle]
        // ];
    }

    /**
     * Lists all Sondaggi models.
     * @param string|null $layout
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($layout = null)
    {
        if ($this->sondaggiModule->enableDashboard == true) {
            return $this->redirect(['pubblicazione/all']);
        }
        Url::remember();
        $this->setListViewsParams();
        $this->setTitleAndBreadcrumbs(AmosSondaggi::t('amossondaggi', 'Amministra i sondaggi'));
        //        $this->setAvailableViews([
        //            'grid' => [
        //                'name' => 'grid',
        //                'label' => AmosIcons::show('view-list-alt').Html::tag('p',
        //                    AmosSondaggi::tHtml('amossondaggi', 'Tabella')),
        //                'url' => '?currentView=grid'
        //            ],
        //        ]);
        //        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams(), 'admin-scope', null));
        return parent::actionIndex($layout);
    }

    public function actionManage()
    {
        $this->setUpLayout('list');
        $this->view->params['titleSection'] = AmosSondaggi::t('amossondaggi', 'Gestisci sondaggi');
        if (!$this->sondaggiModule->hideAllPolls) {

            $this->view->params['labelLinkAll'] = AmosSondaggi::t('amossondaggi', 'Tutti i sondaggi');
            $this->view->params['urlLinkAll']   = AmosSondaggi::t('amossondaggi', '/sondaggi/pubblicazione/all');
            $this->view->params['titleLinkAll'] = AmosSondaggi::t(
                    'amossondaggi', 'Visualizza la lista di tutti i sondaggi'
            );
        } else {
             $this->view->params['urlLinkAll'] = '';
        }
        $this->modelSearch->status = null;
        $this->setDataProvider($this->modelSearch->searchAllAdminExtended(Yii::$app->request->getQueryParams()));
        //\Yii::$app->getView()->params['bi-menu-sidebar'] = SondaggiUtility::getSidebarEventsUser();

        $this->view->params['dataConfirmCreate'] = AmosSondaggi::instance()->sondaggioDataConfirmMessage;

        return $this->render('index-questionari',
                [
                'model' => $this->modelSearch,
                'dataProvider' => $this->getDataProvider(),
                'currentView' => $this->getCurrentView(),
                'title' => $this->view->params['titleSection'],
        ]);
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreatedByMe()
    {
        $this->setUpLayout('list');
        $this->view->params['titleSection'] = AmosSondaggi::t('amossondaggi', 'Sondaggi creati da me');
        if (!$this->sondaggiModule->hideAllPolls) {

            $this->view->params['labelLinkAll'] = AmosSondaggi::t('amossondaggi', 'Tutti i sondaggi');
            $this->view->params['urlLinkAll']   = AmosSondaggi::t('amossondaggi', '/sondaggi/pubblicazione/all');
            $this->view->params['titleLinkAll'] = AmosSondaggi::t('amossondaggi', 'Visualizza la lista di tutti i sondaggi');
        } else {
            $this->view->params['urlLinkAll'] = '';
        }
        $this->view->params['dataConfirmCreate'] = AmosSondaggi::instance()->sondaggioDataConfirmMessage;

        $this->modelSearch->status = null;
        $this->setDataProvider($this->modelSearch->searchCreatedByMe(Yii::$app->request->getQueryParams()));

        return $this->render('index-questionari',
            [
                'model' => $this->modelSearch,
                'dataProvider' => $this->getDataProvider(),
                'currentView' => $this->getCurrentView(),
                'title' => $title
            ]);
    }

    /**
     * Displays a single Sondaggi model.
     * @param integer $id
     * @return string|\yii\web\Response
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            return $this->render('view', ['model' => $this->model]);
        }
    }

    /**
     * @param integer $id
     * @param int $idPagina
     * @return string
     */
    public function actionRisultati($id, $idPagina = -2, $url = 'index')
    {
        $risposte = new Risposte();
        if (!empty($_GET['filter']) && (!empty($_GET['filter']['data_inizio']) || !empty($_GET['filter']['data_fine']) || !empty($_GET['filter']['area_formativa'])
            || !empty($_GET['filter']['attivita']))) {
            $rispLoad['Risposte'] = $_GET['filter'];
            $risposte->load($rispLoad);
        }
        $data           = [];
        $this->model    = Sondaggi::findOne(['id' => $id]);
        /* $pagine = $this->model->getSondaggiDomandePagines()->orderBy('ordinamento, id ASC'); */
        $pagineQuery    = $this->model->getSondaggiDomandePagines()
            ->joinWith('sondaggiDomandes')
            ->andWhere(['IN', 'sondaggi_domande.sondaggi_domande_tipologie_id', [1, 2, 3, 4]])
            ->groupBy('sondaggi_domande_pagine.id')
            ->orderBy('sondaggi_domande_pagine.id ASC');
        /* ->innerJoin('sondaggi_domande', 'sondaggi_domande_pagine.id = sondaggi_domande.sondaggi_domande_pagine_id')
          ->andWhere(['IN', 'sondaggi_domande.sondaggi_domande_tipologie_id', [1,2,3,4]])
          ->orderBy('sondaggi_domande_pagine.id ASC'); */
        $pagine         = $pagineQuery->all();
        $primaPagina    = $pagine[0]['id'];
        $ultimaPagina   = $pagine[count($pagine) - 1]['id'];
        $prossimaPagina = null;
        $arrayPag       = [];
        foreach ($pagine as $Pag) {
            $arrayPag[] = $Pag['id'];
        }

        if (count($this->model)) {
            if ($idPagina > -2) {

                if (\Yii::$app->request->isPost) {
                    $data = \Yii::$app->request->post();
                    $risposte->load($data);
                }

                if ($idPagina == 0) {
                    $prossimaPagina   = 0;
                    $paginaPrecedente = $ultimaPagina;
                } else if ($idPagina == -1) {
                    $prossimaPagina   = $primaPagina;
                    $paginaPrecedente = -1;
                } else {
                    $IndicePag        = array_search($idPagina, $arrayPag);
                    $prossimaPagina   = (count($arrayPag) > 1) ? (($idPagina == $ultimaPagina) ? 0 : $arrayPag[$IndicePag
                        + 1]) : 0;
                    $paginaPrecedente = (count($arrayPag) > 1) ? (($idPagina == $primaPagina) ? -1 : $arrayPag[$IndicePag
                        - 1]) : -1;
                }

                return $this->render('risultati',
                        [
                        'model' => $this->model,
                        'primaPagina' => $primaPagina,
                        'prossimaPagina' => $prossimaPagina,
                        'paginaPrecedente' => $paginaPrecedente,
                        'ultimaPagina' => $ultimaPagina,
                        'idPagina' => $idPagina,
                        'risposte' => $risposte->getDati($id, $idPagina),
                        //  'report' => $risposte->getReport($id, $idPagina),
                        'domande' => $risposte->getDomandeStatistiche($id, $idPagina),
                        'criteri' => $risposte->getDomandeStatistiche($id, $idPagina, true),
                        'tipo' => $risposte->getTipologia($id),
                        'filter' => $risposte,
                        'url' => $url
                ]);
            } else {

                if (\Yii::$app->request->isPost) {
                    $data = \Yii::$app->request->post();
                    $risposte->load($data);
                }

                $idPagina         = -1;
                $prossimaPagina   = $primaPagina;
                $paginaPrecedente = -1;

                return $this->render('risultati',
                        [
                        'model' => $this->model,
                        'primaPagina' => $primaPagina,
                        'prossimaPagina' => $prossimaPagina,
                        'paginaPrecedente' => $paginaPrecedente,
                        'ultimaPagina' => $ultimaPagina,
                        'idPagina' => $idPagina,
                        'risposte' => $risposte->getDati($id, $idPagina),
                        // 'report' => $risposte->getReport($id, $idPagina),
                        'domande' => $risposte->getDomandeStatistiche($id, $idPagina),
                        'criteri' => $risposte->getDomandeStatistiche($id, $idPagina, true),
                        'tipo' => $risposte->getTipologia($id),
                        'filter' => $risposte,
                        'url' => $url
                ]);
            }
        } else {
            return $this->render($url);
        }
    }

    /**
     * @param integer $id
     * @param int $idPagina
     * @return string
     */
    public function actionRisultatiLive($id, $idPagina)
    {
        $url      = 'index';
        $risposte = new Risposte();

        $data           = [];
        $this->model    = Sondaggi::findOne(['id' => $id]);
        /* $pagine = $this->model->getSondaggiDomandePagines()->orderBy('ordinamento, id ASC'); */
        $pagineQuery    = $this->model->getSondaggiDomandePagines()
            ->joinWith('sondaggiDomandes')
            ->andWhere(['IN', 'sondaggi_domande.sondaggi_domande_tipologie_id', [1, 2, 3, 4]])
            ->groupBy('sondaggi_domande_pagine.id')
            ->orderBy('sondaggi_domande_pagine.id ASC');
        /* ->innerJoin('sondaggi_domande', 'sondaggi_domande_pagine.id = sondaggi_domande.sondaggi_domande_pagine_id')
          ->andWhere(['IN', 'sondaggi_domande.sondaggi_domande_tipologie_id', [1,2,3,4]])
          ->orderBy('sondaggi_domande_pagine.id ASC'); */
        $pagine         = $pagineQuery->all();
        $primaPagina    = $pagine[0]['id'];
        $ultimaPagina   = $pagine[count($pagine) - 1]['id'];
        $prossimaPagina = null;
        $arrayPag       = [];
        foreach ($pagine as $Pag) {
            $arrayPag[] = $Pag['id'];
        }

        if (count($this->model)) {
            if ($idPagina > -2) {

                if (\Yii::$app->request->isPost) {
                    $data = \Yii::$app->request->post();
                    $risposte->load($data);
                }

                return $this->renderAjax('risultati_live',
                        [
                        'model' => $this->model,
                        'idPagina' => $idPagina,
                        'risposte' => $risposte->getDati($id, $idPagina),
                        //  'report' => $risposte->getReport($id, $idPagina),
                        'domande' => $risposte->getDomandeStatistiche($id, $idPagina),
                        'tipo' => $risposte->getTipologia($id),
                        'filter' => $risposte,
                ]);
            }
        }
    }

    /**
     * Creates a new Sondaggi model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @param string|null $url
     * @return string
     */
    public function actionCreate($url = null)
    {
        if ($this->sondaggiModule->enableDashboard == true) {
            return $this->redirect(['/sondaggi/dashboard/create']);
        }
        $this->setUpLayout('form');

        $this->model = new Sondaggi();
        $pagine      = new SondaggiDomandePagine();
        $domanda     = new SondaggiDomande();
        $risposta    = new SondaggiRispostePredefinite();
        $post        = \Yii::$app->request->post();

        $urlSondaggi = [['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['index']]];
        $navP        = isset($post['pagina']) ? 1 : 0;
        $navD        = isset($post['domanda']) ? 1 : 0;

        $IsImported = false;
        if (!empty(\Yii::$app->request->post('SondaggiRispostePredefinite')['sondaggi_domande_id'])) {
            $domandaId  = \Yii::$app->request->post('SondaggiRispostePredefinite')['sondaggi_domande_id'];
            $IsImported = SondaggiRispostePredefinite::import($domandaId);
        }

        if ($url) {
            if ($this->model->load($post)) {
                if ($this->model->validate()) {
                    $avatar_id = null;
                    $modelFile = new FilemanagerMediafile();
                    $modelFile->load($post);
                    $file      = UploadedFile::getInstance($modelFile, 'file');
                    if ($file) {
                        $routes = Yii::$app->getModule('upload')->routes;
                        $modelFile->saveUploadedFile($routes, true);
                        if ($modelFile->id) {
                            $avatar_id = $modelFile->id;
                        }
                    }
                    $this->model->filemanager_mediafile_id = $avatar_id;
                    $validateOnSave                        = true;
                    if ($this->model->status == Sondaggi::WORKFLOW_STATUS_DAVALIDARE) {
                        $this->model->status = Sondaggi::WORKFLOW_STATUS_BOZZA;
                        $this->model->save();
                        $this->model->status = Sondaggi::WORKFLOW_STATUS_DAVALIDARE;
                        $validateOnSave      = false;
                    }
                    if ($this->model->status == Sondaggi::WORKFLOW_STATUS_VALIDATO) {
                        $this->model->status = Sondaggi::WORKFLOW_STATUS_BOZZA;
                        $this->model->save();
                        $this->model->status = Sondaggi::WORKFLOW_STATUS_VALIDATO;
                        $validateOnSave      = false;
                    }
                    if ($this->model->save($validateOnSave)) {
                        $this->model->getOtherAttributes(Yii::$app->request->post());
                        $pagine->sondaggi_id = $this->model->id;
                        Yii::$app->getSession()->addFlash('success',
                            AmosSondaggi::tHtml('amossondaggi', "Sondaggio creato correttamente."));
                        return $this->redirect(['index']);
                    } else {
                        Yii::$app->getSession()->addFlash('danger',
                            AmosSondaggi::tHtml('amossondaggi', 'Sondaggio non creato. Verifica i dati inseriti.'));
                    }

                    return $this->render('create',
                            [
                            'model' => $this->model,
                            'moduleCwh' => $this->moduleCwh,
                            'scope' => $this->scope,
                            'url' => $url,
                            'public' => "false"
                    ]);
                }
            } else {
                return $this->render('create',
                        [
                        'model' => $this->model,
                        'moduleCwh' => $this->moduleCwh,
                        'scope' => $this->scope,
                        'url' => $url
                ]);
            }
        } else {
            if ($this->model->load($post)) {
                if ($this->model->validate()) {
                    $avatar_id = null;
                    $modelFile = new FilemanagerMediafile();
                    $modelFile->load($post);
                    $file      = UploadedFile::getInstance($modelFile, 'file');
                    if ($file) {
                        $routes = Yii::$app->getModule('upload')->routes;
                        $modelFile->saveUploadedFile($routes, true);
                        if ($modelFile->id) {
                            $avatar_id = $modelFile->id;
                        }
                    }
                    $this->model->filemanager_mediafile_id = $avatar_id;
                    $validateOnSave                        = true;
                    if ($this->model->status == Sondaggi::WORKFLOW_STATUS_DAVALIDARE) {
                        $this->model->status = Sondaggi::WORKFLOW_STATUS_BOZZA;
                        $this->model->save();
                        $this->model->status = Sondaggi::WORKFLOW_STATUS_DAVALIDARE;
                        $validateOnSave      = false;
                    }
                    if ($this->model->status == Sondaggi::WORKFLOW_STATUS_VALIDATO) {
                        $this->model->status = Sondaggi::WORKFLOW_STATUS_BOZZA;
                        $this->model->save();
                        $this->model->status = Sondaggi::WORKFLOW_STATUS_VALIDATO;
                        $validateOnSave      = false;
                    }
                    if ($this->model->save($validateOnSave)) {
                        $pagine->sondaggi_id = $this->model->id;
                        Yii::$app->getSession()->addFlash('success',
                            AmosSondaggi::tHtml('amossondaggi', "Sondaggio creato correttamente."));
                        return $this->render('/sondaggi-domande-pagine/create',
                                [
                                'model' => $pagine,
                        ]);
                    } else {
                        Yii::$app->getSession()->addFlash('danger',
                            AmosSondaggi::tHtml('amossondaggi', 'Sondaggio non creato. Verifica i dati inseriti.'));
                        return $this->render('create',
                                [
                                'model' => $this->model,
                                'public' => "false"
                        ]);
                    }
                }
            } else if ($pagine->load($post)) {
//inizio upload immagine
                $avatar_id = null;
                $modelFile = new FilemanagerMediafile();
                $modelFile->load($post);

                $file = UploadedFile::getInstance($modelFile, 'file');
                if ($file) {
                    $routes = Yii::$app->getModule('upload')->routes;
                    $modelFile->saveUploadedFile($routes, true);
                    if ($modelFile->id) {
                        $avatar_id                        = $modelFile->id;
                        $pagine->filemanager_mediafile_id = $avatar_id;
                    }
                }
//fine upload immagine
                $pagine->save();
                $domanda->sondaggi_id                = $pagine->sondaggi_id;
                $domanda->sondaggi_domande_pagine_id = $pagine->id;
//Yii::$app->view->params['breadcrumbs'] = $urlSondaggi;
                return $this->render('/sondaggi-domande/create',
                        [
                        'model' => $domanda
                ]);
            } else if ($domanda->load($post)) {
                $ordinamento = $post['SondaggiDomande']['ordine'];
                $ordinaDopo  = 0;
                if (strlen($ordinamento) == 0) {
                    $ordinamento = 'fine';
                }
                if ($ordinamento == 'dopo') {
                    $ordinaDopo = $post['SondaggiDomande']['ordina_dopo'];
                }
                $tipoDomanda = $domanda->sondaggiDomandeTipologie->id;
                if ($domanda->domanda_condizionata == 1 && !empty($domanda->condizione_necessaria)) {
                    $domanda->domanda_condizionata = 0;
                }
                $domanda->save();
                if ($domanda->domanda_condizionata && !empty($domanda->condizione_necessaria)) {
                    foreach ($domanda->condizione_necessaria as $cond) {
                        $domandaCondizioneMm                                   = new SondaggiDomandeCondizionate();
                        $domandaCondizioneMm->sondaggi_risposte_predefinite_id = $cond;
                        $domandaCondizioneMm->sondaggi_domande_id              = $domanda->id;
                        $domandaCondizioneMm->save();
                    }
                }
                $domanda->setOrdinamento($ordinamento, $ordinaDopo,
                    (!empty($domanda->condizione_necessaria)) ? $domanda->condizione_necessaria : 0);
//Yii::$app->view->params['breadcrumbs'] = $urlSondaggi;
                if ($tipoDomanda == 1 || $tipoDomanda == 2 || $tipoDomanda == 3 || $tipoDomanda == 4) {
                    $risposta->sondaggi_domande_id = $domanda->id;
                    $risposta->tipo_domanda        = $tipoDomanda;
                    return $this->render('/sondaggi-risposte-predefinite/create',
                            [
                            'model' => $risposta,
                    ]);
                } else if ($tipoDomanda == 5 || $tipoDomanda == 6 || $tipoDomanda == 10 || $tipoDomanda = 11) {
                    if ($navP) {
                        $newPagina              = new SondaggiDomandePagine();
                        $newPagina->sondaggi_id = $domanda->sondaggi_id;
                        return $this->render('/sondaggi-domande-pagine/create',
                                [
                                'model' => $newPagina,
                        ]);
                    } else {
                        $newDomanda                             = new SondaggiDomande();
                        $newDomanda->sondaggi_id                = $domanda->sondaggi_id;
                        $newDomanda->sondaggi_domande_pagine_id = $domanda->sondaggi_domande_pagine_id;
                        return $this->render('/sondaggi-domande/create',
                                [
                                'model' => $newDomanda,
                        ]);
                    }
                }
            } else if ($risposta->load($post)) {
                $tipoDomanda = $risposta->tipo_domanda;
                if (!$IsImported) {
                    $ordinamento = $post['SondaggiRispostePredefinite']['ordine'];
                    $ordinaDopo  = 0;
                    if (strlen($ordinamento) == 0) {
                        $ordinamento = 'fine';
                    }
                    if ($ordinamento == 'dopo') {
                        $ordinaDopo = $post['SondaggiRispostePredefinite']['ordina_dopo'];
                    }
                    $risposta->save();
                    $risposta->setOrdinamento($ordinamento, $ordinaDopo);
                }
//Yii::$app->view->params['breadcrumbs'] = $urlSondaggi;
                if ($navP) {
                    $newPagina              = new SondaggiDomandePagine();
                    $newPagina->sondaggi_id = $risposta->getSondaggiDomande()->one()['sondaggi_id'];
                    return $this->render('/sondaggi-domande-pagine/create',
                            [
                            'model' => $newPagina,
                    ]);
                } else if ($navD) {
                    $newDomanda                             = new SondaggiDomande();
                    $newDomanda->sondaggi_id                = $risposta->getSondaggiDomande()->one()['sondaggi_id'];
                    $newDomanda->sondaggi_domande_pagine_id = $risposta->getSondaggiDomande()->one()['sondaggi_domande_pagine_id'];
                    return $this->render('/sondaggi-domande/create',
                            [
                            'model' => $newDomanda,
                            'idSondaggio' => $risposta->getSondaggiDomande()->one()['sondaggi_id']
                    ]);
                } else {
                    $newRisposta                      = new SondaggiRispostePredefinite();
                    $newRisposta->sondaggi_domande_id = $risposta->sondaggi_domande_id;
                    $newRisposta->tipo_domanda        = $tipoDomanda;
                    return $this->render('/sondaggi-risposte-predefinite/create',
                            [
                            'model' => $newRisposta,
                    ]);
                }
            } else {
                return $this->render('create',
                        [
                        'model' => $this->model,
                        'moduleCwh' => $this->moduleCwh,
                        'scope' => $this->scope,
                        'url' => null
                ]);
            }
        }
        return $this->render('create',
                [
                'model' => $this->model,
                'moduleCwh' => $this->moduleCwh,
                'scope' => $this->scope,
                'url' => null
        ]);
    }

    /**
     * Updates an existing Sondaggi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        return $this->redirect(['/sondaggi/dashboard/dashboard', 'id' => $id]);

        //$this->redirect(['dashboard', 'id'=>$id])
        $this->setUpLayout('form');

        $this->model = $this->findModel($id);
        $this->model->getOtherAttributes();

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->getOtherAttributes(Yii::$app->request->post());
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success',
                    AmosSondaggi::tHtml('amossondaggi', "Sondaggio aggiornato correttamente."));
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    AmosSondaggi::tHtml('amossondaggi', 'Sondaggio non aggiornato. Verifica i dati inseriti.'));
            }
        }

        return $this->render('update',
                [
                'model' => $this->model,
                'moduleCwh' => $this->moduleCwh,
                'scope' => $this->scope,
        ]);
    }

    /**
     * Deletes an existing Sondaggi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return \yii\web\Response
     * @throws \open20\amos\core\exceptions\AmosException
     */
    public function actionDelete($id)
    {
        $retMessage = SondaggiUtility::deleteSondaggio($id);

        if ($retMessage != 'ok') {
            Yii::$app->getSession()->addFlash('danger', $retMessage);

            return $this->redirect(Yii::$app->request->referrer ?: 'manage');
        } else {
            Yii::$app->getSession()->addFlash('success',
                AmosSondaggi::tHtml('amossondaggi', "Sondaggio cancellato correttamente."));
        }

        return $this->redirect('manage');

        /*
        $this->model = $this->findModel($id);

        if ($this->model->status != Sondaggi::WORKFLOW_STATUS_BOZZA) {
            Yii::$app->getSession()->addFlash('danger',
                AmosSondaggi::tHtml('amossondaggi',
                    "Impossibile cancellare il sondaggio in quanto non è in stato BOZZA."));
        } else {
            $pages = $this->model->sondaggiDomandePagines;
            if ($this->sondaggiModule->pollCascadeDeletion) {
                foreach ($pages as $page) {
                    $page->delete();
                }
                $questions = $this->model->sondaggiDomandes;
                foreach ($questions as $question) {
                    $defaultAnswers = $question->sondaggiRispostePredefinites;
                    foreach ($defaultAnswers as $answer) {
                        $answer->delete();
                    }
                    $question->delete();
                }
                $this->model->delete();
                Yii::$app->getSession()->addFlash('success',
                    AmosSondaggi::tHtml('amossondaggi', "Sondaggio cancellato correttamente."));
            } else {
                if ($pages) {
                    Yii::$app->getSession()->addFlash('danger',
                        AmosSondaggi::tHtml('amossondaggi', "Impossibile cancellare il sondaggio per la presenza di pagine."));
                } else {
                    $this->model->delete();
                    Yii::$app->getSession()->addFlash('success',
                        AmosSondaggi::tHtml('amossondaggi', "Sondaggio cancellato correttamente."));
                }
            }
        }
        return $this->redirect('manage');
        */
    }


    /**
     *
     * @param type $id
     * @param type $type
     * @param type $url
     * @return type
     * @throws \Exception
     */
    public function actionExtractSondaggi($id, $type, $url)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $this->model = $this->findModel($id);
        $xlsData     = [];
        $basePath    = \Yii::getAlias('@vendor/../common/uploads/temp');
        $zipAttachs  = [];

        // se abilita_registrazione == 1 allora i tre campi vanno visualizzati, se no, vale il parametro statisticExtractDisableNameSurnameEmail
        $viewNameSurnameEmail = $this->model->abilita_registrazione || !AmosSondaggi::instance()->statisticExtractDisableNameSurnameEmail;

        $isCommunityManager = false;
        if (!empty(\Yii::$app->getModule('community'))) {
            $isCommunityManager = \open20\amos\community\utilities\CommunityUtil::isLoggedCommunityManager();
        }

        if ($this->sondaggiModule->enableExportBeforeClosing == false && ($this->model->status != Sondaggi::WORKFLOW_STATUS_VALIDATO || $this->model->close_date >= date('Y-m-d'))) {
            if (!AmosSondaggi::instance()->forceDownloadResults) {
                Yii::$app->getSession()->addFlash('danger', AmosSondaggi::tHtml('amossondaggi', '#cannot_download_not_closed'));
                return $this->redirect($url);
            }
        }

        $intestazioneStart = [];
        $offset            = -3;
        if ($viewNameSurnameEmail) {
            $intestazioneStart = ["Nome", "Cognome", "Email"];
            $offset            = 0;
        }

        // INTESTAZIONE EXCEL
        $xlsData[0] = array_merge($intestazioneStart, ["Iniziato il", "Completato il"]);
        if (AmosSondaggi::instance()->compilationToOrganization) {
            $xlsData[0] = array_merge($intestazioneStart, ["Ente", "Iniziato il", "Completato il"]);
        }
        $domande = [];
        $pagine  = $this->model->getSondaggiDomandePagines()->orderBy('sondaggi_domande_pagine.ordinamento');
        foreach ($pagine->all() as $pagina) {
            $domandePagina = $pagina->getSondaggiDomandes()->andWhere(['parent_id' => null])->orderBy('ordinamento ASC')->all();
            foreach ($domandePagina as $domandaPag) {
                $domande[] = $domandaPag;
            }
        }
        //$domande         = $model->getSondaggiDomandes()->orderBy('ordinamento ASC')->all();
        $count    = 1;
        $totCount = 5;
        if (AmosSondaggi::instance()->compilationToOrganization) {
            $totCount = 6;
        }
        if (AmosSondaggi::instance()->enableCompilationWorkflow) {
            $xlsData[0][] = "Stato";
            $totCount++;
        }
        $colRisp         = [];
        $colRispLibere   = [];
        $colRispAllegati = [];
        foreach ($domande as $domanda) {
            $rispostePredefinite = $domanda->getSondaggiRispostePredefinites();
            $countRisposte       = $rispostePredefinite->count();
            $localCount          = 1;
            if (in_array($domanda->sondaggi_domande_tipologie_id, [10, 11])) {
                $xlsData[0][]                  = "D.".$count." ".$domanda->domanda;
                $colRispAllegati[$domanda->id] = $totCount;
                $totCount++;
            } else if (in_array($domanda->sondaggi_domande_tipologie_id, [5, 6, 13])) {
                $xlsData[0][]                = "D.".$count." ".$domanda->domanda;
                $colRispLibere[$domanda->id] = $totCount;
                $totCount++;
            } else {
                if (!empty($countRisposte) && in_array($domanda->sondaggi_domande_tipologie_id, [1, 2, 3, 4])) {
                    if ($domanda->is_parent) {
                        $childs = $domanda->childs;
                        foreach ($childs as $ch) {
                            foreach ($rispostePredefinite->orderBy('ordinamento ASC')->all() as $rispPre) {
                                $xlsData[0][]                   = "D.".$count." ".$domanda->domanda." \n".$ch->domanda." \nR.".$localCount." ".$rispPre->risposta;
                                $colRisp[$rispPre->id][$ch->id] = $totCount;
                                $localCount++;
                                $totCount++;
                            }
                        }
                    } else {
                        foreach ($rispostePredefinite->orderBy('ordinamento ASC')->all() as $rispPre) {
                            $xlsData[0][]          = "D.".$count." ".$domanda->domanda." \nR.".$localCount." ".$rispPre->risposta;
                            $colRisp[$rispPre->id] = $totCount;
                            $localCount++;
                            $totCount++;
                        }
                    }
                }
            }
            $count ++;
        }




// CORPO FILE EXCEL
        $sondaggiRisposte = SondaggiRisposteSessioni::find()
            ->distinct()
            ->innerJoin('sondaggi_risposte',
                'sondaggi_risposte_sessioni.id = sondaggi_risposte.sondaggi_risposte_sessioni_id')
            ->leftJoin('user_profile', 'user_profile.user_id = sondaggi_risposte_sessioni.user_id')
            ->leftJoin('user', 'user_profile.user_id = user.id')
            ->andWhere(['sondaggi_risposte_sessioni.sondaggi_id' => $id])
            ->orderBy('sondaggi_risposte_sessioni.begin_date')
            ->all();

        $row = 1;

        $srpArray = SondaggiRispostePredefinite::find()->asArray()->all();
        $sondRispPredefAll = [];
        foreach ($srpArray as $element) {
            $sondRispPredefAll[$element['id']] = $element;
        }

//        VarDumper::dump(count($sondaggiRisposte), 3, true); echo('<hr>');
//        $cont = 0;
//        $mt = microtime(true);
//        VarDumper::dump($mt, 3, true); echo('<hr>');

        foreach ($sondaggiRisposte as $sondRisposta) {
            $profile = null;
            if (!empty($sondRisposta->user_id)) {
                /** @var AmosAdmin $adminModule */
                $adminModule = AmosAdmin::instance();
                /** @var UserProfile $userProfileModel */
                $userProfileModel = $adminModule->createModel('UserProfile');
                $profile = $userProfileModel::find()->andWhere(['user_id' => $sondRisposta->user_id])->one();
            }
            if (empty($profile)) {
                if ($viewNameSurnameEmail) {
                    $xlsData [$row][0 + $offset] = ($this->model->abilita_criteri_valutazione == 1 ? AmosSondaggi::t('amossondaggi',
                            'L\'utente non ha effettuato la registrazione') : AmosSondaggi::t('amossondaggi',
                            'L\'utente non è stato registrato'));
                    $xlsData [$row][1 + $offset] = ($this->model->abilita_criteri_valutazione == 1 ? AmosSondaggi::t('amossondaggi',
                            'L\'utente non ha effettuato la registrazione') : AmosSondaggi::t('amossondaggi',
                            'L\'utente non è stato registrato'));
                    $xlsData [$row][2 + $offset] = ($this->model->abilita_criteri_valutazione == 1 ? AmosSondaggi::t('amossondaggi',
                            'L\'utente non ha effettuato la registrazione') : AmosSondaggi::t('amossondaggi',
                            'L\'utente non è stato registrato'));
                }
            } else {
                if ($viewNameSurnameEmail) {
                    $xlsData [$row][0 + $offset] = $profile->nome;
                    $xlsData [$row][1 + $offset] = $profile->cognome;
                    $xlsData [$row][2 + $offset] = $profile->user->email;
                }
            }
            $dateDiff = (new \DateTime())->diff(new \DateTime($sondRisposta->updated_at));
            if (($dateDiff->invert * $dateDiff->days) > 730 && AmosSondaggi::instance()->resetGdpr) {
                $xlsData [$row][0 + $offset] = "#####";
                $xlsData [$row][1 + $offset] = "#####";
                $xlsData [$row][2 + $offset] = "#####";
            }

            if (AmosSondaggi::instance()->compilationToOrganization) {
                $profilo                     = \open20\amos\organizzazioni\models\Profilo::find()->andWhere(['id' => $sondRisposta->organization_id])->one();
                $xlsData [$row][3 + $offset] = !empty($profilo->name) ? $profilo->name : '';
                $xlsData [$row][4 + $offset] = Yii::$app->formatter->asDatetime($sondRisposta->begin_date, 'php:d/m/Y H:i:s');
                $xlsData [$row][5 + $offset] = Yii::$app->formatter->asDatetime($sondRisposta->end_date, 'php:d/m/Y H:i:s');
                if (AmosSondaggi::instance()->enableCompilationWorkflow) {
                    $xlsData[$row][6 + $offset] = AmosSondaggi::t('amossondaggi', $sondRisposta->status);
                }
            } else {
                $xlsData [$row][3 + $offset] = Yii::$app->formatter->asDatetime($sondRisposta->begin_date, 'php:d/m/Y H:i:s');
                $xlsData [$row][4 + $offset] = Yii::$app->formatter->asDatetime($sondRisposta->end_date, 'php:d/m/Y H:i:s');
                if (AmosSondaggi::instance()->enableCompilationWorkflow) {
                    $xlsData[$row][5 + $offset] = AmosSondaggi::t('amossondaggi', $sondRisposta->status);
                }
            }
            $session_id = $sondRisposta->id;

            /** @var  $domanda SondaggiDomande */
            foreach ($domande as $domanda) {

                $query = $domanda->getRispostePerUtente((empty($profile) ? null : $profile->user_id), $session_id);
// RISPOSTE LIBERE
                if ($domanda->sondaggi_domande_tipologie_id == 6 || $domanda->sondaggi_domande_tipologie_id == 5 || $domanda->sondaggi_domande_tipologie_id == 13) {

                    $risposta = $query->asArray()->one();
                    if ($risposta) {
                        if ($domanda->sondaggi_domande_tipologie_id == 13) {
                            $risposta['risposta_libera'] = Yii::$app->formatter->asDate($risposta['risposta_libera'], 'php:d/m/Y');
                        }
                        $xlsData[$row][$colRispLibere[$domanda->id] + $offset] = $risposta['risposta_libera'];
                    } else {

                    }
//ALLEGATI
                } else if ($domanda->sondaggi_domande_tipologie_id == 10 || $domanda->sondaggi_domande_tipologie_id == 11) {
                    $risposta = $query->one();
                    if ($risposta) {
                        $attribute = 'domanda_'.$domanda->id;
                        if (!empty($risposta->$attribute)) {
                            $attachments    = $risposta->getFiles();
                            $listAttachUrls = [];
                            foreach ($attachments as $attach) {
                                $folder = BaseInflector::slug($profile->cognome.' '.$profile->nome);
                                if (AmosSondaggi::instance()->compilationToOrganization) {
                                    $profilo = \open20\amos\organizzazioni\models\Profilo::find()->andWhere(['id' => $sondRisposta->organization_id])->one();
                                    $folder  = BaseInflector::slug($profilo->name);
                                }
                                if (AmosSondaggi::instance()->xlsAsZip)
                                        $listAttachUrls []                                       = $folder.'/'.$attach->name.'.'.$attach->type;
                                else
                                        $listAttachUrls []                                       = \Yii::$app->params['platform']['backendUrl'].$attach->getUrl();
                                $zipAttachs[$folder.'/'.$attach->name.'.'.$attach->type] = $attach->path;
                            }
                            $xlsData[$row][$colRispAllegati[$domanda->id] + $offset] = implode(" \n", $listAttachUrls);
                        }
                    } else {

                    }
                } else {
                    $risposteArray = [];
                    /** @var SondaggiRisposte $risposta */
                    foreach ($query->asArray()->all() as $risposta) {
                        $srp = $sondRispPredefAll[$risposta['sondaggi_risposte_predefinite_id']];

                        if (!empty($srp)) {
                            if ($domanda->is_parent) {
                                if (empty($srp['code'])) {

                                    $xlsData[$row][$colRisp[$srp['id']][$risposta['sondaggi_domande_id']]
                                        + $offset] = $srp['risposta'];
                                } else {
                                    $xlsData[$row][$colRisp[$srp['id']][$risposta['sondaggi_domande_id']]
                                        + $offset] = $srp['code'];
                                }
                            } else {
                                if (empty($srp['code'])) {
                                    $xlsData[$row][$colRisp[$srp['id']] + $offset] = $srp['risposta'];
                                } else {
                                    $xlsData[$row][$colRisp[$srp['id']] + $offset] = $srp['code'];
                                }
                            }
                        }
                    }
                }
            }
            $row++;
            gc_collect_cycles();

//            if ($cont >= 1000) {
//
//                $mtNew = microtime(true);
//                VarDumper::dump($mtNew, 3, true); echo('<hr>');
//                VarDumper::dump(($mtNew - $mt), 3, true); echo('<hr>');
//                die('stoop');
//            }
//            $cont++;

        }

        $zip_filepath = $basePath.'/Risposte_sondaggio_'.$id.'.zip';
        if ($type == 'xls') {
            //inizializza l'oggetto excel
            $nomeFile    = $basePath.'/Risposte_sondaggio_'.$id.'.xls';
            $objPHPExcel = new \PHPExcel();

            // set Style first row
            $lastColumn       = $totCount + $offset;
            $lastColumnLetter = \PHPExcel_Cell::stringFromColumnIndex($lastColumn);

            $objPHPExcel->getActiveSheet()->getStyle('A1:'.$lastColumnLetter.'1')->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()->setRGB('C0C0C0');

            for ($i = 1; $i <= $row; $i++) {
                for ($c = 0; $c <= $lastColumn; $c++) {
                    if (empty($xlsData[$i]) || !array_key_exists($c, $xlsData[$i])) {
                        $xlsData[$i][$c] = '';
                    }
                }
            }

            foreach ($xlsData as $key => $value) {
                ksort($xlsData[$key]);
            }
            //li pone nella tab attuale del file xls
            $objPHPExcel->getActiveSheet()->fromArray($xlsData, NULL, 'A1');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save($nomeFile);
            if (!AmosSondaggi::instance()->xlsAsZip) {
                \Yii::$app->response->sendFile($nomeFile);
                return unlink($nomeFile);
            } else {
                /** @var \ZipArchive $zip */
                $zip = new \ZipArchive();
                if ($zip->open($zip_filepath, \ZipArchive::CREATE) !== TRUE) {
                    throw new \Exception('Cannot create a zip file');
                }
                $zip->addFile($nomeFile, 'Risposte_sondaggio.xls');
                foreach ($zipAttachs as $name => $file) {
                    $zip->addFile($file, $name);
                }

                $zip->close();
                \Yii::$app->response->sendFile($zip_filepath);
                unlink($zip_filepath);
                unlink($nomeFile);
                return;
            }
        }
        if ($type == 'pdf') {
            mkdir($basePath.'/attachments/'.$id.'/', 0775, true);
            /** @var \ZipArchive $zip */
            $zip = new \ZipArchive();

            if ($zip->open($zip_filepath, \ZipArchive::CREATE) !== true) {
                throw new \Exception('Cannot create a zip file');
            }

            foreach ($this->model->sondaggiRisposteSessionis as $sessione) {
                if (!empty($profile) && !AmosSondaggi::instance()->forceOnlyFrontend) {
                    $folder = BaseInflector::slug($profile->cognome.' '.$profile->nome);
                } else {
                    $folder = BaseInflector::slug($sessione->id.' '.$sessione->begin_date.' sondaggio');
                }


                if (AmosSondaggi::instance()->compilationToOrganization) {
                    $folder = BaseInflector::slug($sessione->organization->name);
                }
                $pdfPath = $basePath.'/attachments/'.$id.'/'.$folder.'.pdf';
                $sessione->generateSondaggiPdf($pdfPath);
                if (file_exists($pdfPath)) {
                    $zip->addFile($pdfPath, basename($pdfPath));
                }
            }

            foreach ($zipAttachs as $name => $file) {
                $zip->addFile($file, $name);
            }

            $zip->close();
            \Yii::$app->response->sendFile($zip_filepath);
            unlink($zip_filepath);
            return;
        }
    }

    public function actionClone($id)
    {
        /** @var  $model Sondaggi */
        $model      = $this->findModel($id);
        $created_by = \Yii::$app->user->id;


        $data['Sondaggi']                     = $model->attributes;
        $sondaggio                            = new Sondaggi();
        $sondaggio->load($data);
        $sondaggio->status                    = Sondaggi::WORKFLOW_STATUS_BOZZA;
        $sondaggio->titolo                    = $sondaggio.' (clone)';
        $sondaggio->validatori                = $model->validatori;
        $sondaggio->regola_pubblicazione      = $model->regola_pubblicazione;
        $sondaggio->destinatari               = $model->destinatari;
        $sondaggio->created_by                = $created_by;
        $sondaggio->updated_by                = $created_by;
        $ok                                   = $sondaggio->save();



        $pagine = $model->sondaggiDomandePagines;
        //PAGINE
        foreach ($pagine as $pagina) {
            $newDomCond                    = [];
            $data                          = [];
            $newPagina                     = new SondaggiDomandePagine();
            $data['SondaggiDomandePagine'] = $pagina->attributes;
            $newPagina->load($data);
            $newPagina->sondaggi_id        = $sondaggio->id;
            $newPagina->created_by         = $created_by;
            $newPagina->updated_by         = $created_by;
            $newPagina->save();

            //DOMANDE
            $domande = $pagina->sondaggiDomandes;
            foreach ($domande as $domanda) {
                $data                                   = [];
                $newDomanda                             = new SondaggiDomande();
                $data['SondaggiDomande']                = $domanda->attributes;
                $newDomanda->load($data);
                $newDomanda->id                         = null;
                $newDomanda->sondaggi_id                = $sondaggio->id;
                $newDomanda->domanda_condizionata       = $domanda->domanda_condizionata;
                $newDomanda->sondaggi_domande_pagine_id = $newPagina->id;
                $newDomanda->created_by                 = $created_by;
                $newDomanda->updated_by                 = $created_by;
                $okDom                                  = $newDomanda->save();

                if ($okDom) {
                    if ($domanda->domanda_condizionata == 1) {
                        $rispCond                    = $domanda->sondaggiRispostePredefinitesCondizionate;
                        $newDomCond[$newDomanda->id] = ['ordinamento' => $rispCond->ordinamento, 'risposta' => $rispCond->risposta,
                            'pagina' => $newPagina->id];
                    }
                }

                // RISPOSTE PREDEFINITE
                $risposte = $domanda->sondaggiRispostePredefinites;
                if (!empty($risposte)) {
                    foreach ($risposte as $risposta) {
                        $data                                = [];
                        $newRisposta                         = new SondaggiRispostePredefinite();
                        $data['SondaggiRispostePredefinite'] = $risposta->attributes;
                        $newRisposta->load($data);
                        $newRisposta->id                     = null;
                        $newRisposta->sondaggi_domande_id    = $newDomanda->id;
                        $newRisposta->created_by             = $created_by;
                        $newRisposta->updated_by             = $created_by;
                        $newRisposta->save();
                    }
                }
            }

            // DOMANDE CONDIZIONATE
            foreach ($newDomCond as $d => $r) {
                $allDomande = SondaggiDomande::find()->andWhere(['sondaggi_domande_pagine_id' => $r['pagina']])->select('id');

                $risp = SondaggiRispostePredefinite::find()
                    ->andWhere(['sondaggi_domande_id' => $allDomande])
                    ->andWhere(['risposta' => $r['risposta']])
                    ->andWhere(['ordinamento' => $r['ordinamento']])
                    ->one();

                if (!empty($risp)) {
                    $newDomandaCondiz                                   = new SondaggiDomandeCondizionate();
                    $newDomandaCondiz->sondaggi_domande_id              = $d;
                    $newDomandaCondiz->sondaggi_risposte_predefinite_id = $risp->id;
                    $newDomandaCondiz->created_by                       = $created_by;
                    $newDomandaCondiz->updated_by                       = $created_by;
                    $newDomandaCondiz->save();
                }
            }
        }
        if ($ok) {
            \Yii::$app->session->addFlash('success', AmosSondaggi::t('amossondaggi', 'Sondaggio duplicato con successo'));
            return $this->redirect(['update', 'id' => $sondaggio->id]);
        } else {
            \Yii::$app->session->addFlash('danger',
                AmosSondaggi::t('amossondaggi', 'Errore durante la duplicazione del sondaggio'));
            return $this->redirect(['index', 'id' => $id]);
        }
    }

    /**
     * @param $idDomanda
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function import($idDomanda)
    {
        $submitImport = Yii::$app->request->post('submit-import');
        $count        = 0;
        if (!empty($submitImport)) {
            if ((isset($_FILES['import-file']['tmp_name']) && (!empty($_FILES['import-file']['tmp_name'])))) {
                $inputFileName = $_FILES['import-file']['tmp_name'];
                $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
                $objReader     = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel   = $objReader->load($inputFileName);

                $sheet         = $objPHPExcel->getSheet(0);
                $highestRow    = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $ret['file']   = true;
                $i             = 1;
                for ($row = 2; $row <= $highestRow; $row++) {
                    $rowData                   = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row, NULL, TRUE, FALSE);
                    $Array                     = $rowData[0];
                    $rispostaPredefinitaName   = $Array[0];
                    if (!empty($rispostaPredefinitaName)) {
                        $rispostaPredefinita                      = new SondaggiRispostePredefinite();
                        $rispostaPredefinita->sondaggi_domande_id = $idDomanda;
                        $rispostaPredefinita->risposta            = $rispostaPredefinitaName;
                        $rispostaPredefinita->code                = null;
                        $rispostaPredefinita->ordinamento         = $i;
                        $ok                                       = $rispostaPredefinita->save();
                        if ($ok) {
                            $count ++;
                            $i++;
                        }
                    }
                }
                \Yii::$app->session->addFlash('success',
                    AmosSondaggi::t('amossondaggi', "Sono state inserite {n} risposte.", ['n' => $count]));
            }
        }
    }

    public function actionDownloadImportFileExample()
    {
        $path = Yii::getAlias('@vendor').'/open20/amos-sondaggi/src/downloads';
        $file = $path.'/Risposte_predefinite.xls';
        if (file_exists($file)) {
            Yii::$app->response->sendFile($file);
        }
    }

    public function beforeAction($action)
    {

        $titleSection = AmosSondaggi::t('amossondaggi', 'Gestisci');
        $labelLinkAll = AmosSondaggi::t('amossondaggi', 'Tutti i sondaggi');
        $urlLinkAll   = '/sondaggi/pubblicazione/all';
        $titleLinkAll = AmosSondaggi::t('amossondaggi', 'Visualizza la lista di tutti i sondaggi');

        $subTitleSection = Html::tag('p', AmosSondaggi::t('amossondaggi', ''));


        $labelCreate = AmosSondaggi::t('amossondaggi', 'Nuovo');
        $titleCreate = AmosSondaggi::t('amossondaggi', 'Crea un nuovo sondaggio');
        $labelManage = AmosSondaggi::t('amossondaggi', 'Gestisci');
        $titleManage = AmosSondaggi::t('amossondaggi', 'Gestisci i sondaggi');
        $urlCreate   = '/sondaggi/dashboard/create';
        $urlManage   = AmosSondaggi::t('amossondaggi', '#');

        $hideCreate = false;
        if (!\Yii::$app->user->can('SONDAGGI_CREATE')) {
            $hideCreate = true;
        }

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'sondaggi',
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
            'hideCreate' => $hideCreate,
            'urlManage' => $urlManage,
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }

    /**
     *
     * @return array
     */
    public static function getManageLinks()
    {
        return PubblicazioneController::getManageLinks();
    }
}
