<?php

namespace amos\sitemanagement\controllers;
use amos\sitemanagement\models\search\SiteManagementElementSearch;
use amos\sitemanagement\models\SiteManagementContainer;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
* This is the class for controller "SiteManagementContainerController".
*/
class SiteManagementContainerController extends base\SiteManagementContainerController
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
                            'associate-element',
                        ],
                        'roles' => ['SITE_MANAGEMENT_ADMINISTRATOR']
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
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionAssociateElement($id){
        $model = $this->findModel($id);

        $elementSearch = new SiteManagementElementSearch();
        $dataProviderElements = $elementSearch->search(\Yii::$app->request->get());
        return $this->render('associate-element', [
            'model' => $model,
            'elementSearch' => $elementSearch,
            'dataProviderElements' => $dataProviderElements
            ]);
    }


}
