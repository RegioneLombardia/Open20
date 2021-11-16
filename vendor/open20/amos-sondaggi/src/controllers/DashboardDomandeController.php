<?php
/**
 * Lombardia Informatica S.p.A.
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
use open20\amos\sondaggi\models\SondaggiRispostePredefinite;
use open20\amos\sondaggi\utility\SondaggiUtility;
use open20\amos\attachments\FileModule;
use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Class SondaggiDomandeController
 * SondaggiDomandeController implements the CRUD actions for SondaggiDomande model.
 *
 * @property \open20\amos\sondaggi\models\SondaggiDomande $model
 * @property \open20\amos\sondaggi\models\search\SondaggiDomandeSearch $modelSearch
 *
 * @package open20\amos\sondaggi\controllers
 */
class DashboardDomandeController extends CrudController
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
                                'clone'
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
        $this->dataProvider->query->andWhere(['parent_id' => null]);

        if ($idSondaggio) {
            $backButton = Html::a(AmosIcons::show('long-arrow-return', ['class' => 'm-r-5']) . AmosSondaggi::t('amossondaggi', "Torna alle pagine"),
                ['/sondaggi/dashboard-domande-pagine/index', 'idSondaggio' => $idSondaggio], [
                    'class' => 'btn btn-secondary',
                    'title' => AmosSondaggi::t('amossondaggi', "Torna alle pagine")
                ]);
            Yii::$app->view->params['additionalButtons'] = [
                'htmlButtons' => [$backButton]
            ];
        }
