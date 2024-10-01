<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notify
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\base;

use open20\amos\core\record\Record;
use open20\amos\core\user\AmosUser;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\models\Notification;
use open20\amos\notificationmanager\models\NotificationChannels;
use open20\amos\notificationmanager\models\NotificationsRead;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Query;
use yii\helpers\VarDumper;
use yii\log\Logger;

/**
 * Class NotifierRepository
 * @package open20\amos\notificationmanager\base
 */
class NotifierRepository
{
    /**
     * Method to count unviewed notification.
     *
     * @param int|null $uid
     * @param string $class_name
     * @param null $externalquery
     * @return false|int|null|string
     */
    public function countNotRead($uid, $class_name, $externalquery = null)
    {
        $result = 0;
        $classObj = new $class_name;
        try {
            $subquery = new Query();
            $subquery
                ->distinct()
                ->select('id')
                ->from(['subquery' => $externalquery]);
            
            $query = new Query();
            $query
                ->distinct()
                ->select('count(*) as number')
                ->from(Notification::tableName() . ' a')
                ->leftJoin(NotificationsRead::tableName() . ' b', 'a.id = b.notification_id and b.user_id = ' . $uid)
                ->leftJoin($classObj->tableName(), 'a.content_id = ' . $classObj->tableName() . '.id')
                ->andWhere([
                    'b.user_id' => null,
                    'a.channels' => NotificationChannels::CHANNEL_READ,
                    'a.class_name' => $class_name,
                    $classObj->tableName() . '.id' => $subquery
                ]);
            $result = $query->scalar();
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        
        return $result;
    }
    
    /**
     * Method to verify if model is Read
     *
     * @param ActiveRecord $model
     * @param int|null $uid
     * @return boolean
     */
    public function modelIsRead($model, $uid = null)
    {
        $result = 0;
        $userId = $uid;
        
        try {
            if ($uid === null) {
                $userId = Yii::$app->user->identity->profile->id;
            }
            
            $query = new Query();
            $query
                ->distinct()
                ->select('count(*) as number')
                ->from(Notification::tableName() . ' a')
                ->innerJoin(
                    NotificationsRead::tableName() . ' b', 
                    'a.id = b.notification_id and b.user_id = ' . $userId . ' and a.content_id = ' . $model->id
                )
                ->andWhere([
                    'a.channels' => NotificationChannels::CHANNEL_READ_DETAIL,
                    'a.class_name' => get_class($model)
                ]);
            $result = $query->scalar();
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        
        return $result > 0;
    }
    
    /**
     * @param int|null $uid
     * @param $class_name
     * @param null $externalquery
     * @param NotificationChannels $channel
     * @return bool
     */
    public function notificationOff($uid, $class_name, $externalquery = null, $channel = 0x0001)
    {
        $allOk = true;
        try {
           $classObj = new $class_name;
            $subquery = new Query();
            $subquery->distinct()
                ->select('id')
                ->from(['subquery' => $externalquery]);
            $subquery = $subquery->all();

            $query = new Query();
            $query
                ->distinct()
                ->select('a.id as notification_id')
                ->from(Notification::tableName().' a')
                ->leftJoin(NotificationsRead::tableName().' b', 'a.id = b.notification_id and b.user_id = '.$uid)
                ->leftJoin($classObj->tableName(), 'a.content_id = '.$classObj->tableName().'.id')
                ->andWhere([
                    'b.user_id' => null,
                    'a.channels' => $channel,
                    'a.class_name' => $class_name,
                    $classObj->tableName().'.id' => \yii\helpers\ArrayHelper::map($subquery, 'id', 'id')
            ]);


            $result = $query->all();
            foreach ($result as $model) {
                /** @var NotificationsRead $read */
                $read = AmosNotify::instance()->createModel('NotificationsRead');
                $read->user_id = $uid;
                $read->notification_id = $model['notification_id'];
                $ok = $read->save();
                if (!$ok) {
                    $allOk = false;
                }
            }
        } catch (\yii\base\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
            $allOk = false;
        }
        
        return $allOk;
    }
    
    /**
     * @param int $uid
     * @param string $class_name
     * @param null|Query|ActiveQuery $externalquery
     * @param int $channel
     * @return bool
     */
    public function notificationOn($uid, $class_name, $externalquery = null, $channel = 0x0001)
    {
        $allOk = true;
        try {
            $classObj = new $class_name;
            $subquery = new Query();
            $subquery->distinct()->select('id')->from(['subquery' => $externalquery]);
            
            $query = new Query();
            $query
                ->distinct()
                ->select('a.id as notification_id')
                ->from(Notification::tableName() . ' a')
                ->leftJoin(
                    NotificationsRead::tableName() . ' b', 
                    'a.id = b.notification_id and b.user_id = ' . $uid
                )
                ->leftJoin(
                    $classObj->tableName(), 
                    'a.content_id = ' . $classObj->tableName() . '.id'
                )
                ->andWhere([
                    'b.user_id' => $uid,
                    'a.channels' => $channel,
                    'a.class_name' => $class_name,
                    $classObj->tableName() . '.id' => $subquery
                ]);
            
            $result = $query->all();
            foreach ($result as $model) {
                /** @var NotificationsRead $notificationsReadModel */
                $notificationsReadModel = AmosNotify::instance()->createModel('NotificationsRead');
                $read = $notificationsReadModel::findOne([
                    'user_id' => $uid,
                    'notification_id' => $model['notification_id']
                ]);
                $ok = $read->delete();
                if (!$ok) {
                    $allOk = false;
                }
            }
        } catch (\yii\base\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        
        return $allOk;
    }
    
    /**
     * @param int $uid
     * @param string $class_name
     * @param null $externalquery
     * @return bool
     */
    public function favouriteOn($uid, $class_name, $contentId)
    {
        /** @var Record $class_name */
        $externalquery = $class_name::find()->andWhere(['id' => $contentId]);
        
        return $this->notificationOff($uid, $class_name, $externalquery, NotificationChannels::CHANNEL_FAVOURITES);
    }
    
    /**
     * @param int $uid
     * @param string $class_name
     * @param null $externalquery
     * @return bool
     */
    public function favouriteOff($uid, $class_name, $contentId)
    {
        /** @var Record $class_name */
        $externalquery = $class_name::find()->andWhere(['id' => $contentId]);
        
        return $this->notificationOn($uid, $class_name, $externalquery, NotificationChannels::CHANNEL_FAVOURITES);
    }
    
    /**
     * @param Record $model
     * @param int|null $uid
     * @return bool
     */
    public function isFavorite($model, $uid = null)
    {
        $result = 0;
        $userId = $uid;
        
        try {
            if ($uid === null) {
                /** @var AmosUser $user */
                $user = Yii::$app->user->identity;
                $userId = $user->profile->id;
            }
            
            $query = new Query();
            $query
                ->distinct()
                ->select('count(*) as number')
                ->from(Notification::tableName() . ' a')
                ->innerJoin(
                    NotificationsRead::tableName() . ' b', 
                    'a.id = b.notification_id and b.user_id = ' . $userId . ' and a.content_id = ' . $model->id
                )
                ->andWhere([
                    'a.channels' => NotificationChannels::CHANNEL_FAVOURITES, 
                    'a.class_name' => get_class($model)]
                );
            
            $result = $query->scalar();
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        
        return ($result > 0);
    }

    /**
     * @param Record $model
     * @param int|null $uid
     * @return bool
     */
    public function getAllFavourites($class_name, $uid = null)
    {
        $result = 0;
        $userId = $uid;
        
        try {
            if ($uid === null) {
                /** @var AmosUser $user */
                $user = Yii::$app->user->identity;
                $userId = $user->profile->id;
            }
            
            $query = new Query();
            $query
                ->distinct()
                ->select('a.content_id')
                ->from(Notification::tableName() . ' a')
                ->innerJoin(
                    NotificationsRead::tableName() . ' b', 
                    'a.id = b.notification_id and b.user_id = ' . $userId . ' '
                )
                ->andWhere([
                    'a.channels' => NotificationChannels::CHANNEL_FAVOURITES, 
                    'a.class_name' => $class_name]
                );
                
            $result = $query->all();
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        
        return $result;
    }

}
