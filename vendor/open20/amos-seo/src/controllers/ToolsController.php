<?php

/*
 * To change this proscription header, choose Proscription Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace open20\amos\seo\controllers;

use open20\amos\seo\models\SeoData;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Tools for SEO
 *
 */
class ToolsController extends Controller {

    /**
     * Generates missing pretty urls for every object of the provided class
     * 
     * @param type $modelSearchClass
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
//                        'actions' => [
//                            'index',
//                            'generateMissingPrettyUrls'
//                        ],
                        'roles' => ['ADMIN']
                    ],
                ],
            ],
        ];
    }

    public $layout = "main";

    public function actionIndex() {
//        $modules = [];
//        foreach(Yii::$app->getModule('seo')->modulesEnabled as $module){
//            if (in_array('open20\\amos\\core\\interfaces\\CmsModuleInterface', class_implements($module))) {
//                $modules[] = $module;
//            }
//        }
        return $this->render('index',[
            'modules' => Yii::$app->getModule('seo')->modulesEnabled
        ]);
    }

    public function actionGenerateMissingPrettyUrls($moduleName) {
        $module = Yii::$app->getModule($moduleName);
        $errorMessage = null;
        if (in_array('open20\\amos\\core\\interfaces\\CmsModuleInterface', class_implements($module))) {
            $searchModelClass = $module::getModelSearchClassName();
            $searchModel = new $searchModelClass;
            $dataProvider = $searchModel->cmsSearch([], null);
            $modelsCount = 0;
            foreach ($dataProvider->models as $model) {
                $seoData = SeoData::findOne([
                            'classname' => $model->className(),
                            'content_id' => $model->id
                ]);

                if (is_null($seoData)) {
                    $seoData = new SeoData();
                    $seoData->classname = $model->className();
                    $seoData->content_id = $model->id;
                    $seoData->pretty_url = $seoData->generateUniqueSeoSlug($model[$model->titleAttribute]);
                    $seoData->created_by = $model->created_by;
                    $seoData->save();
                    $modelsCount++;
                }
            }
        } else {
            $errorMessage = \open20\amos\seo\AmosSeo::t('amosseo','Modulo non valido');
        }
        
        return $this->render('generateMissingPrettyUrls',[
            'modelsCount' => $modelsCount,
            'errorMessage' => $errorMessage
        ]);
    }

}
