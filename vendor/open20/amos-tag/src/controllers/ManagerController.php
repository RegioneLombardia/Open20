<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

namespace open20\amos\tag\controllers;

use yii\base\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class ManagerController extends Controller {

    /**
     * @var string $layout
     */
    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $this->setUpLayout();
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviors = ArrayHelper::merge(
                        parent::behaviors(),
                        [
                            'access' => [
                                'class' => AccessControl::class,
                                'rules' => [
                                    [
                                        'allow' => true,
                                        'actions' => [
                                            'index'
                                        ],
                                        'roles' => ['AMMINISTRATORE_TAG']
                                    ],
                                    [
                                        'allow' => true,
                                        'actions' => [
                                            'autocomplete-free-tag'
                                        ],
                                        'roles' => ['BASIC_USER', 'ADMIN', 'AMMINISTRATORE_TAG']
                                    ]
                                ]
                            ],
                            'verbs' => [
                                'class' => VerbFilter::class,
                                'actions' => [
                                    'delete' => ['post', 'get']
                                ]
                            ]
                        ]
        );

        return $behaviors;
    }

    /**
     * @return mixed
     */
    public function actionIndex() {
        $this->setUpLayout("form");
        return $this->render('index');
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null) {
        if ($layout === false) {
            $this->layout = false;
            return true;
        }

        $module = \Yii::$app->getModule('layout');
        if (empty($module)) {
            $this->layout = '@vendor/open20/amos-core/views/layouts/'
                    . (!empty($layout) ? $layout : $this->layout);
            return true;
        }

        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        return true;
    }

    public function actionAutocompleteFreeTag($id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        $term = filter_input(INPUT_GET, 'term');
        if (!empty($term)) {
            $data = \open20\amos\tag\models\TagFree::find()
                    ->andWhere(['like', 'tag', $term])
                    ->select('tag')
                    ->limit(60)
                    ->column();

            return $data;
        }

        return $out;
    }

}
