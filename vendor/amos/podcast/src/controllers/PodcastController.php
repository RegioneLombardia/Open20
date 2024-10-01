<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\podcast\controllers
 */

namespace amos\podcast\controllers;

use amos\podcast\models\Podcast;
use amos\podcast\models\PodcastEpisode;
use amos\podcast\Module;
use amos\podcast\widgets\icons\WidgetIconPodcastAdmin;
use amos\podcast\widgets\icons\WidgetIconPodcastCategory;
use amos\podcast\widgets\icons\WidgetIconPodcastOwn;
use amos\podcast\widgets\icons\WidgetIconPodcastPublished;
use amos\podcast\widgets\icons\WidgetIconPodcastToValidate;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class PodcastController
 * This is the class for controller "PodcastController".
 * @package amos\podcast\controllers
 */
class PodcastController extends \amos\podcast\controllers\base\PodcastController
{

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
                                'to-validate-episodes',
                                'own',
                            ],
                            'roles' => ['PODCAST_READER', 'ADMINISTRATOR_PODCAST']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'admin',
                            ],
                            'roles' => ['PODCAST_VALIDATOR', 'ADMINISTRATOR_PODCAST',]
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'to-validate',
                            ],
                            'roles' => ['PODCAST_VALIDATOR', 'ADMINISTRATOR_PODCAST', \amos\podcast\rules\ValidatorPodcastRule::className()]
                        ],
                    ],

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
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionOwn()
    {
        Url::remember();

        $this->view->title = Module::t('amospodcast', "Creati da me");
        $this->view->params['titleSection'] = $this->view->title;

        $this->setDataProvider($this->modelSearch->searchOwn(\Yii::$app->request->getQueryParams()));
        $this->setIndexParams();
        $this->setUpLayout('list');

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
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAdmin()
    {
        Url::remember();
        $this->view->title = Module::t('amospodcast', "Amministra podcast");
        $this->view->params['titleSection'] = $this->view->title;

        $this->setDataProvider($this->modelSearch->searchAdmin(\Yii::$app->request->getQueryParams()));
        $this->setIndexParams();
        $this->setUpLayout('list');

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
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionToValidate()
    {
        Url::remember();
        $this->view->title = Module::t('amospodcast', "Podcast da validare");
        $this->view->params['titleSection'] = $this->view->title;

        $this->setDataProvider($this->modelSearch->searchToValidatePlusEpisodes(\Yii::$app->request->getQueryParams()));
        $this->setIndexParams();
        $this->setUpLayout('list');

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

    public function actionToValidateEpisodes($showall = false)
    {

        $this->view->title = Module::t('amospodcast', "Episodi da validare");
        $this->view->params['titleSection'] = $this->view->title;


        $expandRowKey = \Yii::$app->request->post('expandRowKey');
        $podcast = Podcast::findOne($expandRowKey);
//        $episodes = $podcast->podcastEpisodes;

        \Yii::$app->request->setQueryParams(['aid' => $expandRowKey]);


        $query = $podcast->getPodcastEpisodes();
        if (!$showall) {
            $query->andWhere(['status' => PodcastEpisode::PODCASTEP_WORKFLOW_STATUS_REQUESTPUBLICATION]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
//        $dataProvider = $this->getModelSearch()->searchByActivity(\Yii::$app->request->getQueryParams());
        $dataProvider->sort = false;


        try {
            $createBtn = '';
//

            return \kartik\grid\GridView::widget([
                'id' => 'podcast-gridview',
                'dataProvider' => $dataProvider,
                'responsive' => true,
                'export' => false,
                'pjax' => true,
                'pjaxSettings' => [
                    'options' => [
                        'id' => 'product-grid',
                        'timeout' => (isset(\Yii::$app->params['timeout']) ? \Yii::$app->params['timeout'] : 20000),
                        'enablePushState' => false
                    ]
                ],
                'columns' => [
                    'title',
                    [
                        'attribute' => 'workflowStatus.label',
                        'label' => Module::t('amospodcast', 'Status')
                    ],

                    [
                        'class' => 'open20\amos\core\views\grid\ActionColumn',
                        'controller' => 'podcast-episode'
                    ],
                ],
                'panel' => [
                    'before' => false,
                    'heading' => '<h3 class="panel-title">' . Module::t('amospodcast', 'Episodes') . '</h3>',
                    'type' => 'success',
                    'after' => false,
                    //Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
                    'footer' => false
                ],
            ]);
        } catch (\Exception $e) {
            pr($e->getTraceAsString());
            return $e->getMessage();
        }
    }


    /**
     *
     * @return array
     */
    public static function getManageLinks()
    {
        if (\Yii::$app->user->can(WidgetIconPodcastPublished::class)) {

            $links[] = [
                'title' => Module::t('amospodcast', 'Visualizza tutti i podcast'),
                'label' => Module::t('amospodcast', 'Tutti i podcast'),
                'url' => '/podcast/podcast/index'
            ];
        }
        if (\Yii::$app->user->can(WidgetIconPodcastOwn::class)) {

            $links[] = [
                'title' => Module::t('amospodcast', 'Visualizza I podcast creati da me'),
                'label' => Module::t('amospodcast', 'Creati da me'),
                'url' => '/podcast/podcast/own'
            ];
        }
        if (\Yii::$app->user->can(WidgetIconPodcastToValidate::class)) {

            $links[] = [
                'title' => Module::t('amospodcast', 'Visualizza I podcast da validare'),
                'label' => Module::t('amospodcast', 'Da validare'),
                'url' => '/podcast/podcast/to-validate'
            ];
        }

        if (\Yii::$app->user->can(WidgetIconPodcastAdmin::class)) {

            $links[] = [
                'title' => Module::t('amospodcast', 'Amministra i podcast'),
                'label' => Module::t('amospodcast', 'Amministra'),
                'url' => '/podcast/podcast/admin'
            ];
        }

        if (\Yii::$app->user->can(WidgetIconPodcastCategory::class)) {

            $links[] = [
                'title' => Module::t('amospodcast', 'Visualizza le categorie'),
                'label' => Module::t('amospodcast', 'Categorie podcast'),
                'url' => '/podcast/podcast-category/index'
            ];
        }


        return $links;
    }

}
