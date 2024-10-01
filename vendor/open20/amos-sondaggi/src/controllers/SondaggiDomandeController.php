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
use open20\amos\sondaggi\models\search\SondaggiDomandeSearch;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\SondaggiDomande;
use open20\amos\sondaggi\utility\SondaggiUtility;
use Yii;
use yii\helpers\Url;

/**
 * Class SondaggiDomandeController
 * SondaggiDomandeController implements the CRUD actions for SondaggiDomande model.
 *
 * @property \open20\amos\sondaggi\models\SondaggiDomande $model
 * @property \open20\amos\sondaggi\models\search\SondaggiDomandeSearch $modelSearch
 *
 * @package open20\amos\sondaggi\controllers
 */
class SondaggiDomandeController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setModelObj(new SondaggiDomande());
        $this->setModelSearch(new SondaggiDomandeSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p',
                        AmosSondaggi::tHtml('amossondaggi', 'Tabella')),
                'url' => '?currentView=grid'
            ]
        ]);

        parent::init();

        $this->setUpLayout();
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    protected function setCreateNewBtnParams()
    {
        $get = Yii::$app->request->get();
        $urlCreateNew = ['create'];
        $buttonLabel = AmosSondaggi::t('amossondaggi', 'Aggiungi domanda');

        if (isset($get['idSondaggio'])) {
            $urlCreateNew['idSondaggio'] = filter_input(INPUT_GET, 'idSondaggio');
        }
        if (isset($get['idPagina'])) {
            $urlCreateNew['idPagina'] = filter_input(INPUT_GET, 'idPagina');
        }
        if (isset($get['url'])) {
            $urlCreateNew['url'] = $get['url'];
        }

        Yii::$app->view->params['createNewBtnParams'] = [
            'urlCreateNew' => $urlCreateNew,
            'createNewBtnLabel' => $buttonLabel
        ];
    }

    /**
     * This method is useful to set all common params for all list views.
     */
    protected function setListViewsParams($idSondaggio = null)
    {
        $sondaggio = Sondaggi::findOne($idSondaggio);
        $canCreate = true;
        if ($sondaggio) {
            if ($sondaggio->sondaggio_type == SondaggiTypes::SONDAGGI_TYPE_LIVE) {
                if ($sondaggio->hasAlreadyDomande()) {
                    $canCreate = false;
                }

            }
        }
        if ($canCreate) {
            $this->setCreateNewBtnParams();
        }
        $this->setUpLayout('list');
        Yii::$app->session->set(AmosSondaggi::beginCreateNewSessionKey(), Url::previous());
        Yii::$app->session->set(AmosSondaggi::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
    }

    /**
     * Lists all SondaggiDomande models.
     * @return mixed
     */
    public function actionIndex($idSondaggio = null, $idPagina = null, $url = null)
    {
        Url::remember();
        $this->setUrl($url);
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        $this->setListViewsParams($idSondaggio);

        if ($idSondaggio) {
            $backButton = Html::a(AmosIcons::show('long-arrow-return', ['class' => 'm-r-5']) . AmosSondaggi::t('amossondaggi', "Torna alle pagine"),
                ['/sondaggi/sondaggi-domande-pagine/index', 'idSondaggio' => $idSondaggio], [
                    'class' => 'btn btn-secondary',
                    'title' => AmosSondaggi::t('amossondaggi', "Torna alle pagine")
                ]);
            Yii::$app->view->params['additionalButtons'] = [
                'htmlButtons' => [$backButton]
            ];
        }
//        return parent::actionIndex($layout); // TODO sistemare questo punto cambiando totalmente la action in quanto non compatibile con gli standard di PHP 7
        return parent::actionIndex();
    }

    /**
     * Displays a single SondaggiDomande model.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
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
     * Creates a new SondaggiDomande model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $idSondaggio
     * @param int|null $idPagina
     * @param string|null $url
     * @return string|\yii\web\Response
     */
    public function actionCreate($idSondaggio, $idPagina = null, $url = null)
    {
        $this->setUpLayout('form');
        $this->model = new SondaggiDomande();
        $this->model->sondaggi_id = $idSondaggio;
        if ($idPagina) {
            $this->model->sondaggi_domande_pagine_id = $idPagina;
        }
        if ($this->model->load(Yii::$app->request->post())) {
            $condizioneNecessaria = (!empty($this->model->condizione_necessaria)) ? $this->model->condizione_necessaria : [
            ];
            $ordinamento = Yii::$app->request->post()['SondaggiDomande']['ordine'];
            $ordinaDopo = 0;
            if (strlen($ordinamento) == 0) {
                $ordinamento = 'fine';
            }
            if ($ordinamento == 'dopo') {
                $ordinaDopo = Yii::$app->request->post()['SondaggiDomande']['ordina_dopo'];
            }
            if($this->model->save()){
                \Yii::$app->session->addFlash('success', AmosSondaggi::t('amossondaggi', "Domanda creata correttamente"));
            }
            $this->model->setOrdinamento($ordinamento, $ordinaDopo,
                (isset($this->model->condizione_necessaria)) ? $this->model->condizione_necessaria : 0);

            $this->model->setValidazione($this->model->validazione);

            if ($this->model->domanda_condizionata) {
                foreach ($condizioneNecessaria as $cond) {
                    $condizione = new \open20\amos\sondaggi\models\SondaggiDomandeCondizionate();
                    $condizione->sondaggi_risposte_predefinite_id = $cond;
                    $condizione->sondaggi_domande_id = $this->model->id;
                    $condizione->save();
                }
            }
            if ($this->model->modello_risposte_id) {
                $num = \open20\amos\sondaggi\models\SondaggiRispostePredefinite::importFromModello($this->model->modello_risposte_id, $this->model->id);
            }
//            if ($url) {
//                $this->redirect($url);
//            } else {
                return $this->redirect(['/sondaggi/sondaggi-domande/index', 'idSondaggio'=> $idSondaggio, 'idPagina'=> $idPagina, 'url' => $url ]);

//            }
        }

        return $this->render('create',
            [
                'model' => $this->model,
                'url' => ($url) ? $url : null,
            ]);
    }

    /**
     * Updates an existing SondaggiDomande model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param null $url
     * @return string|\yii\web\Response
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id, $url = null)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);
        $validazioni = [];
        foreach ((array)$this->model->sondaggiDomandeRuleMms as $v) {
            $validazioni[] = $v->sondaggi_domande_rule_id;
        }
        $this->model->validazione = $validazioni;

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $condizioneNecessaria = (!empty($this->model->condizione_necessaria)) ? $this->model->condizione_necessaria : [
            ];
            $ordinamento = Yii::$app->request->post()['SondaggiDomande']['ordine'];
            $ordinaDopo = 0;
            if (strlen($ordinamento) == 0) {
                $ordinamento = 'fine';
            }
            if ($ordinamento == 'dopo') {
                $ordinaDopo = Yii::$app->request->post()['SondaggiDomande']['ordina_dopo'];
            }
            $this->model->save();
            if (empty($this->model->ordinamento) || !empty($this->model->ordine)) {
                $this->model->setOrdinamento($ordinamento, $ordinaDopo,
                    (!empty($this->model->condizione_necessaria)) ? $this->model->condizione_necessaria : 0);
            }
            $this->model->setValidazione($this->model->validazione);
            \open20\amos\sondaggi\models\SondaggiDomandeCondizionate::deleteAll(['sondaggi_domande_id' => $id]);
            if ($this->model->domanda_condizionata) {
                foreach ($condizioneNecessaria as $cond) {
                    $condizione = new \open20\amos\sondaggi\models\SondaggiDomandeCondizionate();
                    $condizione->sondaggi_risposte_predefinite_id = $cond;
                    $condizione->sondaggi_domande_id = $this->model->id;
                    $condizione->save();
                }
            }

            if ($this->model->modello_risposte_id) {
                $num = \open20\amos\sondaggi\models\SondaggiRispostePredefinite::importFromModello($this->model->modello_risposte_id, $this->model->id);
            }
            if ($url) {
                return $this->redirect($url);
            } else {
                return $this->redirect(['update', 'id' => $this->model->id]);
            }
        } else {
            return $this->render('update',
                [
                    'model' => $this->model,
                    'url' => ($url) ? $url : null,
                ]);
        }
    }

    /**
     * Deletes an existing SondaggiDomande model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @param int $idSondaggio
     * @param null $url
     * @return \yii\web\Response
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id, $idSondaggio, $url = null)
    {
        $retMessage = SondaggiUtility::deleteAnswer($id);

        if ($retMessage != 'ok') {
            Yii::$app->getSession()->addFlash('danger', $retMessage);
        } else {
            Yii::$app->getSession()->addFlash('success',
                AmosSondaggi::tHtml('amossondaggi', "Domanda cancellata correttamente."));
        }

        /*
        $this->model = $this->findModel($id);

        $risposte = $this->model->getSondaggiRispostePredefinites()->count();
        if ($risposte) {
            Yii::$app->getSession()->addFlash('danger',
                AmosSondaggi::tHtml('amossondaggi',
                    "Impossibile cancellare la domanda in quanto sono presenti risposte predefinite collegate."));
        } else {
            if ($this->model->sondaggi->status != Sondaggi::WORKFLOW_STATUS_BOZZA) {
                Yii::$app->getSession()->addFlash('danger',
                    AmosSondaggi::tHtml('amossondaggi',
                        "Impossibile cancellare la domanda in quanto il sondaggio a cui è collegata non è in stato BOZZA."));
            } else {
                \open20\amos\sondaggi\models\SondaggiDomandeCondizionate::deleteAll(['sondaggi_domande_id' => $id]);
                $this->model->delete();
                Yii::$app->getSession()->addFlash('success',
                    AmosSondaggi::tHtml('amossondaggi', "Domanda cancellata correttamente."));
            }
        }

        */

        if ($url) {
            return $this->redirect($url);
        } else {
            return $this->redirect(['index', 'idSondaggio' => $idSondaggio, 'url' => $url]);
        }
    }
}