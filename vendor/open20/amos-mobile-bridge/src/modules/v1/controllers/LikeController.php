<?php
namespace open20\amos\mobile\bridge\modules\v1\controllers;

use open20\amos\core\models\ContentLikes;
use open20\amos\core\models\ModelsClassname;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\httpclient\Exception;
use yii\log\Logger;
use yii\rest\Controller;

class LikeController extends DefaultController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviours = parent::behaviors();

        return ArrayHelper::merge($behaviours, [
                'verbFilter' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'like' => ['post'],
                        'like-me' => ['post'],
                    ],
                ],
        ]);
    }

    /**
     * 
     * @param type $uid
     * @param type $cid
     * @param type $mid
     * @return type
     */
    private function getCounter($model_id = null, $model_class_id = null)
    {
        return ContentLikes::getLikesToCounter(null, $model_id, $model_class_id);
    }

    /**
     * 
     * @return type
     */
    public function actionLike()
    {
        $out = [];

        try {
            //Request params
            $bodyParams = Yii::$app->getRequest()->getBodyParams();

            //Refference namespace
            $classname = $bodyParams['namespace'];
            $cid = $bodyParams['id'];

            if ($classname) {
                $uid = \Yii::$app->getUser()->id;
                $model_class_obj = ModelsClassname::find()->andWhere(['classname' => $classname])->one();
                if (!is_null($model_class_obj)) {
                    $rs = ContentLikes::find()
                        ->andWhere(
                            [
                                'content_id' => $cid,
                                'models_classname_id' => $model_class_obj->id,
                                'user_id' => $uid
                            ]
                        )
                        ->one();

                    if (empty($rs)) {
                        $rs = new ContentLikes();
                        $rs->user_id = $uid;
                        $rs->content_id = $cid;
                        $rs->models_classname_id = $model_class_obj->id;
                        $rs->user_ip = \Yii::$app->request->getUserIP();
                    }
                    $rs->likes = -1 * ($rs->likes - 1);
                    $rs->save();
                    $out = [
                        'tot' => $this->getCounter($cid, $model_class_obj->id),
                        'class' => ($rs->likes == 1) ? 'likeme' : 'notlikeme'
                    ];
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $out;
    }

    /**
     * 
     * @return type
     */
    public function actionLikeMe()
    {
        $out = [];

        try {
            //Request params
            $bodyParams = Yii::$app->getRequest()->getBodyParams();

            //Refference namespace
            $classname = $bodyParams['namespace'];
            $cid = $bodyParams['id'];

            if ($classname) {
                $uid = \Yii::$app->getUser()->id;
                $model_class_obj = ModelsClassname::find()->andWhere(['classname' => $classname])->one();
                if (!is_null($model_class_obj)) {
                    $out = [
                        'tot' => $this->getCounter($cid, $model_class_obj->id),
                        'class' => (ContentLikes::getLikeMe($uid, $cid, $model_class_obj->id) == 1) ? 'likeme' : 'notlikeme'
                    ];
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $out;
    }
}
