<?php
namespace open20\amos\mobile\bridge\modules\v1\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\favorites\AmosFavorites;
use open20\amos\favorites\widgets\FavoriteWidget;
use InvalidArgumentException;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\httpclient\Exception;
use yii\log\Logger;

class FavoriteController extends DefaultController
{

    const FAVORITE_OFF = 0;
    const FAVORITE_ON = 1;

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
                    'favorite' => ['post'],
                ],
            ],
        ]);
    }

    /**
     * 
     * @return type
     */
    public function actionFavorite()
    {
        $notify = Yii::$app->getModule('notify');
        $toret = [
            'status' => null,
            'messages' => null,
            'data' => null,
        ];
        
        try {
            //Request params
            $bodyParams = Yii::$app->getRequest()->getBodyParams();

            //Refference namespace
            $classname = $bodyParams['namespace'];
            $cid = $bodyParams['id'];
            $userId = $bodyParams['user_id'];
            
            if ($classname) { 

                if (isset($userId) && !empty($userId)) {
                    $userModel = AmosAdmin::instance()->createModel('User');
                    if (!empty($userModel::findOne(['id' => $userId]))){
                        $uid = $userId;
                    } else {
                        throw new InvalidArgumentException('Parametro user_id non è associato a nessun utente!');
                    }
                } else {
                    $uid = \Yii::$app->getUser()->id;
                }

                $query = $classname::find()->andWhere(['id' => $cid]);
                $model_class_obj = $query->one(); 

                if (!is_null($model_class_obj)) {
                    $alreadyFavorite = $notify->isFavorite($model_class_obj, $uid);
                    $ok = false;
                    if ($alreadyFavorite) {
                        $ok = $notify->favouriteOff($uid, $classname, $cid);
                    } else {
                        $ok = $notify->favouriteOn($uid, $classname, $cid);
                    }

                    if ($ok) {
                        $toret['status'] = 'ok';
                    } else {
                        $toret['status'] = 'ko';
                        $toret['messages'][] = 'Impossibile cambiare stato...';
                    }
                    $toret['data'] = $notify->isFavorite($model_class_obj, $uid);

                    return $toret;
                } else {
                    throw new InvalidArgumentException('Non è stato possibile individuare la preferenza da memorizzare');
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);

            $toret['status'] = 'ko';
            $toret['messages'][] = $ex->getMessage();
            return $toret;
        }
        
    }

    /**
     * 
     * @return type
     */
    public function actionIsFavorite($namespace, $userId, $id)
    {
        $notify = Yii::$app->getModule('notify');
        $toret = [
            'status' => null,
            'messages' => null,
            'data' => null,
        ];
        
        try {
            if ($namespace) { 

                if (isset($userId) && !empty($userId)) {
                    $userModel = AmosAdmin::instance()->createModel('User');
                    if (!empty($userModel::findOne(['id' => $userId]))){
                        $uid = $userId;
                    } else {
                        throw new InvalidArgumentException('Parametro user_id non è associato a nessun utente!');
                    }
                } else {
                    $uid = \Yii::$app->getUser()->id;
                }

                $query = $namespace::find()->andWhere(['id' => $id]);
                $model_class_obj = $query->one(); 
                if (!is_null($model_class_obj)) {
                    $toret['status'] = 'ok';
                    $toret['data'] = $notify->isFavorite($model_class_obj, $uid);
                    return $toret;
                } else {
                    throw new InvalidArgumentException('Non è stato possibile individuare la preferenza');
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);

            $toret['status'] = 'ko';
            $toret['messages'][] = $ex->getMessage();
            return $toret;
        }
        
    }



}
