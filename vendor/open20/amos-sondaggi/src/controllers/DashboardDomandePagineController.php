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

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\base\SondaggiTypes;
use open20\amos\sondaggi\models\search\SondaggiDomandePagineSearch;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiDomandePagine;
use open20\amos\sondaggi\utility\SondaggiUtility;
use open20\amos\upload\models\FilemanagerMediafile;
use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Class SondaggiDomandePagineController
 * SondaggiDomandePagineController implements the CRUD actions for SondaggiDomandePagine model.
 *
 * @property \open20\amos\sondaggi\models\SondaggiDomandePagine $model
 * @property \open20\amos\sondaggi\models\search\SondaggiDomandePagineSearch $modelSearch
 *
 * @package open20\amos\sondaggi\controllers
 */
class DashboardDomandePagineController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'form';

    public $sondaggiModule = null;

    public function behaviors()
    {
        return ArrayHelper::merge([],
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'index',
                                'create',
                                'view',
                                'update',
                                'delete',
                                'order'
                            ],
                            'roles' => ['AMMINISTRAZIONE_SONDAGGI']
                        ]
                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get']
                    ]
                ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sondaggiModule = AmosSondaggi::instance();

        $this->setModelObj(new SondaggiDomandePagine());
        $this->setModelSearch(new SondaggiDomandePagineSearch());

//        EventsAsset::register(\Yii::$app->view);

