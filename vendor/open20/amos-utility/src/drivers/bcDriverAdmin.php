<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\utility\drivers
 * @category   CategoryName
 */

namespace open20\amos\utility\drivers;

use open20\amos\utility\drivers\base\bcDriver;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\models\search\UserProfileSearch;

use open20\amos\admin\widgets\icons\WidgetIconCommunityManagerUserProfiles;
use open20\amos\admin\widgets\icons\WidgetIconInactiveUserProfiles;
use open20\amos\admin\widgets\icons\WidgetIconUserProfile;
use open20\amos\admin\widgets\icons\WidgetIconValidatedUserProfiles;

use yii\db\Query;
/**
 * 
 */
class bcDriverAdmin extends bcDriver
{
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modelClassName = UserProfile::classname();
        $this->widgetIconNames = [
//            WidgetIconCommunityManagerUserProfiles::getWidgetIconName() => WidgetIconCommunityManagerUserProfiles::classname(),
//            WidgetIconInactiveUserProfiles::getWidgetIconName() => WidgetIconInactiveUserProfiles::classname(),
//            WidgetIconUserProfile::getWidgetIconName() => WidgetIconUserProfile::classname(),
//            WidgetIconValidatedUserProfiles::getWidgetIconName() => WidgetIconValidatedUserProfiles::classname(),
        ];
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconCommunityManagerUserProfiles() {
        $query = AmosAdmin::instance()->createModel('UserProfileSearch');
        $dataProvider = $query->searchCommunityManagerUsers([]);
        $this->query = $dataProvider->query;

// Query originale del 12.09.2019
//$params = [];
//        $query = new UserProfileSearch();
//        $dataProvvider = $query->searchCommunityManagerUsers($params);
//
//        $this->setBulletCount(
//            $this->makeBulletCounter(
//                Yii::$app->getUser()->getId(),
//                UserProfile::className(),
//                $dataProvvider->query
//            )
//        );

//        if ($this->disableBulletCounters == false) {
//            $params = [];
//            $query = new UserProfileSearch();
//            $dataProvvider = $query->searchCommunityManagerUsers($params);
//
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    UserProfile::className(),
//                    $dataProvvider->query
//                )
//            );
//            $this->trigger(self::EVENT_AFTER_COUNT);
//        }

    }
    
    public function searchWidgetIconInactiveUserProfiles() {
        $this->query = new Query();
        $this->query
            ->select([UserProfile::tableName().'.id', UserProfile::tableName().'.attivo', UserProfile::tableName().'.deleted_at'])
            ->from(UserProfile::tableName())
            ->where([UserProfile::tableName().'.attivo' => UserProfile::STATUS_DEACTIVATED])
            ->andWhere([UserProfile::tableName().'.deleted_at' => null]);
        
        
//        if ($this->disableBulletCounters == false) {
//            $query = new Query();
//            $query
//                ->select([UserProfile::tableName().'.id', UserProfile::tableName().'.attivo', UserProfile::tableName().'.deleted_at'])
//                ->from(UserProfile::tableName())
//                ->where([UserProfile::tableName().'.attivo' => UserProfile::STATUS_DEACTIVATED])
//                ->andWhere([UserProfile::tableName().'.deleted_at' => null]);
//
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(), UserProfile::className(), $query
//                )
//            );
//        }

    }
    
    public function searchWidgetIconUserProfile() {
        $this->query = new Query();
        $this->query
            ->select([UserProfile::tableName().'.id', UserProfile::tableName().'.attivo', UserProfile::tableName().'.deleted_at'])
            ->from(UserProfile::tableName())
            ->andWhere([
                '>=',
                UserProfile::tableName().'.created_at',
                \Yii::$app->user->getIdentity()->getProfile()->ultimo_logout
            ]);

//        if ($this->disableBulletCounters == false) {
//            $query = new Query();
//            $query
//                ->select([UserProfile::tableName().'.id', UserProfile::tableName().'.attivo', UserProfile::tableName().'.deleted_at'])
//                ->from(UserProfile::tableName())
//                ->andWhere([
//                    '>=', 
//                    UserProfile::tableName().'.created_at',
//                    Yii::$app->getUser()->getIdentity()->getProfile()->ultimo_logout
//                ]);
//
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    UserProfile::className(),
//                    $query
//                )
//            );
//            $this->trigger(self::EVENT_AFTER_COUNT);
//        }

    }
    
    public function searchWidgetIconValidatedUserProfiles() {
        $this->query = new Query();
        $this->query
            ->select([UserProfile::tableName().'.id', UserProfile::tableName().'.attivo', UserProfile::tableName().'.deleted_at'])
            ->from(UserProfile::tableName())
            ->where([UserProfile::tableName().'.attivo' => UserProfile::STATUS_ACTIVE])
            ->andWhere([UserProfile::tableName().'.deleted_at' => null]);
    }
    
}
