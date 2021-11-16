<?php

namespace open20\amos\mobile\bridge\modules\v1\controllers;

use open20\amos\core\models\ContentShared;
use open20\amos\core\models\ModelsClassname;
use open20\amos\events\models\Event;
use open20\amos\events\utility\EventsUtility;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\httpclient\Exception;
use yii\rest\Controller;
use yii\swiftmailer\Logger;

class ShareEventPlatformController extends DefaultController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviours = parent::behaviors();

        return ArrayHelper::merge($behaviours,
                [
                'verbFilter' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'share' => ['post'],
                        'share-url' => ['post'],
                    ],
                ],
        ]);
    }

    /**
     * 
     * @return boolean
     */
    public function actionShare()
    {
        $result = ['url' => ''];

        try {
            //Request params
            $bodyParams = Yii::$app->getRequest()->getBodyParams();

            //Refference namespace
            $classname = $bodyParams['namespace'];
            $id        = $bodyParams['id'];

            if ($classname) {
                $model = $classname::findOne($id);
                if (!is_null($model)) {
                    if(!strcmp(Event::class, $classname)) {
                        $result = ['url' => EventsUtility::getUrlLanding($model)];
                    } else {
                        $contentShared = ContentShared::find()
                                ->innerJoinWith('modelsClassname')
                                ->andWhere(['classname' => $classname, 'content_id' => $id])->one();

                        if (empty($contentShared)) {
                            $modelClassname = ModelsClassname::findOne(['classname' => $classname]);
                            if ($modelClassname) {
                                $contentShared                      = new ContentShared();
                                $contentShared->models_classname_id = $modelClassname->id;
                                $contentShared->content_id          = $id;
                                $contentShared->save();
                                if ($contentShared->hasErrors()) {
                                    return $this->echoError($contentShared);
                                }
                            }
                        }
                        $result = ['url' => $this->getPublicUrl($model)];
                    }
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $result;
    }

    /**
     * 
     * @return array
     */
    private function echoError($record)
    {
        $errors     = $record->getErrors();
        $firstError = reset($errors);

        return [
            'error' => true,
            'message' => reset($firstError),
            'more' => $errors
        ];
    }

    /**
     * 
     */
    public function actionShareUrl()
    {
        $url = ['url' => ''];

        try {
            //Request params
            $bodyParams = Yii::$app->getRequest()->getBodyParams();

            //Refference namespace
            $classname = $bodyParams['namespace'];
            $id        = $bodyParams['id'];
            $model     = $classname::findOne($id);
            if (!is_null($model)) {
                if ($this->isContentShared($model)) {
                    $url = ['url' => $this->getPublicUrl($model)];
                }
            }
            return $url;
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $url;
    }

    /**
     * 
     * @param type $model
     * @return boolean
     */
    private function isContentShared($model)
    {
        $obj = $model;
        if ($obj) {
            $classname     = get_class($obj);
            $contentShared = ContentShared::find()
                    ->innerJoinWith('modelsClassname')
                    ->andWhere(['classname' => $classname, 'content_id' => $obj->id])->one();

            if ($contentShared) {
                return true;
            }
        }

        return false;
    }

    /**
     * 
     * @param type $model
     * @return type
     */
    private function getPublicUrl($model)
    {
        $view_url = $model->getViewUrl();
        return substr($view_url, 0, strrpos($view_url, "/")).'/public'."?id=".$model->id;
    }
}