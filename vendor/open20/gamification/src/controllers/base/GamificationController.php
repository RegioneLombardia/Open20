<?php

namespace open20\gamification\controllers\base;

use open20\amos\admin\models\UserProfile;
use open20\gamification\models\PunteggiTotUtenti;
use open20\gamification\models\PunteggiUtenti;
use open20\gamification\Module;
use Yii;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use open20\gamification\models\Gamification;
use open20\gamification\models\search\GamificationSearch;
use yii\web\NotFoundHttpException;


/**
 * Class GamificationController implements the CRUD actions for Gamification model.
 *
 * @property Gamification $model
 * @property GamificationSearch $modelSearch
 *
 * @package open20\gamification\controller\base
 */
class GamificationController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->setModelObj(new Gamification());
        $this->setModelSearch(new GamificationSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),

                'url' => '?currentView=grid'
            ],
            /*'icon' => [
                'name' => 'icon',
                'label' => AmosIcons::show('view-module') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Scheda')),
                'url' => '?currentView=icon'
            ],
            'map' => [
                'name' => 'map',
                'label' => AmosIcons::show('map') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Map')),
                'url' => '?currentView=map'
            ],
            'list' => [
                'name' => 'list',
                'label' => AmosIcons::show('view-list') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'List')),
                'url' => '?currentView=list'
            ],
            'calendar' => [
                'name' => 'calendar',
                'intestazione' => '', //codice HTML per l'intestazione che verrà caricato prima del calendario,
                                      //per esempio si può inserire una funzione $model->getHtmlIntestazione() creata ad hoc
                'label' => AmosIcons::show('calendar') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Calendari')),
                'url' => '?currentView=calendar'
            ],*/
        ]);

        parent::init();
        $this->setUpLayout();
    }

    public function beforeAction($action)
    {

        if (\Yii::$app->user->isGuest) {
            $hideCreate = true;
            $titleScopePreventCreate = \Yii::t('gamification',"L'utenza non è autorizzata all'uso della funzione");

        } elseif (\Yii::$app->user->can('GAMIFICATION_ADMINISTRATOR')){
            $canCreate = true;
            $hideCreate = false;
        }

        $labelCreate = Module::t('gamification', 'Nuovo');
        $titleCreate = Module::t('gamification', 'Inserisci una nuova gamification');
        $urlCreate = '/gamification/gamification/create';

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'scuole',
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'urlCreate' => $urlCreate,
            'hideCreate' => $hideCreate,
            'titleScopePreventCreate' => $titleScopePreventCreate,
            'canCreate' => $canCreate,
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['request-information', 'ricalcola'],
                            'allow' => true,
                            'roles' => ['@']
                        ],
                        [
                            'actions' => ['create', 'clone', 'update', 'index', 'view', 'detail-user-export', 'delete'],
                            'allow' => true,
                            'roles' => ['GAMIFICATION_ADMINISTRATOR'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'request-information' => ['post', 'get']
                    ]
                ]
            ]);
        return $behaviors;
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    protected function setCreateNewBtnParams()
    {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => Module::t('gamification', 'Nuovo'),
            'urlCreateNew' => '/gamification/gamification/create',
            'otherOptions' => [
                'title' => Module::t('gamification', 'Aggiungi una nuova gamification'),
                'class' => 'btn btn-primary'
            ]
        ];
    }

    /**
     * This method is useful to set all common params for all list views.
     * @param bool $setCurrentDashboard
     */
    protected function setListViewsParams($setCurrentDashboard = true)
    {
        $this->setCreateNewBtnParams();
        $this->setUpLayout('list');
        Yii::$app->session->set(Module::beginCreateNewSessionKey(), Url::previous());
        Yii::$app->session->set(Module::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
    }

    /**
     * Used for set page title and breadcrumbs.
     * @param string $pageTitle News page title (ie. Created by news, ...)
     */
    public function setTitleAndBreadcrumbs($pageTitle)
    {
        Yii::$app->session->set('previousTitle', $pageTitle);
        Yii::$app->session->set('previousUrl', Url::previous());
        Yii::$app->view->title = $pageTitle;
        Yii::$app->view->params['breadcrumbs'] = [
            ['label' => $pageTitle]
        ];
    }

    /**
     * Lists all gamification models.
     * @return mixed
     */
    public function actionIndex($layout = NULL, $idDelete = null)
    {
        if (!is_null($idDelete)){
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Impossibile eliminare la gamification'));
        }
        Url::remember();
        \Yii::$app->controller->view->params['titleSection'] =  Module::t('gamification', 'Gamification');
        \Yii::$app->controller->view->params['labelLinkAll'] = Module::t('gamification', 'La mia gamification');
        \Yii::$app->controller->view->params['titleLinkAll'] = Module::t('gamification', 'Mostra tutte le gamifications');

        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        $this->setListViewsParams();
        $this->setTitleAndBreadcrumbs(Module::t('gamification', 'Gamification'));

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'layout' => $layout,
            ]
        );
    }

    /**
     * Clone a single gamification model.
     * @param integer $id
     * @return mixed
     */
    public function actionClone($id)
    {
        $this->model = $this->findModel($id);
        $clone = clone $this->model;
        $clone->isNewRecord = true;
        $clone->id = null;
        $clone->status = 'GamificationWorkflow/DRAFT';
        $clone->save(false);

        return $this->redirect('index');
    }

    public function InsertTotPunteggi($user_id = null) {
        if (!($this->model->status == Gamification::GAMIFICATION_WORKFLOW_STATUS_CONCLUDED))
        {
            $this->model->punteggioIscizioneEFotoUtente($this->model->id, $user_id);
            $this->model->punteggioCollegamentoUtente($this->model->id, $user_id);
            $this->model->punteggioCreazioneCommunity($this->model->id, $user_id);
            $this->model->punteggioIscrizioneCommunity($this->model->id, $user_id);
            $this->model->punteggioCreazioneNews($this->model->id, $user_id);
            $this->model->punteggioCommentiNews($this->model->id, $user_id);
            $this->model->punteggioLikeNotizie($this->model->id, $user_id);
            $this->model->punteggioCreazioneDocumenti($this->model->id, $user_id);
            $this->model->punteggioCreazioneDiscussioni($this->model->id, $user_id);
            $this->model->punteggioCommentiDiscussioni($this->model->id, $user_id);
            $this->model->punteggioLikeDiscussioni($this->model->id, $user_id);
            $this->model->punteggioCreazioneSondaggi($this->model->id, $user_id);

            $this->model->aggiornaTabellaPunteggiTotUsers($this->model->id);

            $totPunteggi = PunteggiTotUtenti::find()
                ->andWhere(['gamification_id' => $this->model->id])
                ->orderBy(['totale' => SORT_DESC])
                ->all();
            foreach ($totPunteggi as $i => $punteggioTot) {
                $punteggioTot->posizione = $i + 1;
                $punteggioTot->save();
            }
        }
    }

    /**
     * Displays a single gamification model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);

        $dataQuery = PunteggiTotUtenti::find()
            ->andWhere(['gamification_id' => $id])
            ->orderBy(['totale' => SORT_DESC]);

        $modelPunteggiTotUtenti = $dataQuery->all();

        $dataProvider = new ActiveDataProvider([
            'query' => $dataQuery
        ]);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            return $this->render('view',
                [
                    'model' => $this->model,
                    'currentView' => $this->getCurrentView(),
                    'dataProvider' => $dataProvider,
                    'modelPunteggiTotUtenti' => $modelPunteggiTotUtenti,
                ]);
        }
    }

    public function actionDetailUserExport($id, $gamification) {
        $data = PunteggiTotUtenti::findOne(['user_id' => $id]);
        $userProfile = UserProfile::findOne(['user_id' => $id]);
        $dataDownload = PunteggiUtenti::find()
            ->select(['datetime_attivita', 'username', 'nome', 'cognome', 'email', 'attivita', 'punteggio'])
            ->andWhere(['user_profile_id' => $userProfile->id])
            ->andWhere(['gamification_id' => $gamification])
            ->orderBy('datetime_attivita')
            ->all();

        PunteggiUtenti::generateExcellFromFile($dataDownload);
    }

    /**
     * Creates a new gamification model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $this->model = new Gamification();

        if ($this->model->load(Yii::$app->request->post())) {

            if (is_null($this->model->utente_iscrizione)){
                $this->model->utente_iscrizione = Gamification::defaultValue_utente_iscrizione;
            }
            if (is_null($this->model->utente_foto)){
                $this->model->utente_foto = Gamification::defaultValue_utente_foto;
            }
            if (is_null($this->model->utente_colleg)){
                $this->model->utente_colleg = Gamification::defaultValue_utente_colleg;
            }
            if (is_null($this->model->community_creazione)){
                $this->model->community_creazione = Gamification::defaultValue_community_creazione;
            }
            if (is_null($this->model->community_iscrizione)){
                $this->model->community_iscrizione = Gamification::defaultValue_community_iscrizione;
            }
            if (is_null($this->model->notizie_creazione)){
                $this->model->notizie_creazione = Gamification::defaultValue_notizie_creazione;
            }
            if (is_null($this->model->notizie_commento)){
                $this->model->notizie_commento = Gamification::defaultValue_notizie_commento;
            }
            if (is_null($this->model->notizie_like)){
                $this->model->notizie_like = Gamification::defaultValue_notizie_like;
            }
            if (is_null($this->model->documenti_creazione)){
                $this->model->documenti_creazione = Gamification::defaultValue_documenti_creazione;
            }
            if (is_null($this->model->discussioni_creazione)){
                $this->model->discussioni_creazione = Gamification::defaultValue_discussioni_creazione;
            }
            if (is_null($this->model->discussioni_commento)){
                $this->model->discussioni_commento = Gamification::defaultValue_discussioni_commento;
            }
            if (is_null($this->model->discussioni_like)){
                $this->model->discussioni_like = Gamification::defaultValue_discussioni_like;
            }
            if (is_null($this->model->sondaggi_partec)){
                $this->model->sondaggi_partec = Gamification::defaultValue_sondaggi_partec;
            }

            if ($this->model->status == 'GamificationWorkflow/OPENING') {
                $this->model->status = 'GamificationWorkflow/DRAFT';
                if ($this->model->save()) {
                    $this->model->status = 'GamificationWorkflow/OPENING';
                }
            }
            if ($this->model->validate() && $this->model->save()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->render('create', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Creates a new Gamification model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new Gamification();

        if (\Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                //Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                return json_encode($this->model->toArray());
            } else {
                //Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->renderAjax('_formAjax', [
            'model' => $this->model,
            'fid' => $fid,
            'dataField' => $dataField
        ]);
    }

    /**
     * Updates an existing Gamification model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {

            if (is_null($this->model->utente_iscrizione)){
                $this->model->utente_iscrizione = Gamification::defaultValue_utente_iscrizione;
            }
            if (is_null($this->model->utente_foto)){
                $this->model->utente_foto = Gamification::defaultValue_utente_foto;
            }
            if (is_null($this->model->utente_colleg)){
                $this->model->utente_colleg = Gamification::defaultValue_utente_colleg;
            }
            if (is_null($this->model->community_creazione)){
                $this->model->community_creazione = Gamification::defaultValue_community_creazione;
            }
            if (is_null($this->model->community_iscrizione)){
                $this->model->community_iscrizione = Gamification::defaultValue_community_iscrizione;
            }
            if (is_null($this->model->notizie_creazione)){
                $this->model->notizie_creazione = Gamification::defaultValue_notizie_creazione;
            }
            if (is_null($this->model->notizie_commento)){
                $this->model->notizie_commento = Gamification::defaultValue_notizie_commento;
            }
            if (is_null($this->model->notizie_like)){
                $this->model->notizie_like = Gamification::defaultValue_notizie_like;
            }
            if (is_null($this->model->documenti_creazione)){
                $this->model->documenti_creazione = Gamification::defaultValue_documenti_creazione;
            }
            if (is_null($this->model->discussioni_creazione)){
                $this->model->discussioni_creazione = Gamification::defaultValue_discussioni_creazione;
            }
            if (is_null($this->model->discussioni_commento)){
                $this->model->discussioni_commento = Gamification::defaultValue_discussioni_commento;
            }
            if (is_null($this->model->discussioni_like)){
                $this->model->discussioni_like = Gamification::defaultValue_discussioni_like;
            }
            if (is_null($this->model->sondaggi_partec)){
                $this->model->sondaggi_partec = Gamification::defaultValue_sondaggi_partec;
            }

            if ($this->model->save()) {
                if ($this->model->status == 'GamificationWorkflow/CONCLUDED'){
                    $this->InsertTotPunteggi(null);
                }
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item updated'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not updated, check data'));
            }

            //$this->model->aggiornaOpeningToConcludedPerData($this->model->id);
        }

        return $this->render('update', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    public function actionRicalcola($gamif_id, $user_id = null){
        $this->model = $this->findModel($gamif_id);
        $this->InsertTotPunteggi($user_id);
        if ($user_id == null) {
            Yii::$app->session->addFlash('success', 'Ricalcolo completato con successo.');
            return $this->redirect(['view', 'id' => $gamif_id]);
        } else {
            Yii::$app->session->addFlash('success', 'Ricalcolo completato con successo.');
            $user = UserProfile::findOne(['user_id' => $user_id]);
            return $this->redirect(['/amosadmin/user-profile/update', 'id' => $user->id]);
        }
    }

    /**
     * Deletes an existing Gamification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            
            $model->delete();            
            if (!$model->hasErrors()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Element deleted successfully.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'You are not authorized to delete this element.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::tHtml('amoscore', 'Element not found.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Gamification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Gamification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Gamification::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
