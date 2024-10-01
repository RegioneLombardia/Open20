<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\elasticsearch\controllers\base
 */

namespace open20\elasticsearch\controllers;

use open20\elasticsearch\models\ElasticQuery;
use Yii;
use open20\elasticsearch\models\ElasticsearchHistory;
use open20\elasticsearch\models\search\ElasticsearchHistorySearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\helpers\Url;
use yii\web\Response;


/**
 * Class ElasticsearchHistoryController
 * ElasticsearchHistoryController implements the CRUD actions for ElasticsearchHistory model.
 *
 * @property \open20\elasticsearch\models\ElasticsearchHistory $model
 * @property \open20\elasticsearch\models\search\ElasticsearchHistorySearch $modelSearch
 *
 * @package open20\elasticsearch\controllers\base
 */
class ElasticsearchHistoryController extends CrudController
{

    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->setModelObj(new ElasticsearchHistory());
        $this->setModelSearch(new ElasticsearchHistorySearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
        ]);

        parent::init();
        $this->setUpLayout();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'most-searched-words',
                            ],
                            'roles' => ['ELASTICSEARCH_MANAGE_HISTORY']
                        ],

                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get']
                    ],
                ],
            ]
        );

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {

        $this->view->params = [
            'hideCreate' => true,
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }

    /**
     * Lists all ElasticsearchHistory models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();

        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        //se il layout di default non dovesse andar bene si può aggiuntere il layout desiderato
        //in questo modo nel controller "return parent::actionIndex($this->layout);"
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
     * Displays a single ElasticsearchHistory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);
        $results = (Array)json_decode($this->model->results, true);

        $query = new ElasticQuery();
        $res = $query->buildResult($results);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $res
        ]);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            return $this->render('view', ['model' => $this->model, 'dataProvider' => $dataProvider]);
        }
    }

    /**
     * Creates a new ElasticsearchHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        die;
        $this->setUpLayout('form');
        $this->model = new ElasticsearchHistory();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
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
     * Creates a new ElasticsearchHistory model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new ElasticsearchHistory();

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
     * Updates an existing ElasticsearchHistory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        die;
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item updated'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not updated, check data'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Deletes an existing ElasticsearchHistory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            $this->model->delete();
            if (!$this->model->hasErrors()) {
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
     * @param string $searchText
     * @return string
     */
    public function actionMostSearchedWords($search_text = '')
    {
        $query = new Query();
        $query->select('search_text, count(*) as n')
            ->from('elasticsearch_history')
            ->groupBy('search_text')
            ->orderBy('n DESC')
            ->limit(15);
        $result = $query->all();
        return $this->renderAjax('most_searched_words', ['results' => $result, 'searchText' => $search_text]);
    }
}
