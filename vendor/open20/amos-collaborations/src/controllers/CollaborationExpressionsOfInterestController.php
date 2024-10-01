<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\controllers
 */

namespace open20\amos\collaborations\controllers;

use open20\amos\collaborations\events\CollaborationExpressionsOfInterestEvent;
use open20\amos\collaborations\models\CollaborationExpressionsOfInterest;
use open20\amos\collaborations\models\CollaborationProposals;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use open20\amos\collaborations\assets\CollaborationsAsset;
use Yii;

/**
 * Class CollaborationExpressionsOfInterestController
 * This is the class for controller "CollaborationExpressionsOfInterestController".
 * @package open20\amos\collaborations\controllers
 */
class CollaborationExpressionsOfInterestController extends \open20\amos\collaborations\controllers\base\CollaborationExpressionsOfInterestController
{
    /**
     * Init
     * @return void
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        CollaborationsAsset::register(\Yii::$app->view);


        parent::init();
    }

    /**
     * Behaviors
     * @return array
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'ruleConfig' => [
                        'class' => AccessRule::className()
                    ],
                    'rules' => [
                        [
                            // Collaborations Administrator
                            'allow' => true,
                            'actions' => [
                                'manage',
                                'received-for-own-proposal',
                                'received'
                            ],
                            'roles' => ['COLLABORATIONS_ADMINISTRATOR', 'COLLABORATIONS_STAFF']
                        ],
                        [
                            // Collaborations Reader
                            'allow' => true,
                            'actions' => [
                                'created-by-me'
                            ],
                            'roles' => ['COLLABORATIONS_READER']
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
     * Before action
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $titleSection = \Yii::t('amoscollaborations', 'Manifestazioni di interesse');
        $labelLinkAll = '';
        $urlLinkAll = '';
        $titleLinkAll = \Yii::t('amoscollaborations', 'Manifestazioni di interesse');

        //$subTitleSection = Html::tag('p', \Yii::t('open_challenge', 'Elenco Challenge'));

        //$labelCreate = null;
        //$titleCreate = \Yii::t('amoscollaborations', 'Crea nuova proposta di collaborazione');
        $labelManage = \Yii::t('amoscollaborations', 'Gestisci');
        $titleManage = \Yii::t('amoscollaborations', 'Gestisci manifestazioni di interesse');
        //$urlCreate = '/collaborations/collaboration-proposals/create';
        $urlManage = null;

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'collaborationExpressionsOfInterest',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'urlLinkAll' => $urlLinkAll,
            'labelLinkAll' => $labelLinkAll,
            'titleLinkAll' => $titleLinkAll,
            //'labelCreate' => $labelCreate,
            //'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            //'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
            'hideCreate' => true    // Hide create
        ];
        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }

    /**
     * Manage links button
     * @return array
     */
    public static function getManageLinks()
    {
        if (\Yii::$app->user->can('COLLABORATIONS_ADMINISTRATOR') || \Yii::$app->user->can('COLLABORATIONS_STAFF')) {
            $links = [];
            $links[] = [
                'title' => \Yii::t('amoscollaborations', 'Tutte le manifestazioni di interesse ricevute'),
                'label' => \Yii::t('amoscollaborations', 'Ricevute'),
                'url' => Url::to(['received'])
            ];
            $links[] = [
                'title' => \Yii::t('amoscollaborations', 'Tutte le manifestazioni di interesse create da me'),
                'label' => \Yii::t('amoscollaborations', 'Create da me'),
                'url' => Url::to(['created-by-me'])
            ];
            $links[] = [
                'title' => \Yii::t('amoscollaborations', 'Amministra tutte le manifestazioni di interesse'),
                'label' => \Yii::t('amoscollaborations', 'Amministra'),
                'url' => Url::to(['manage'])
            ];
        }
        return $links;
    }