//        return parent::actionIndex($layout); // TODO sistemare questo punto cambiando totalmente la action in quanto non compatibile con gli standard di PHP 7
        \Yii::$app->getView()->params['showSidebarForm'] = true;
        $this->setMenuSidebar(Sondaggi::findOne($idSondaggio));

        return parent::actionIndex('form');
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
        $this->setMenuSidebar(Sondaggi::findOne($idSondaggio), $this->model->id);

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

        $sondaggio = Sondaggi::findOne($idSondaggio);
        if ($sondaggio->status == Sondaggi::WORKFLOW_STATUS_VALIDATO) {
            Yii::$app->getSession()->addFlash('danger',
            AmosSondaggi::tHtml('amossondaggi', '#cannot_edit_published'));
            return $this->redirect(['index', 'idSondaggio' => $idSondaggio, 'url' => $url]);
        }
        if (count($sondaggio->sondaggiDomandePagines) <= 0) {
            Yii::$app->getSession()->addFlash('danger',
            AmosSondaggi::tHtml('amossondaggi', '#cannot_add_question_no_pages'));
            return $this->redirect(['index', 'idSondaggio' => $idSondaggio, 'url' => $url]);
        }

        $this->setMenuSidebar(Sondaggi::findOne($idSondaggio), $this->model->id);
        if ($idPagina) {
            $this->model->sondaggi_domande_pagine_id = $idPagina;
        }
        if ($this->model->load(Yii::$app->request->post())) {
            if ($this->model->parent_id == 'prompt') $this->model->parent_id = null;
            if (!empty($this->model->parent_id)) {
                $parent = SondaggiDomande::findOne($this->model->parent_id);
                $this->model->sondaggi_id = $parent->sondaggi_id;
                $this->model->sondaggi_domande_pagine_id = $parent->sondaggi_domande_pagine_id;
                $this->model->sondaggi_domande_tipologie_id = $parent->sondaggi_domande_tipologie_id;
            }
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
            } else {
                return $this->render('update', [
                    'model' => $this->model,
                    'url' => ($url) ? $url : null,
                ]);
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
                return $this->redirect(['/sondaggi/dashboard-domande/index', 'idSondaggio'=> $idSondaggio, 'idPagina'=> $idPagina, 'url' => $url ]);

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
        $idSondaggio = $this->model->sondaggi_id;

        $sondaggio = Sondaggi::findOne($idSondaggio);
            if ($sondaggio->status == Sondaggi::WORKFLOW_STATUS_VALIDATO) {
                Yii::$app->getSession()->addFlash('danger',
                AmosSondaggi::tHtml('amossondaggi', '#cannot_edit_published'));
                return $this->redirect(['index', 'idSondaggio' => $idSondaggio, 'url' => $url]);
            }

        $validazioni = [];
        $this->setMenuSidebar(Sondaggi::findOne($idSondaggio), $this->model->id);
        foreach ((array)$this->model->sondaggiDomandeRuleMms as $v) {
            $validazioni[] = $v->sondaggi_domande_rule_id;
        }
        $this->model->validazione = $validazioni;

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->parent_id == 'prompt') $this->model->parent_id = null;
            if (!empty($this->model->parent_id)) {
                $parent = SondaggiDomande::findOne($this->model->parent_id);
                $this->model->sondaggi_id = $parent->sondaggi_id;
                $this->model->sondaggi_domande_pagine_id = $parent->sondaggi_domande_pagine_id;
                $this->model->sondaggi_domande_tipologie_id = $parent->sondaggi_domande_tipologie_id;
            }
            foreach ($this->model->getChildren()->all() as $child) {
                $child->sondaggi_domande_tipologie_id = $this->model->sondaggi_domande_tipologie_id;
                $child->save(false);
            }
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
        if ($risposte && in_array($this->model->sondaggiDomandeTipologie->id, [1, 2, 3, 4])) {
            Yii::$app->getSession()->addFlash('danger',
                AmosSondaggi::tHtml('amossondaggi',
                    "Impossibile cancellare la domanda in quanto sono presenti risposte predefinite collegate."));
        } else {
            if ($this->model->sondaggi->status != Sondaggi::WORKFLOW_STATUS_BOZZA) {
                Yii::$app->getSession()->addFlash('danger',
                    AmosSondaggi::tHtml('amossondaggi',
                        "Impossibile cancellare la domanda in quanto il sondaggio a cui è collegata non è in stato BOZZA."));
            } else {
                \open20\amos\sondaggi\models\SondaggiRispostePredefinite::deleteAll(['sondaggi_domande_id' => $id]);
                \open20\amos\sondaggi\models\SondaggiDomande::deleteAll(['parent_id' => $id]);
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

    public function setMenuSidebar($model, $idQuestion)
    {
        \Yii::$app->getView()->params['showSidebarForm'] = true;
        \Yii::$app->getView()->params['bi-menu-sidebar'] = SondaggiUtility::getSidebarPages($model, $idQuestion);
    }

    public function actionClone($id) {
        $created_by = \Yii::$app->user->id;
        $newDomCond                    = [];
        $data                          = [];

        $domanda = SondaggiDomande::findOne($id);

        $sondaggio = Sondaggi::findOne($domanda->sondaggi_id);
        if ($sondaggio->status == Sondaggi::WORKFLOW_STATUS_VALIDATO) {
            Yii::$app->getSession()->addFlash('danger',
            AmosSondaggi::tHtml('amossondaggi', '#cannot_edit_published'));
            return $this->redirect(['index', 'idSondaggio' => $domanda->sondaggi_id, 'url' => $url]);
        }
        if (count($sondaggio->sondaggiDomandePagines) <= 0) {
            Yii::$app->getSession()->addFlash('danger',
            AmosSondaggi::tHtml('amossondaggi', '#cannot_add_question_no_pages'));
            return $this->redirect(['index', 'idSondaggio' => $domanda->sondaggi_id, 'url' => $url]);
        }

        //DOMANDE
        $data                                   = [];
        $newDomanda                             = new SondaggiDomande();
        $data['SondaggiDomande']                = $domanda->attributes;
        $newDomanda->load($data);
        $newDomanda->id                         = null;
        $newDomanda->sondaggi_id                = $domanda->sondaggi_id;
        $newDomanda->domanda_condizionata       = $domanda->domanda_condizionata;
        $newDomanda->sondaggi_domande_pagine_id = $domanda->sondaggi_domande_pagine_id;
        $newDomanda->created_by                 = $created_by;
        $newDomanda->updated_by                 = $created_by;
        $okDom                                  = $newDomanda->save();

        foreach($domanda->getFiles() as $file) {
            FileModule::instance()->attachFile($file->path, $newDomanda, 'file', false);
        }

        if ($okDom) {
            if ($domanda->domanda_condizionata == 1) {
                $rispCond                    = $domanda->sondaggiRispostePredefinitesCondizionate;
                $newDomCond[$newDomanda->id] = ['ordinamento' => $rispCond->ordinamento, 'risposta' => $rispCond->risposta,
                    'pagina' => $domanda->sondaggi_domande_pagine_id];
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
        if ($okDom) {
            \Yii::$app->session->addFlash('success', AmosSondaggi::t('amossondaggi', '#clone_question_success'));
            return $this->redirect(['index', 'idSondaggio' => $domanda->sondaggi_id]);
        } else {
            \Yii::$app->session->addFlash('danger',
                AmosSondaggi::t('amossondaggi', '#clone_question_error'));
            return $this->redirect(['index', 'idSondaggio' => $domanda->sondaggi_id]);
        }
    }
}
