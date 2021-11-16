<?php

namespace amos\sitemanagement\controllers;
use amos\sitemanagement\models\SiteManagementElement;
use amos\sitemanagement\widgets\SMContainerWidget;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
* This is the class for controller "SiteManagementElementController".
*/
class SiteManagementElementController extends base\SiteManagementElementController
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
                            'show-preview',
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

    public function actionShowPreview($id) {
        /** @var  $model SiteManagementElement */
        $model = $this->findModel($id);
        $divopen = "<div class=\"col-xs-12 nop sitemanagement-template-preview\" id=\"preview-template\">";


        return $divopen. SMContainerWidget::renderElementPreview($model)."</div>";

//        $template = $model->siteManagementTemplate;
//        if($template){
//            $widgetsValues = [];
//            $vals = $model->siteManagementContainerElemeFieldsVals;
//            foreach ($vals as $val){
//                if($val->siteManagementFieldsType->type == 'file'){
//                    $widgetsValues[$val->siteManagementFieldsType->name] = !empty($val->getAttachFiles()) ? $val->getAttachFiles()->getWebUrl() : '';
//                } else {
//                    $widgetsValues[$val->siteManagementFieldsType->name] = $val->value;
//                }
//            }
//        }

    }
}
