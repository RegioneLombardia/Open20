<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 11/03/2020
 * Time: 12:19
 */

namespace open20\amos\sondaggi\controllers;

use open20\amos\admin\controllers\SecurityController;
use open20\amos\admin\models\UserProfile;
use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\core\validators\CFValidator;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\cwh\models\CwhRegolePubblicazione;
use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\emailmanager\models\EmailTemplate;
use open20\amos\news\models\search\NewsSearch;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\utility\SondaggiUtility;
use open20\amos\sondaggi\models\search\SondaggiSearch;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiDomande;
use open20\amos\sondaggi\models\SondaggiDomandeCondizionate;
use open20\amos\sondaggi\models\SondaggiDomandePagine;
use open20\amos\sondaggi\models\SondaggiRispostePredefinite;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;
use open20\amos\sondaggi\models\SondaggiInvitations;
use open20\amos\sondaggi\models\SondaggiInvitationMm;
use open20\amos\upload\models\FilemanagerMediafile;
use open20\amos\attachments\FileModule;
use Yii;
use open20\amos\organizzazioni\models\Profilo;

class DashboardController extends CrudController
{

    /**
     * @inheritdoc
     */
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
                            ],
                            'roles' => ['@']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'info',
                                'pages',
                                'publish',
                                'clone',
                                'manage',
                                'create',
                                'download-file-example',
                                'download-import-file-example',
                                'communications',
                                'create-communication',
                                'update-communication',
                                'send-communications',
                                'delete-communication',
                            ],
                            'roles' => ['AMMINISTRAZIONE_SONDAGGI']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'dashboard',
                                'compilations'
                            ],
                            'roles' => ['DASHBOARD_VIEW']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'hour-by-date'
                            ]
                        ],
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
     * @var string $layout
     */
    public $layout = 'list';
    public
        $moduleCwh,
        $scope;

    /**
     * @var AmosEvents $sondaggiModule
     */
    public $sondaggiModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {

        $this->sondaggiModule = AmosSondaggi::instance();

        $this->setModelObj($this->sondaggiModule->createModel('Sondaggi'));
        $this->setModelSearch($this->sondaggiModule->createModel('SondaggiSearch'));

//        EventsAsset::register(\Yii::$app->view);

        $this->scope     = null;
        $this->moduleCwh = \Yii::$app->getModule('cwh');

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

        //    \Yii::$app->params['bsVersion']                    = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        \Yii::$app->view->params['showSidebarForm']        = true;
        $this->setUpLayout('form');
    }

    /**
     * @param null $layout
     * @return string|\yii\web\Response
     */
    public function actionIndex($layout = null)
    {
        // if ($this->sondaggiModule->enableDashboard == true) {
        return $this->redirect(['/sondaggi/sondaggi/manage']);
        // } else {
        //     return $this->redirect(['/sondaggi/pubblicazione']);
        // }
    }

    /**
     *
     */
    public function actionDashboard($id)
    {
        $this->model = $this->findModel($id);
        $this->model->loadCustomTags();
        $this->setMenuSidebar($this->model);
        $user = \Yii::$app->user;
        return $this->render('view', ['model' => $this->model, 'user' => $user]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionInfo($id)
    {
        $this->model = $this->findModel($id);
        $this->setMenuSidebar($this->model);

        $this->model->getOtherAttributes();
        if (AmosSondaggi::instance()->forceOnlyFrontend){
            $this->model->frontend = 1;
        }
        $this->model->loadCustomTags();

        $this->scope     = null;
        $this->moduleCwh = Yii::$app->getModule('cwh');

        if (!empty($this->moduleCwh)) {
            $this->scope = $this->moduleCwh->getCwhScope();
        }
        $post = Yii::$app->request->post();
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

                $this->model->getOtherAttributes($post);
                $this->model->filemanager_mediafile_id = $avatar_id;
                if ($this->model->save()) {
                    $this->model->saveCustomTags();
                    Yii::$app->getSession()->addFlash('success',
                        AmosSondaggi::tHtml('amossondaggi', "Sondaggio aggiornato correttamente."));
                    return $this->redirect(['info', 'id' => $this->model->id]);
                } else {
                    Yii::$app->getSession()->addFlash('danger',
                        AmosSondaggi::tHtml('amossondaggi', 'Sondaggio non aggiornato. Verifica i dati inseriti.'));
                }
            }
        }

        return $this->render('create',
                [
                'model' => $this->model,
                'moduleCwh' => $this->moduleCwh,
                'scope' => $this->scope,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionCompilations($id)
    {
        $this->model = $this->findModel($id);
        $this->setMenuSidebar($this->model);

        $this->model->getOtherAttributes();

        $this->scope     = null;
        $this->moduleCwh = Yii::$app->getModule('cwh');

        if (!empty($this->moduleCwh)) {
            $this->scope = $this->moduleCwh->getCwhScope();
        }

        $this->setCurrentView($this->getAvailableView('grid'));

        $compilations = null;
        $compilations = SondaggiInvitationMm::find()->andWhere(['sondaggi_id' => $id])->joinWith('to', true, 'RIGHT JOIN');

        \Yii::debug($compilations->createCommand()->rawSql, 'sondaggi');

        $dataProvider = new ActiveDataProvider([
            'query' => $compilations,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('_compilations',
                [
                'dataProvider' => $dataProvider,
                'currentView' => $this->getCurrentView()
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionInvitations($id)
    {
        $this->model     = $this->findModel($id);
        $this->setMenuSidebar($this->model);
        $this->model->getOtherAttributes();
        $this->scope     = null;
        $this->moduleCwh = Yii::$app->getModule('cwh');
        if (!empty($this->moduleCwh)) {
            $this->scope = $this->moduleCwh->getCwhScope();
        } else {
            throw new \yii\web\HttpException(401);
        }
        $post = Yii::$app->request->post();

        if ($this->model->validate()) {
            if ($this->model->load($post)) {
                if ($this->model->save()) {
                    Yii::$app->getSession()->addFlash('success',
                        AmosSondaggi::tHtml('amossondaggi', "Permessi aggiornati correttamente."));
                    return $this->redirect(['invitations', 'id' => $this->model->id]);
                } else {
                    Yii::$app->getSession()->addFlash('danger',
                        AmosSondaggi::tHtml('amossondaggi', 'Permessi non aggiornati. Verifica i dati inseriti.'));
                }
            }
        }

        return $this->render('invitations',
                [
                'model' => $this->model,
                'moduleCwh' => $this->moduleCwh,
                'scope' => $this->scope,
        ]);
    }

    /**
     * Creates a new Sondaggi model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @param string|null $url
     * @return string
     */
    public function actionCreate($url = null)
    {
        $this->setupLayout('form');
        \Yii::$app->getView()->params['showSidebarForm'] = false;

        $this->model           = new Sondaggi();
        $pagine                = new SondaggiDomandePagine();
        $domanda               = new SondaggiDomande();
        $risposta              = new SondaggiRispostePredefinite();
        $post                  = \Yii::$app->request->post();
        $this->model->scenario = Sondaggi::SCENARIO_CREATE;

        if (AmosSondaggi::instance()->forceOnlyFrontend){
            $this->model->frontend = 1;
        }

        \Yii::debug($post, 'sondaggi');

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
                $this->model->validate();
                if ($this->model->hasErrors()) {
                    pr($this->model->errors);
                    die;
                }
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
                        return $this->redirect(['/sondaggi/dashboard/dashboard', 'id' => $this->model->id]);
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
                        return $this->render('/dashboard-domande-pagine/create',
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
                // Default options
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
     * Deletes an existing Sondaggi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return \yii\web\Response
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id, $url = null)
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
        $pagine      = $this->model->getSondaggiDomandePagines()->count();
        if ($pagine) {
            Yii::$app->getSession()->addFlash('danger',
                AmosSondaggi::tHtml('amossondaggi', "Impossibile cancellare il sondaggio per la presenza di pagine."));
        } else {
            if ($this->model->status != Sondaggi::WORKFLOW_STATUS_BOZZA) {
                Yii::$app->getSession()->addFlash('danger',
                    AmosSondaggi::tHtml('amossondaggi',
                        "Impossibile cancellare il sondaggio in quanto non è in stato BOZZA."));
            } else {
                $this->model->delete();
                Yii::$app->getSession()->addFlash('success',
                    AmosSondaggi::tHtml('amossondaggi', "Sondaggio cancellato correttamente."));
            }
        }
        if (!empty($url)) return $this->redirect($url);

        return $this->redirect('sondaggi');
        */
    }

    public function actionClone($id, $url = null)
    {
        /** @var  $model Sondaggi */
        $model      = $this->findModel($id);
        $created_by = \Yii::$app->user->id;


        $data['Sondaggi']                    = $model->attributes;
        $sondaggio                           = new Sondaggi();
        $sondaggio->load($data);
        $sondaggio->status                   = Sondaggi::WORKFLOW_STATUS_BOZZA;
        $sondaggio->titolo                   = $sondaggio.' (clone)';
        $sondaggio->validatori               = $model->validatori;
        $sondaggio->filemanager_mediafile_id = $model->filemanager_mediafile_id;
        $sondaggio->regola_pubblicazione     = $model->regola_pubblicazione;
        $sondaggio->destinatari              = $model->destinatari;
        $sondaggio->created_by               = $created_by;
        $sondaggio->updated_by               = $created_by;
        $ok                                  = $sondaggio->save(false);

        foreach ($model->getFiles() as $file) {
            FileModule::instance()->attachFile($file->path, $sondaggio, 'file', false);
        }


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

            foreach ($pagina->getFiles() as $file) {
                FileModule::instance()->attachFile($file->path, $newPagina, 'file', false);
            }

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

                foreach ($domanda->getFiles() as $file) {
                    FileModule::instance()->attachFile($file->path, $newDomanda, 'file', false);
                }

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

                // SOTTO-DOMANDE
                $sub_questions = $domanda->getChildren()->all();
                if ($domanda->is_parent && !empty($sub_questions)) {
                    foreach ($sub_questions as $child) {
                        $data                                  = [];
                        $newSubDomanda                         = new SondaggiDomande();
                        $data['SondaggiDomande']               = $child->attributes;
                        $newSubDomanda->load($data);
                        $newSubDomanda->id                     = null;
                        $newSubDomanda->parent_id              = $newDomanda->id;
                        $newSubDomanda->created_by             = $created_by;
                        $newSubDomanda->updated_by             = $created_by;
                        $newSubDomanda->save();
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

            // INVITI
            $invitations = $model->invitations;
            foreach ($invitations as $invitation) {
                $data                        = [];
                $newInvitation               = new SondaggiInvitations();
                $data['SondaggiInvitations'] = $invitation->attributes;
                $newInvitation->load($data);
                $newInvitation->id                      = null;
                $newInvitation->sondaggi_id             = $sondaggio->id;
                $newInvitation->count                   = $invitation->count;
                $newInvitation->invited                 = false;
                $newInvitation->created_by              = $created_by;
                $newInvitation->updated_by              = $created_by;
                $okInv                                  = $newInvitation->save();
            }
        }
        if ($ok) {
            \Yii::$app->session->addFlash('success', AmosSondaggi::t('amossondaggi', 'Sondaggio duplicato con successo'));
            return $this->redirect(['/sondaggi/dashboard/dashboard', 'id' => $sondaggio->id]);
        } else {
            \Yii::$app->session->addFlash('danger',
                AmosSondaggi::t('amossondaggi', 'Errore durante la duplicazione del sondaggio'));
            return $this->redirect($url);
        }
    }

    public function actionPages($id)
    {
        $sondaggio = $this->findModel($id);
        $this->setMenuSidebar($sondaggio);
        Url::remember();
        $this->setUrl($url);
        $this->setModelSearch(new \open20\amos\sondaggi\models\search\SondaggiDomandePagineSearch());
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt').Html::tag('p',
                    AmosSondaggi::tHtml('amossondaggi', 'Tabella')),
                'url' => '?currentView=grid'
            ],
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));

        return parent::actionIndex('form');
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    protected function setCreateNewBtnParams()
    {
        $get         = Yii::$app->request->get();
        $buttonLabel = AmosSondaggi::t('amossondaggi', 'Aggiungi pagina');
        $sondaggio   = Sondaggi::findOne(filter_input(INPUT_GET, 'idSondaggio'));

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
        if ($canCreate) {
            Yii::$app->view->params['createNewBtnParams'] = [
                'urlCreateNew' => $urlCreateNew,
                'createNewBtnLabel' => $buttonLabel
            ];
        }
        if (!empty($get['idSondaggio'])) {
            $backButton                                  = Html::a(AmosIcons::show('long-arrow-return',
                        ['class' => 'm-r-5']).AmosSondaggi::t('amossondaggi', "Torna ai sondaggi"),
                    ['/sondaggi/sondaggi/index'],
                    [
                    'class' => 'btn btn-secondary',
                    'title' => AmosSondaggi::t('amossondaggi', "Torna ai sondaggi")
            ]);
            Yii::$app->view->params['additionalButtons'] = [
                'htmlButtons' => [$backButton]
            ];
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

    /**
     * @param $id
     */
    public function setScope($id)
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh)) {
            $moduleCwh->setCwhScopeInSession([
                'community' => $id,
                ],
                [
                'mm_name' => 'community_user_mm',
                'entity_id_field' => 'community_id',
                'entity_id' => $id
            ]);
        }
    }

    /**
     * @param $string
     * @return string
     */
    public function getStringCompiled($n)
    {
        if ($n > 0) {
            return AmosSondaggi::t('amossondaggi', "Compilato")." ".AmosIcons::show('check',
                    ['style' => 'color:#28a745']);
        } else {
            return AmosSondaggi::t('amossondaggi', "Non compilato")." ".AmosIcons::show('close',
                    ['class' => 'text-danger']);
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
                    $rispostaPredefinitaCodice = $Array[1];
                    if (!empty($rispostaPredefinitaName)) {
                        $rispostaPredefinita                      = new SondaggiRispostePredefinite();
                        $rispostaPredefinita->risposta            = $rispostaPredefinitaName;
                        $rispostaPredefinita->sondaggi_domande_id = $idDomanda;
                        $rispostaPredefinita->code                = $rispostaPredefinitaCodice;
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

    /**
     * Creates a new SondaggiComunication model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @param string|null $url
     * @return string
     */
    public function actionCreateCommunication($idSondaggio, $url = null)
    {
        $this->setUpLayout('form');
        $this->model              = new \open20\amos\sondaggi\models\SondaggiComunication();
        $this->model->sondaggi_id = $idSondaggio;

        $sondaggio = Sondaggi::findOne($idSondaggio);
        $this->setMenuSidebar($sondaggio, $this->model->id);

        if ($this->model->load(Yii::$app->request->post())) {
            $post                             = \Yii::$app->request->post();
            $entiInvitati                     = $sondaggio->getEntiInvitati();
            $cloneEntiInvitati                = clone $entiInvitati;
            $entiCheHannoCompilato            = $sondaggio->getEntiCheHannoCompilato();
            $entiInvitatiCheNonHannoCompilato = $sondaggio->getEntiInvitatiNonCompilato($cloneEntiInvitati->select('to_id'));
            $type                             = $post['SondaggiComunication']['type'];
            $query                            = null;
            $count                            = 0;
            switch ($type) {
                case 0:
                    $query = $entiInvitati->createCommand()->rawSql;
                    $count = $entiInvitati->count();
                    break;
                case 1:
                    $query = $entiCheHannoCompilato->createCommand()->rawSql;
                    $count = $entiCheHannoCompilato->count();
                    break;
                case 2:
                    $query = $entiInvitatiCheNonHannoCompilato->createCommand()->rawSql;
                    $count = $entiInvitatiCheNonHannoCompilato->count();
                    break;
            }
            $this->model->query = $query;
            $this->model->count = $count;
            if ($this->model->save()) {
                \Yii::$app->session->addFlash('success', AmosSondaggi::t('amossondaggi', "Comunicazione salvata"));
            } else {
                \Yii::$app->session->addFlash('danger', AmosSondaggi::t('amossondaggi', "Errore"));
            }
            return $this->redirect(['/sondaggi/dashboard/communications', 'sondaggi_id' => $idSondaggio, 'url' => $url]);
        }

        return $this->render('create_com',
                [
                'model' => $this->model,
                'url' => ($url) ? $url : null,
        ]);
    }

    public function actionUpdateCommunication($id, $url = null)
    {
        $this->setUpLayout('form');
        $this->model              = \open20\amos\sondaggi\models\SondaggiComunication::findOne($id);


        $sondaggio = Sondaggi::findOne($this->model->sondaggi_id);
        $this->setMenuSidebar($sondaggio, $this->model->id);

        if ($this->model->load(Yii::$app->request->post())) {
            $post                             = \Yii::$app->request->post();
            $entiInvitati                     = $sondaggio->getEntiInvitati();
            $cloneEntiInvitati                = clone $entiInvitati;
            $entiCheHannoCompilato            = $sondaggio->getEntiCheHannoCompilato();
            $entiInvitatiCheNonHannoCompilato = $sondaggio->getEntiInvitatiNonCompilato($cloneEntiInvitati->select('to_id'));
            $type                             = $post['SondaggiComunication']['type'];
            $query                            = null;
            $count                            = 0;
            switch ($type) {
                case 0:
                    $query = $entiInvitati->createCommand()->rawSql;
                    $count = $entiInvitati->count();
                    break;
                case 1:
                    $query = $entiCheHannoCompilato->createCommand()->rawSql;
                    $count = $entiCheHannoCompilato->count();
                    break;
                case 2:
                    $query = $entiInvitatiCheNonHannoCompilato->createCommand()->rawSql;
                    $count = $entiInvitatiCheNonHannoCompilato->count();
                    break;
            }
            $this->model->query = $query;
            $this->model->count = $count;
            if ($this->model->save()) {
                \Yii::$app->session->addFlash('success', AmosSondaggi::t('amossondaggi', "Comunicazione salvata"));
            } else {
                \Yii::$app->session->addFlash('danger', AmosSondaggi::t('amossondaggi', "Errore"));
            }
            return $this->redirect(['/sondaggi/dashboard/communications', 'sondaggi_id' => $this->model->sondaggi_id, 'url' => $url]);
        }

        return $this->render('update_com',
                [
                'model' => $this->model,
                'url' => ($url) ? $url : null,
        ]);
    }

    public function actionCommunications($sondaggi_id)
    {
        $this->model = $this->findModel($sondaggi_id);
        $this->setMenuSidebar($this->model);

        $this->model->getOtherAttributes();

        $this->scope     = null;
        $this->moduleCwh = Yii::$app->getModule('cwh');

        if (!empty($this->moduleCwh)) {
            $this->scope = $this->moduleCwh->getCwhScope();
        }
        Url::remember();
        $this->setUrl($url);
        $this->setModelSearch(new \open20\amos\sondaggi\models\search\SondaggiComunicationSearch());
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));

        $this->setCurrentView($this->getAvailableView('grid'));

        return $this->render('communications',
                [
                'moduleCwh' => $this->moduleCwh,
                'scope' => $this->scope,
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null
        ]);
    }

    public function actionSendCommunications($id, $url, $preview = false)
    {
        $comunicazione = \open20\amos\sondaggi\models\SondaggiComunication::findOne($id);
        if (!empty($comunicazione)) {
            $to  = $comunicazione->email_test;
            $all = \Yii::$app->db->createCommand($comunicazione->query)->queryAll();
            if ($preview == true) {
                $this->sendEmailGeneral($comunicazione->email_test, $comunicazione->subject, $comunicazione->message);
            } else {

                foreach ($all as $single) {
                    if ($comunicazione->type == 1)
                        $to_id = $single['id'];
                    else
                        $to_id        = $single['to_id'];
                    $email        = '';
                    $organization = Profilo::findOne($to_id);
                    if (!empty($organization)) {
                        $referente = $organization->referenteOperativo;
                        if (!is_null($referente)) {
                            $email = $referente->user->email;
                        } else {
                            $email = $organization->email;
                        }
                    }
                    \Yii::debug($email, 'sondaggi');
                    if (!empty($email)) {
                        $this->sendEmailGeneral($email, $comunicazione->subject, $comunicazione->message);
                    }
                }
            }
        }
        return $this->redirect([$url]);
    }

    protected function sendEmailGeneral($to, $subject, $message, $files = [])
    {
        try {
            $from = '';
            if (isset(\Yii::$app->params['email-assistenza'])) {
                //use default platform email assistance
                $from = \Yii::$app->params['email-assistenza'];
            }

            /** @var Email $email */
            $email = new Email();
            $email->sendMail($from, $to, $subject, $message, $files);
        } catch (\Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return true;
    }

    public function actionDeleteCommunication($id, $url)
    {
        $comunicazione = \open20\amos\sondaggi\models\SondaggiComunication::findOne($id);
        if (!empty($comunicazione)) {
            $comunicazione->delete();
        }
        return $this->redirect([$url]);
    }
}
