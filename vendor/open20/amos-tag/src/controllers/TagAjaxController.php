<?php

namespace open20\amos\tag\controllers;

use open20\amos\core\controllers\BackendController;
use open20\amos\core\forms\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class TagAjaxController extends BackendController
{
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
                            'tag-widget'
                        ],
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
        return $behaviors;
    }

    public function actionTagWidget()
    {
        $get = \Yii::$app->request->get();
        $widgetParams = [];
        $classname = $get['classname'];
        $record_id = $get['record_id'];
        $previousPost = unserialize($get['previousPost']);
        $widgetParams['attribute'] = $get['attribute'];
        $widgetParams['singleFixedTreeId'] = $get['singleFixedTreeId'];

//        $widgetParams['id'] = $get['id'];
        $widgetParams['containerClass'] = $get['containerClass'];
        $widgetParams['name'] = $get['name'];
        $widgetParams['isSearch'] = $this->filterBoolean($get['isSearch']);
        $widgetParams['hideHeader'] = $this->filterBoolean($get['hideHeader']);
        $widgetParams['selectSonsOnly'] = $this->filterBoolean($get['selectSonsOnly']);
//        $widgetParams['isFrontend'] = $get['isFrontend'];

        $widgetParams['moduleCwh'] = \Yii::$app->getModule('cwh');
        $widgetParams['form'] = new ActiveForm();
        $widgetParams['responseAjax'] = true;
        $widgetParams['enableAjax'] = true;


        if (!empty($classname)) {
            if (!empty($record_id)) {
                $model = $classname::findOne($record_id);
                $model->load($previousPost);
                $widgetParams['model'] = $model;
            } else {
                $model = new $classname();
                $model->load($previousPost);
                $widgetParams['model'] = $model;
            }
            if (!empty($previousPost) && !empty($get['isSearch'])) {
                $widgetParams['form_values'] = isset($previousPost[$model->formName()]['tagValues']) ? $previousPost[$model->formName()]['tagValues'] : [];
            }


        }
        // die;

        return $this->renderAjax('tag-widget', ['widgetParams' => $widgetParams]);
    }

    /**
     * @param $val
     * @return bool
     */
    public function filterBoolean($val)
    {
        if ($val == 'true') {
            return true;
        }
        if ($val == 'false') {
            return false;
        }
        return  $val;
    }

}