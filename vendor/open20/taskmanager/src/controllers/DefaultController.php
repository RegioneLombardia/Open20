<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\taskmanager
 * @category   CategoryName
 */

namespace open20\amos\taskmanager\controllers;

use open20\amos\taskmanager\TaskManager;
use open20\amos\taskmanager\models\TaskManagerJob;
use open20\amos\core\controllers\BaseController;
use open20\amos\core\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class DefaultController extends BaseController
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setModel(new TaskManagerJob);
        parent::init();
    }

    public function behaviors()
    {
        return ArrayHelper::merge(
                parent::behaviors(),
                [
                    'access' => [
                        'class' => AccessControl::className(),
                        'rules' => [
                            [
                                'allow' => true,
                                'actions' => [
                                    'index',
                                ],
                                'roles' => ['TASKMANAGER_USER']
                            ],
                            [
                                'allow' => true,
                                'actions' => [
                                    'all',
                                ],
                                'roles' => ['TASKMANAGER_MANAGER']
                            ],
                            [
                                'allow' => true,
                                'actions' => [
                                    'delete',
                                ],
                                'roles' => ['TASKMANAGER_USER']
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
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = TaskManager::t('amostaskmanager', 'running_commands');
        } else {
            $titleSection    = TaskManager::t('amostaskmanager', 'my_running_commands');
            $subTitleSection = Html::tag('p', TaskManager::t('amostaskmanager', 'running_commands_description'));
        }

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'taskmanager',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'hideCreate' => true,
            'hideManage' => true,
            'hideSecondAction' => true,
            'hideViewAll' => false
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        $this->setUpLayout('@vendor/open20/amos-layout/src/views/layouts/main');
        $this->view->params['containerFullWidth'] = true;

        return true;
    }

    /**
     * Index view, rendered with data provider, filtered by User ID and status
     *
     */
    public function actionIndex()
    {

        $model = TaskManagerJob::find()->andWhere(['created_by' => \Yii::$app->getUser()->getId()])->orderBy('created_at DESC');
        if (\Yii::$app->request->isPost) {
            $filter = \Yii::$app->request->post();
            $model->andFilterWhere(['status' => $filter['status']])->andFilterWhere(['module' => $filter['module']]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        return $this->render('index',
                [
                    'filter' => $filter,
                    'statuses' => $this->taskStatuses,
                    'modules' => $this->getTaskModules(\Yii::$app->getUser()->getId()),
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * All view, rendered with data provider, filtered by status
     *
     */
    public function actionAll()
    {

        $model = TaskManagerJob::find()->orderBy('created_at DESC');
        if (\Yii::$app->request->isPost) {
            $filter = \Yii::$app->request->post();
            $model->andFilterWhere(['status' => $filter['status']])->andFilterWhere(['module' => $filter['module']]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        return $this->render('index',
                [
                    'filter' => $filter,
                    'statuses' => $this->taskStatuses,
                    'modules' => $this->taskModules,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelete($id)
    {
        $model = TaskManagerJob::findOne($id);
        if ($model) $model->delete();

        if (!$model->hasErrors()) {
            \Yii::$app->getSession()->addFlash('success', TaskManager::t('amostaskmanager', 'job_deleted_successfully'));
        } else {
            \Yii::$app->getSession()->addFlash('danger', TaskManager::t('amostaskmanager', 'error_deleting_job'));
        }
        return $this->redirect(Url::previous());
    }

    public function getTaskStatuses()
    {
        return [
            null => TaskManager::t('amostaskmanager', 'STATUS_ALL'),
            TaskManagerJob::STATUS_SCHEDULED => TaskManager::t('amostaskmanager', 'STATUS_SCHEDULED'),
            TaskManagerJob::STATUS_STARTED => TaskManager::t('amostaskmanager', 'STATUS_STARTED'),
            TaskManagerJob::STATUS_COMPLETED => TaskManager::t('amostaskmanager', 'STATUS_COMPLETED'),
            TaskManagerJob::STATUS_FAILED => TaskManager::t('amostaskmanager', 'STATUS_FAILED')
        ];
    }

    public function getTaskModules($userId = null)
    {
        $modules = TaskManagerJob::find()->select('module')->andFilterWhere(['created_by' => $userId])->distinct()->asArray()->all();
        $modules = ArrayHelper::map($modules, 'module', 'module');
        $modules = array(null => TaskManager::t('amostaskmanager', 'all_modules')) + $modules;
        return $modules;
    }
}
?>