//         $this->scope     = null;
//         $this->moduleCwh = \Yii::$app->getModule('cwh');
//
//         if (!empty($this->moduleCwh)) {
//             $this->scope = $this->moduleCwh->getCwhScope();
//         }

        $this->setAvailableViews([
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

        //    \Yii::$app->params['bsVersion']                    = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        \Yii::$app->view->params['showSidebarForm']        = true;
        $this->setUpLayout('form');

    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    protected function setCreateNewBtnParams()
    {
        $get = Yii::$app->request->get();
        $buttonLabel = AmosSondaggi::t('amossondaggi', 'Aggiungi pagina');
        $sondaggio = Sondaggi::findOne(filter_input(INPUT_GET, 'idSondaggio'));

        $canCreate = true;
        if ($sondaggio) {
            if ($sondaggio->sondaggio_type == SondaggiTypes::SONDAGGI_TYPE_LIVE) {
                if ($sondaggio->hasAlreadyPage()) {
                    $canCreate = false;
                }

            }
        }

        $urlCreateNew = ['create'];
        if (isset($get['idSondaggio'])) {
            $urlCreateNew['idSondaggio'] = filter_input(INPUT_GET, 'idSondaggio');
        }
        if (isset($get['idPagina'])) {
            $urlCreateNew['idPagina'] = filter_input(INPUT_GET, 'idPagina');
        }
        if (isset($get['url'])) {
            $urlCreateNew['url'] = $get['url'];
        }
        if($canCreate) {
            Yii::$app->view->params['createNewBtnParams'] = [
                'urlCreateNew' => $urlCreateNew,
                'createNewBtnLabel' => $buttonLabel
            ];
        }
        if (!empty($get['idSondaggio'])) {
            $backButton = Html::a(AmosIcons::show('long-arrow-return', ['class' => 'm-r-5']) . AmosSondaggi::t('amossondaggi', "Torna ai sondaggi"),
                ['/sondaggi/sondaggi/manage'], [
                    'class' => 'btn btn-secondary',
                    'title' => AmosSondaggi::t('amossondaggi', "Torna ai sondaggi")
                ]);
            Yii::$app->view->params['additionalButtons'] = [
                'htmlButtons' => [$backButton]
            ];
        }
    }

    /**
     * This method is useful to set all common params for all list views.
     */
    protected function setListViewsParams()
    {
        $this->setCreateNewBtnParams();
        $this->setUpLayout('form');
        Yii::$app->session->set(AmosSondaggi::beginCreateNewSessionKey(), Url::previous());
        Yii::$app->session->set(AmosSondaggi::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
    }

    /**
     * Lists all SondaggiDomandePagine models.
     * @return mixed
     */
    public function actionIndex($idSondaggio, $url = null)
    {
        Url::remember();
        $this->setUrl($url);
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        $this->setListViewsParams();
//        return parent::actionIndex($layout); // TODO sistemare questo punto cambiando totalmente la action in quanto non compatibile con gli standard di PHP 7
        \Yii::$app->getView()->params['showSidebarForm'] = true;
        $this->setMenuSidebar(Sondaggi::findOne($idSondaggio));
        return parent::actionIndex('form');
    }

    /**
     * Displays a single SondaggiDomandePagine model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);
        $this->setMenuSidebar($this->model);
        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            return $this->render('view', ['model' => $this->model]);
        }
    }

    /**
     * Creates a new SondaggiDomandePagine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $idSondaggio
     * @param string|null $url
     * @return string|\yii\web\Response
     */
    public function actionCreate($idSondaggio, $url = null)
    {
        $this->setUpLayout('form');
        $this->model = new SondaggiDomandePagine();
        $this->model->sondaggi_id = $idSondaggio;
        $this->setMenuSidebar(Sondaggi::findOne($idSondaggio));

        $sondaggio = Sondaggi::findOne($idSondaggio);
        if ($sondaggio->status == Sondaggi::WORKFLOW_STATUS_VALIDATO) {
            Yii::$app->getSession()->addFlash('danger',
            AmosSondaggi::tHtml('amossondaggi', '#cannot_edit_published'));
            return $this->redirect(['index', 'idSondaggio' => $idSondaggio, 'url' => $url]);
        }

        if ($this->model->load(Yii::$app->request->post())) {
            //inizio upload immagine
            $avatar_id = null;
            $modelFile = new FilemanagerMediafile();
            $modelFile->load(Yii::$app->request->post());
            $file = UploadedFile::getInstance($modelFile, 'file');
            if ($file) {
                $routes = Yii::$app->getModule('upload')->routes;
                $modelFile->saveUploadedFile($routes, true);
                if ($modelFile->id) {
                    $avatar_id = $modelFile->id;
                    $this->model->filemanager_mediafile_id = $avatar_id;
                }
            }
            $this->model->ordinamento = SondaggiDomandePagine::find()->andWhere(['sondaggi_id' => $idSondaggio])->max('ordinamento') + 1;
            //fine upload immagine
            $this->model->save();
            /* $domanda = new \open20\amos\sondaggi\models\SondaggiDomande();
              $domanda->sondaggi_id = $this->model->sondaggi_id;
              $domanda->sondaggi_domande_pagine_id = $this->model->id; */
            // if ($url) {
            //     return $this->redirect($url);
            // } else {
            return $this->redirect(['index', 'idSondaggio' => $idSondaggio, 'url' => $url]);
            // }
        } else {
            return $this->render('create', [
                'model' => $this->model,
                'url' => $url
            ]);
        }
    }

    /**
     * Updates an existing SondaggiDomandePagine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string|null $url
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id, $url = null)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);
        $this->setMenuSidebar(Sondaggi::findOne($this->model->sondaggi_id));

        $sondaggio = Sondaggi::findOne($this->model->sondaggi_id);
        if ($sondaggio->status == Sondaggi::WORKFLOW_STATUS_VALIDATO) {
            Yii::$app->getSession()->addFlash('danger',
            AmosSondaggi::tHtml('amossondaggi', '#cannot_edit_published'));
            return $this->redirect(['index', 'idSondaggio' => $this->model->sondaggi_id, 'url' => $url]);
        }

        if ($this->model->load(Yii::$app->request->post())) {
            //inizio upload immagine
            $avatar_id = null;
            $modelFile = new FilemanagerMediafile();
            $modelFile->load(Yii::$app->request->post());
            $file = UploadedFile::getInstance($modelFile, 'file');
            if ($file) {
                $routes = Yii::$app->getModule('upload')->routes;
                $modelFile->saveUploadedFile($routes, true);
                if ($modelFile->id) {
                    $avatar_id = $modelFile->id;
                    $this->model->filemanager_mediafile_id = $avatar_id;
                }
            }
            //fine upload immagine
            if ($this->model->save()) {

                return $this->redirect(['index', 'idSondaggio' => $this->model->sondaggi_id, 'url' => $url]);
            }
        } else {
            return $this->render('update', [
                'model' => $this->model,
                'url' => ($url) ? $url : null
            ]);
        }
    }

    /**
     * Deletes an existing SondaggiDomandePagine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @param int $idSondaggio
     * @param string|null $url
     * @return \yii\web\Response
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id, $idSondaggio, $url = null)
    {

        $retMessage = SondaggiUtility::deletePage($id);

        if ($retMessage != 'ok') {
            Yii::$app->getSession()->addFlash('danger', $retMessage);
        } else {
            Yii::$app->getSession()->addFlash('success',
                AmosSondaggi::tHtml('amossondaggi', "Pagina cancellata correttamente."));
        }

        /*
        try {
            $this->model = $this->findModel($id);

            $domande = $this->model->getSondaggiDomandes()->count();
            if ($domande) {
                Yii::$app->getSession()->addFlash('danger',
                    AmosSondaggi::tHtml('amossondaggi', "Impossibile cancellare la pagina per la presenza di domande."));
            } else {
                if ($this->model->sondaggi->status != Sondaggi::WORKFLOW_STATUS_BOZZA) {
                    Yii::$app->getSession()->addFlash('danger',
                        AmosSondaggi::tHtml('amossondaggi',
                            "Impossibile cancellare la risposta in quanto il sondaggio a cui è collegata non è in stato BOZZA."));
                } else {
                    $this->model->delete();
                    Yii::$app->getSession()->addFlash('success',
                        AmosSondaggi::tHtml('amossondaggi', "Pagina cancellata correttamente."));
                }
            }
        } catch (NotFoundHttpException $e) {

        }
        */

        if ($url) {
            return $this->redirect($url);
        } else {
            return $this->redirect(['index', 'idSondaggio' => $idSondaggio, 'url' => $url]);
        }
    }

    /**
     * @param $model
     */
    public function setMenuSidebar($model)
    {
        \Yii::$app->getView()->params['showSidebarForm'] = true;
        \Yii::$app->getView()->params['bi-menu-sidebar'] = SondaggiUtility::getSidebarPages($model);
    }

    public function actionOrder($id, $order = 'dopo') {
        $sondaggio = SondaggiDomandePagine::findOne($id);
        \Yii::debug($sondaggio, 'sondaggi');
        $result = $sondaggio->setOrdinamento($order);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [$result];
    }
}
