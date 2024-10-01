<?php

namespace amos\sitemanagement\controllers;
use amos\sitemanagement\widgets\SMContainerWidget;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
* This is the class for controller "SiteManagementTemplateController".
*/
class SiteManagementTemplateController extends base\SiteManagementTemplateController
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
                            'is-template-model-content',
                        ],
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

    /**
     * @param $id
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIsTemplateModelContent($id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = $this->findModel($id);
        $html = SMContainerWidget::renderTemplatePreview($model->id);
        $result = [];
        $result ['html']= $html;
        if(!empty($model->content_model)){
            $result ['isModelContent']= '1';
            $result ['modelContent']= $model->content_model;
        }
        else {
            $result ['isModelContent']= '0';
        }
        return $result;
    }



}
