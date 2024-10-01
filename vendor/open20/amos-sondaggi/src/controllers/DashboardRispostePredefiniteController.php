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
use open20\amos\sondaggi\models\search\SondaggiRispostePredefiniteSearch;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiDomande;
use open20\amos\sondaggi\models\SondaggiRispostePredefinite;
use open20\amos\sondaggi\utility\SondaggiUtility;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

/**
 * Class SondaggiRispostePredefiniteController
 * SondaggiRispostePredefiniteController implements the CRUD actions for SondaggiRispostePredefinite model.
 *
 * @property \open20\amos\sondaggi\models\SondaggiRispostePredefinite $model
 *
 * @package open20\amos\sondaggi\controllers
 */
class DashboardRispostePredefiniteController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'main';

    public $sondaggiModule = null;

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
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete',
                            'delete-all'
                        ],
                        'roles' => ['AMMINISTRAZIONE_SONDAGGI']
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
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sondaggiModule = AmosSondaggi::instance();
        $this->setModelObj(new SondaggiRispostePredefinite());
        $this->setModelSearch(new SondaggiRispostePredefiniteSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosSondaggi::tHtml('amossondaggi', 'Tabella')),
                'url' => '?currentView=grid'
            ]
        ]);

        parent::init();

        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        \Yii::$app->view->params['showSidebarForm']        = true;
        $this->setUpLayout('form');
    }

    /**
     * Lists all SondaggiRispostePredefinite models.
     * @return mixed
     */
    public function actionIndex($url = null)
    {
        $idSondaggio = null;
        $idDomanda = Yii::$app->request->get('idDomanda');
        $domanda = SondaggiDomande::findOne($idDomanda);
        if ($domanda) {
            $idSondaggio = $domanda->sondaggi_id;
        }
        Url::remember();
        $this->setCreateNewBtnParams();
        $this->setUrl($url);
        $this->setParametro($idDomanda);
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        if ($this->model->load(Yii::$app->request->post())) {

            $IsImported = false;
            if(!empty(\Yii::$app->request->post('SondaggiRispostePredefinite')['sondaggi_domande_id'])){
                $domandaId = \Yii::$app->request->post('SondaggiRispostePredefinite')['sondaggi_domande_id'];
                $IsImported = SondaggiRispostePredefinite::import($domandaId);
            }

            if(!$IsImported) {
                $ordinamento = 0;
                if(!empty(Yii::$app->request->post()['SondaggiRispostePredefinite']['ordine'])) {
                    $ordinamento = Yii::$app->request->post()['SondaggiRispostePredefinite']['ordine'];
                    $ordinaDopo = 0;
                    if (strlen($ordinamento) == 0) {
                        $ordinamento = 'fine';
                    }
                }
                $ordinaDopo = 0;
                if(!empty(Yii::$app->request->post()['SondaggiRispostePredefinite']['ordina_dopo'])) {
                    if ($ordinamento == 'dopo') {
                        $ordinaDopo = Yii::$app->request->post()['SondaggiRispostePredefinite']['ordina_dopo'];
                    }
                }
                $this->model->save();
                $this->model->setOrdinamento($ordinamento, $ordinaDopo);
            }
            if ($url) {
                return $this->redirect($url);
            } else {
                return $this->redirect(['index', 'idSondaggio' => $idSondaggio, 'idDomanda' => $idDomanda]);
            }
        }

        \Yii::$app->getView()->params['showSidebarForm'] = true;
        $this->setMenuSidebar(Sondaggi::findOne($idSondaggio), $idDomanda);

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null,
                'moduleName' => ($this->moduleName) ? $this->moduleName : null,
                'contextModelId' => ($this->contextModelId) ? $this->contextModelId : null,
            ]
        );

    }

    /**
     * Displays a single SondaggiRispostePredefinite model.
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
     * Creates a new SondaggiRispostePredefinite model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $idDomanda
     * @param string|null $url
     * @return string|\yii\web\Response
     */
    public function actionCreate($idDomanda, $url = null)
    {
        $idSondaggio = null;
        $idDomanda = Yii::$app->request->get('idDomanda');
        $domanda = SondaggiDomande::findOne($idDomanda);
        if ($domanda) {
            $idSondaggio = $domanda->sondaggi_id;
        }

        $sondaggio = Sondaggi::findOne($idSondaggio);
            if ($sondaggio && $sondaggio->status == Sondaggi::WORKFLOW_STATUS_VALIDATO) {
                Yii::$app->getSession()->addFlash('danger',
                AmosSondaggi::tHtml('amossondaggi', '#cannot_edit_published'));
                return $this->redirect(['index', 'idDomanda' => $idDomanda, 'url' => $url]);
            }

        $this->setUpLayout('form');
        $this->model = new SondaggiRispostePredefinite();
        $this->model->sondaggi_domande_id = $idDomanda;
        // Adding default answer position to "after"
        $this->model->ordine = 'fine';
        $this->setMenuSidebar(Sondaggi::findOne($idSondaggio), $idDomanda);

        if ($this->model->load(Yii::$app->request->post())) {
            $IsImported = false;
            if(!empty(\Yii::$app->request->post('SondaggiRispostePredefinite')['sondaggi_domande_id'])){
                $domandaId = \Yii::$app->request->post('SondaggiRispostePredefinite')['sondaggi_domande_id'];
                $IsImported = SondaggiRispostePredefinite::import($domandaId);
            }

            if(!$IsImported) {
                $ordinamento = 0;
                if(!empty(Yii::$app->request->post()['SondaggiRispostePredefinite']['ordine'])) {
                    $ordinamento = Yii::$app->request->post()['SondaggiRispostePredefinite']['ordine'];
                    $ordinaDopo = 0;
                    if (strlen($ordinamento) == 0) {
                        $ordinamento = 'fine';
                    }
                }
                $ordinaDopo = 0;
                if(!empty(Yii::$app->request->post()['SondaggiRispostePredefinite']['ordina_dopo'])) {
                    if ($ordinamento == 'dopo') {
                        $ordinaDopo = Yii::$app->request->post()['SondaggiRispostePredefinite']['ordina_dopo'];
                    }
                }
                if ($this->model->save()) {
                    $this->model->setOrdinamento($ordinamento, $ordinaDopo);
                } else {
                    return $this->render('update', [
                        'model' => $this->model,
                        'url' => ($url) ? $url : null,
                    ]);
                }
            }
            $newPage = Yii::$app->request->post('pagina');
            $newQuestion = Yii::$app->request->post('domanda');

            if ($newPage === '') return $this->redirect(['/sondaggi/dashboard-domande-pagine/create', 'idSondaggio'=> $idSondaggio]);
            if ($newQuestion === '') return $this->redirect(['/sondaggi/dashboard-domande/create', 'idSondaggio'=> $idSondaggio]);

            return $this->redirect(['/sondaggi/dashboard-risposte-predefinite/index', 'idDomanda'=> $idDomanda]);

        } else {
            return $this->render('create', [
                'model' => $this->model,
                'url' => $url
            ]);
        }
    }

    /**
     * Updates an existing SondaggiRispostePredefinite model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string|null $url
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id, $url = null)
    {
        $this->model = $this->findModel($id);
        $idSondaggio = null;
        $idDomanda = $this->model->sondaggi_domande_id;
        $domanda = SondaggiDomande::findOne($idDomanda);
        if ($domanda) {
            $idSondaggio = $domanda->sondaggi_id;
        }

        $sondaggio = Sondaggi::findOne($idSondaggio);
            if ($sondaggio && $sondaggio->status == Sondaggi::WORKFLOW_STATUS_VALIDATO) {
                Yii::$app->getSession()->addFlash('danger',
                AmosSondaggi::tHtml('amossondaggi', '#cannot_edit_published'));
                return $this->redirect(['index', 'idDomanda' => $idDomanda, 'url' => $url]);
            }

        $this->setUpLayout('form');
        $this->setMenuSidebar(Sondaggi::findOne($idSondaggio), $this->model->id);

        if ($this->model->load(Yii::$app->request->post())) {
            $ordinamento = Yii::$app->request->post()['SondaggiRispostePredefinite']['ordine'];
            $ordinaDopo = 0;
            if (strlen($ordinamento) == 0) {
                $ordinamento = 'fine';
            }
            if ($ordinamento == 'dopo') {
                $ordinaDopo = Yii::$app->request->post()['SondaggiRispostePredefinite']['ordina_dopo'];
            }
            if ($this->model->save()) {
                $this->model->setOrdinamento($ordinamento, $ordinaDopo);
                if ($url) {
                    return $this->redirect($url);
                }
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                return $this->render('update', [
                    'model' => $this->model,
                    'url' => ($url) ? $url : null,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $this->model,
                'url' => ($url) ? $url : null,
            ]);
        }
    }

    /**
     * Deletes an existing SondaggiRispostePredefinite model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string|null $url
     * @return \yii\web\Response
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id, $url = null)
    {
        $idSondaggio = null;
        $idDomanda = Yii::$app->request->get('idDomanda');
        $domanda = SondaggiDomande::findOne($idDomanda);
        if ($domanda) {
            $idSondaggio = $domanda->sondaggi_id;
        }
        $this->model = $this->findModel($id);
        $risposte = $this->model->getSondaggiDomandeCondizionates()->count();
        $domanda = SondaggiDomande::findOne(['id' => $this->model->sondaggi_domande_id]);
        if ($risposte) {
            Yii::$app->getSession()->addFlash('danger', AmosSondaggi::tHtml('amossondaggi', "Impossibile cancellare la risposta in quanto sono presenti domande condizionate da questa risposta."));
        } else {
            if ($domanda->sondaggi->status != Sondaggi::WORKFLOW_STATUS_BOZZA) {
                Yii::$app->getSession()->addFlash('danger', AmosSondaggi::tHtml('amossondaggi', "Impossibile cancellare la risposta in quanto il sondaggio a cui è collegata non è in stato BOZZA."));
            } else {
                $this->model->delete();
                Yii::$app->getSession()->addFlash('success', AmosSondaggi::tHtml('amossondaggi', "Risposta cancellata correttamente."));
            }
        }
        if ($url) {
            return $this->redirect($url);
        } else {
            return $this->redirect(['index', 'idSondaggio' => $idSondaggio, 'idDomanda' => $domanda->id, 'url' => $url]);
        }
    }

    /**
     * Deletes all existing SondaggiRispostePredefinite models.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $idDomanda
     * @param string|null $url
     * @return \yii\web\Response
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteAll($idDomanda, $url = null)
    {
        if ($idDomanda) {
            $domanda = SondaggiDomande::findOne(['id' => $idDomanda]);
            if (!is_null($domanda)) {
            $rispostePredefinite = $domanda->sondaggiRispostePredefinites;
                $allOk = true;
                foreach ($rispostePredefinite as $rispostaPredefinita) {
                    if ($rispostaPredefinita->getSondaggiDomandeCondizionates()->count()) {
                        Yii::$app->getSession()->addFlash('danger', AmosSondaggi::tHtml('amossondaggi', "Impossibile cancellare la risposta predefinita “" . $rispostaPredefinita->risposta . "”."));
                    }
                    else {
                        $rispostaPredefinita->delete();
                        if ($rispostaPredefinita->hasErrors()) {
                            $allOk = false;
                        }
                    }
                }
                if ($allOk) {
                    Yii::$app->getSession()->addFlash('success', AmosSondaggi::tHtml('amossondaggi', "Risposte predefinite cancellate correttamente."));
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosSondaggi::tHtml('amossondaggi', "Errore durante la cancellazione delle risposte predefinite."));
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosSondaggi::tHtml('amossondaggi', "Domanda non trovata."));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosSondaggi::tHtml('amossondaggi', "Impossibile cancellare le risposte predefinite senza id domanda."));
        }

        if ($url) {
            return $this->redirect($url);
        } else {
            return $this->redirect(['index', 'idDomanda' => $idDomanda, 'url' => $url]);
        }
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    protected function setCreateNewBtnParams()
    {
        $get = Yii::$app->request->get();
        $buttonLabel = AmosSondaggi::t('amossondaggi', 'Aggiungi risposta predefinita');

        $urlCreateNew = ['create'];
        if (isset($get['idDomanda'])) {
            $urlCreateNew['idDomanda'] =  filter_input(INPUT_GET, 'idDomanda');
        }

        $domanda = SondaggiDomande::findOne(filter_input(INPUT_GET, 'idDomanda'));


        if (isset($get['url'])) {
            $urlCreateNew['url'] = $get['url'];
        }

        $buttonEliminaRisposte = '';
        if (Yii::$app->user->can('AMMINISTRAZIONE_SONDAGGI') || Yii::$app->user->can('SONDAGGIRISPOSTEPREDEFINITE_UPDATE', ['model' => $this->model])) {
            $buttonEliminaRisposte = Html::button(AmosSondaggi::t('amossondaggi', 'Elimina risposte predefinite'), [
                'class' => 'btn pull-right btn-danger-inverse',
                'data-toggle' => 'modal',
                'data-target' => '#modalDeleteAll',
            ]);
        }
        $buttonImportaRisposte =  Html::button(AmosSondaggi::t('amossondaggi', 'Importa risposte predefinite'), [
            'class' => 'btn btn-primary pull-right',
            'style' => 'min-height: 36px;',
            'data-toggle' => 'modal',
            'data-target' => '#modalImport',
        ]);

        if($domanda) {
            $backButton = Html::a(AmosIcons::show('long-arrow-return', ['class' => 'm-r-5']) . AmosSondaggi::t('amossondaggi', "Torna alle domande"),
                ['/sondaggi/sondaggi-domande/index', 'idSondaggio' => $domanda->sondaggi_id, 'idPagina' => $domanda->sondaggi_domande_pagine_id], [
                    'class' => 'btn btn-secondary',
                    'title' => AmosSondaggi::t('amossondaggi', "Torna alle domande")
                ]);
        }

        Yii::$app->view->params['additionalButtons'] = [
            'htmlButtons' => [$backButton, $buttonImportaRisposte, $buttonEliminaRisposte]
        ];
        Yii::$app->view->params['createNewBtnParams'] = [
            'urlCreateNew' => $urlCreateNew,
            'createNewBtnLabel' => $buttonLabel

        ];
    }

    public function setMenuSidebar($model, $idQuestion)
    {
        \Yii::$app->getView()->params['showSidebarForm'] = true;
        \Yii::$app->getView()->params['bi-menu-sidebar'] = SondaggiUtility::getSidebarPages($model, $idQuestion);
    }
}