    /**
     * Expressions of interest received
     */
    public function actionReceived()
    {
        CollaborationsAsset::register(\Yii::$app->view);
        Url::remember();

        $this->view->params['titleSection'] = \Yii::t('amoscollaborations', 'Manifestazioni di interesse ricevute');
        $this->view->params['labelLinkAll'] = \Yii::t('amoscollaborations', 'Create da me');
        $this->view->params['urlLinkAll'] = '/collaborations/collaboration-expressions-of-interest/created-by-me';
        $this->view->params['titleLinkAll'] = \Yii::t('amoscollaborations', 'Visualizza le manifestazioni di interesse create da me');
        //$this->view->params['subTitleSection'] = \yii\helpers\Html::tag('p', \Yii::t('amoscollaborations', 'Elenco delle proposte di collaborazione da pubblicare'));

        $this->setDataProvider($this->modelSearch->searchReceived(\Yii::$app->request->getQueryParams()));
        $this->setUpLayout('list');

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
            ]
        );
    }

    /**
     * Creates a new CollaborationExpressionsOfInterest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $this->model = new CollaborationExpressionsOfInterest();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                //Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                (new CollaborationExpressionsOfInterestEvent)->sendNotifyToCollaborationProposalInterested($this->model);
                return $this->render('parts/thank_you');
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
     * Expressions of interest created by me
     */
    public function actionCreatedByMe()
    {
        CollaborationsAsset::register(\Yii::$app->view);
        Url::remember();

        $this->view->params['titleSection'] = \Yii::t('amoscollaborations', 'Manifestazioni di interesse create da me');
        if (\Yii::$app->user->can('COLLABORATIONS_ADMINISTRATOR') || \Yii::$app->user->can('COLLABORATIONS_STAFF')) {
            $this->view->params['labelLinkAll'] = \Yii::t('amoscollaborations', 'Ricevute');
            $this->view->params['urlLinkAll'] = '/collaborations/collaboration-expressions-of-interest/received';
            $this->view->params['titleLinkAll'] = \Yii::t('amoscollaborations', 'Visualizza tutte le manifestazioni di interesse ricevute');
        }
        //$this->view->params['subTitleSection'] = \yii\helpers\Html::tag('p', \Yii::t('amoscollaborations', 'Elenco delle proposte di collaborazione da pubblicare'));

        $this->setDataProvider($this->modelSearch->searchCreatedByMe(\Yii::$app->request->getQueryParams()));
        $this->setUpLayout('list');

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
            ]
        );
    }

    /**
     * All expressions of interest
     */
    public function actionManage()
    {
        Url::remember();

        $this->view->params['titleSection'] = \Yii::t('amoscollaborations', 'Amministra manifestazioni di interesse');
        $this->view->params['labelLinkAll'] = \Yii::t('amoscollaborations', 'Ricevute');
        $this->view->params['urlLinkAll'] = '/collaborations/collaboration-expressions-of-interest/received';
        $this->view->params['titleLinkAll'] = \Yii::t('amoscollaborations', 'Visualizza tutte le manifestazioni di interesse ricevute');
        //$this->view->params['subTitleSection'] = \yii\helpers\Html::tag('p', \Yii::t('amoscollaborations', 'Elenco delle proposte di collaborazione da pubblicare'));

        $this->setDataProvider($this->modelSearch->search(\Yii::$app->request->getQueryParams()));
        $this->setUpLayout('list');

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
            ]
        );
    }

    public function actionReceivedForOwnProposal($id)
    {
        $this->view->params['titleSection'] = \Yii::t('amoscollaborations', 'Manifestazioni di interesse ricevute');
        $this->view->params['subTitleSection'] = Html::tag('p', \Yii::t('amoscollaborations', 'Manifestazioni di interesse ricevute per la proposta ') . CollaborationProposals::findOne($id)->title);
        $this->view->params['labelLinkAll'] = \Yii::t('amoscollaborations', 'Ricevute');
        $this->view->params['urlLinkAll'] = '/collaborations/collaboration-expressions-of-interest/received';
        $this->view->params['titleLinkAll'] = \Yii::t('amoscollaborations', 'Visualizza tutte le manifestazioni di interesse ricevute');

        $this->setDataProvider($this->modelSearch->searchReceivedForOwnProposal(\Yii::$app->request->getQueryParams(), $id));
        $this->setUpLayout('list');

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
            ]
        );
    }
}
