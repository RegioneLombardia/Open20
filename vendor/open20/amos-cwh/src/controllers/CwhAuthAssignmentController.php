<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\controllers;

use open20\amos\chat\DataProvider;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\models\CwhAuthAssignment;
use open20\amos\cwh\models\search\CwhAuthAssignmentSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rbac\Item;

/**
 * Class CwhAuthAssignmentController
 * @package open20\amos\cwh\controllers
 */
class CwhAuthAssignmentController extends CrudController
{

    protected $rules = [
        [
            'label' => 'Create',
            'name' => 'CREATE'
        ],
        [
            'label' => 'Validate',
            'name' => 'VALIDATE'
        ],
    ];

    /**
     * @var string $layout
     */
    public $layout = 'list';

    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'update',
                            'create',
                            'index',
                        ],
                        'roles' => ['ADMIN']
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

    public function init()
    {
        $this->setModelObj(new CwhAuthAssignment());
        $this->setModelSearch(new CwhAuthAssignmentSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosCwh::t('amoscwh', '{iconaTabella}' . Html::tag('p', AmosCwh::t('amoscwh', 'Tabella')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
        ]);

        $this->addRules();

        parent::init();
        $this->setUpLayout();
    }

    /**
     * Lists all ComunicazioniDiscussioniCommenti models.
     * @return mixed
     */
    public function actionIndex($layout = null)
    {
        Url::remember();
        $searchModel = new CwhAuthAssignmentSearch();
        /** @var DataProvider $dataProvider */
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $dataProvider->setSort([
            'attributes' => [
                'user_id' => [
                    'asc' => ['user_id' => SORT_ASC],
                    'desc' => ['user_id' => SORT_DESC],
                ],
                'cwhNodi.text' => [
                    'asc' => ['cwh_nodi_id' => SORT_ASC],
                    'desc' => ['cwh_nodi_id' => SORT_DESC],
                ],
                'authItemDescription' => [
                    'asc' => ['item_name' => SORT_ASC],
                    'desc' => ['item_name' => SORT_DESC],
                ]
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Creates a new CwhAuthAssignment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $model = new CwhAuthAssignment();

        if (!Yii::$app->request->post()) {
            $model->load(Yii::$app->request->get());
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'authItems' => $this->getCwhRules(),
            'model' => $model,
        ]);
    }

    protected function getCwhRules()
    {
        $authItems = Yii::$app->getAuthManager()->getPermissions();
        $authItemsArr = [];
        foreach ($authItems as $authItem) {
            /**@var Item $authItem */
            if (strstr($authItem->name, AmosCwh::getInstance()->permissionPrefix)) {
                $authItemsArr[] = $authItem;
            }
        }
        return $authItemsArr;
    }

    /**
     * Creates a new CwhAuthAssignment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'authItems' => $this->getCwhRules(),
                'model' => $model,
            ]);
        }
    }

    private function addRules(){

        $cwhModule = Yii::$app->getModule('cwh');

        $auth = Yii::$app->getAuthManager();
        foreach ((array) $cwhModule->modelsEnabled as $model) {
            foreach ($this->rules as $rule) {
                $permissionName = $cwhModule->permissionPrefix . "_" . $rule['name'] . "_" . $model;
                if (is_null($auth->getPermission($permissionName))) {
                    $permissionCwhModel = $auth->createPermission($permissionName);
                    $permissionCwhModel->description = "{$rule['label']} {$model}";

                    $auth->add($permissionCwhModel);
                }
            }
        }
    }

}
