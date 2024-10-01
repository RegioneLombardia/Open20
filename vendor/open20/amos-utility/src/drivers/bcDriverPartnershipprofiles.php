<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\utility\drivers
 * @category   CategoryName
 */

namespace open20\amos\utility\drivers;

use open20\amos\utility\drivers\base\bcDriver;

use open20\amos\partnershipprofiles\models\ExpressionsOfInterest;
use open20\amos\partnershipprofiles\models\search\ExpressionsOfInterestSearch;
use open20\amos\partnershipprofiles\models\search\PartnershipProfilesSearch;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;

use open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestCreatedBy;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestReceived;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesAll;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesCreatedBy;

/**
 * 
 */
class bcDriverPartnershipprofiles extends bcDriver
{
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modelClassName = PartnershipProfiles::className(); // put here your model
        $this->widgetIconNames = [
//            WidgetIconExpressionsOfInterestCreatedBy::getWidgetIconName() => WidgetIconExpressionsOfInterestCreatedBy::classname(),
            WidgetIconExpressionsOfInterestReceived::getWidgetIconName() => WidgetIconExpressionsOfInterestReceived::classname(),
            WidgetIconPartnershipProfilesAll::getWidgetIconName() => WidgetIconPartnershipProfilesAll::classname(),
//            WidgetIconPartnershipProfilesCreatedBy::getWidgetIconName() = WidgetIconPartnershipProfilesCreatedBy::classname(),
        ];
    }
    
    /**
     * Put here your search queries
     */
    public function searchWidgetIconExpressionsOfInterestCreatedBy() {
        $search = new ExpressionsOfInterestSearch();
        $this->query = $search->searchCreatedByQuery([]);
        $this->query
            ->andWhere([
            ExpressionsOfInterestSearch::tableName() . '.status' => ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_DRAFT
        ]);

        //        $search = new ExpressionsOfInterestSearch();
//        $query = $search->searchCreatedByQuery([]);
//        $query->andWhere([
//            ExpressionsOfInterestSearch::tableName() . '.status' => ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_DRAFT
//        ]);
//        
//        $this->setBulletCount(
//            $this->makeBulletCounter(
//                Yii::$app->getUser()->getId(),
//                ExpressionsOfInterest::className(),
//                $query
//            )
//        );
    }
    
    public function searchWidgetIconPartnershipProfilesCreatedBy() {
        $search = new PartnershipProfilesSearch();
        $this->query = $search->searchCreatedByQuery([]);
        
        //        $search = new PartnershipProfilesSearch();
//        $this->setBulletCount(
//            $this->makeBulletCounter(
//                Yii::$app->getUser()->getId(),
//                PartnershipProfiles::className(),
//                $search->searchCreatedByQuery([])
//            )
//        );
    }
    
    
    
    public function searchWidgetIconExpressionsOfInterestReceived() {
//        echo 'WidgetIconExpressionsOfInterestReceived' . PHP_EOL;

        $loggedUser = \Yii::$app->user->identity;
        $search = new ExpressionsOfInterestSearch();
        $this->query = $search->searchReceivedQuery([]);
        $this->query->andWhere([
            '>=',
            ExpressionsOfInterest::tableName() . '.created_at',
            $loggedUser->userProfile->ultimo_logout]
        );

//// Query originale del 2018        
//    public function makeBulletCount()
//    {
//        /** @var User $loggedUser */
//        $loggedUser = \Yii::$app->user->identity;
//        $modelSearch = new ExpressionsOfInterestSearch();
//        $query = $modelSearch->searchReceivedQuery([]);
//        $query->andWhere(['>=', ExpressionsOfInterest::tableName() . '.created_at', $loggedUser->userProfile->ultimo_logout]);
//        $count = $query->count();
//        return $count;
//    }
        
        
//        if ($this->disableBulletCounters == false) {
//            $loggedUser = \Yii::$app->user->identity;
//            $search = new ExpressionsOfInterestSearch();
//            $query = $search->searchReceivedQuery([]);
//            $query->andWhere([
//                '>=',
//                ExpressionsOfInterest::tableName() . '.created_at',
//                $loggedUser->userProfile->ultimo_logout]
//            );
//
////            $search->setEventAfterCounter();
//            $query = $search->searchReceivedQuery([]);
//            
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    ExpressionsOfInterest::className(),
//                    $query
//                )
//            );
//                
//            \Yii::$app->session->set('_offQuery', $query);
//            $this->trigger(self::EVENT_AFTER_COUNT);
//        }        
    }
    
    public function searchWidgetIconPartnershipProfilesAll() {
//        echo 'WidgetIconPartnershipProfilesAll' . PHP_EOL;
        
        $this->query = $this->cwhActiveQuery->getQueryCwhAll();

//// Query originale del 2018
//    public function makeBulletCount()
//    {
//        $modelSearch = new PartnershipProfilesSearch();
//        $notifier = \Yii::$app->getModule('notify');
//        $count = 0;
//        if ($notifier) {
//            /** @var \open20\amos\notificationmanager\AmosNotify $notifier */
//            $count = $notifier->countNotRead(\Yii::$app->getUser()->id, PartnershipProfiles::class, $modelSearch->searchAllQuery([]));
//        }
//        return $count;
//    }
//}
        

//        if ($this->disableBulletCounters == false) {
//            $search = new PartnershipProfilesSearch();
//            $search->setEventAfterCounter();
//            $query = $search->searchAllQuery([]);
//            
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    PartnershipProfiles::class,
//                    $query
//                )
//            );
//            
//            \Yii::$app->session->set('_offQuery', $query);
//            $this->trigger(self::EVENT_AFTER_COUNT);
//        }
    }

    /**
     *
     */
    public function searchWidgetIconPartnershipProfilesOwnIntrerest()
    {
        $this->query = $this->cwhActiveQuery->getQueryCwhOwnInterest();
//        pr($this->query->createCommand()->rawSql);
    }

    }
