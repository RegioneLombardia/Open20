<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\projectmanagement\utility
 * @category   CategoryName
 */

namespace open20\amos\moodle\utility;

use open20\amos\moodle\AmosMoodle;
use open20\amos\community\AmosCommunity;
use open20\amos\community\exceptions\CommunityException;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityType;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\moodle\models\MoodleCourse;
use open20\amos\moodle\models\MoodleCategory;
use open20\amos\admin\AmosAdmin;
use open20\amos\moodle\models\ServiceCall;

use Yii;
use yii\log\Logger;
use yii\helpers\ArrayHelper;

/**
 * Class MoodleUtility
 * @package open20\amos\moodle\utility
 */
class MoodleUtility
{
    /**
     * Create a community for a course.
     * @param open20\amos\moodle\models\MoodleCourse $model
     * @return Community|null
     */
    public static function createCourseCommunity($model)
    {
        /** @var AmosCommunity $communityModule */
        $communityModule = Yii::$app->getModule('community');
        $title = ($model->name ? $model->name : '');
        $type = CommunityType::COMMUNITY_TYPE_CLOSED;
        $context = MoodleCourse::className();
        $managerRole = $model->getManagerRole();
        $description = ($model->summary ? $model->summary : '');
        $community = null;
        /*
          print "title: $title, type $type, context: $context, managerRole: $managerRole,
          description: $description, model: $model.<br />";
          print 'pinocchio'; exit;
         * 
         */
        try {
            $model->community_id = $communityModule->createCommunity($title, $type, $context, $managerRole,
                $description, $model); // $managerStatus serve
            if ($model->community_id) {
                $community = Community::findOne($model->community_id);
            }
        } catch (CommunityException $exception) {
            \Yii::getLogger()->log($exception->getMessage(), Logger::LEVEL_ERROR);
        }

        return $community;
    }

    /**
     * Method to create a new community user for the course or category community
     * @param open20\amos\moodle\models\MoodleCourse | open20\amos\moodle\models\MoodleCategory $model
     * @param int $userId
     * @throws CommunityException
     */
    public static function createCommunityUser($model, $userId)
    {
        $communityModule = Yii::$app->getModule('community');
        $baseRole = $model->getBaseRole();
        $idCommunity = $model->community_id;
        $userStatus = CommunityUserMm::STATUS_ACTIVE;
        //print "communityModule: ".get_class($communityModule)."; baseRole: $baseRole; idCommunity: $idCommunity; userStatus: $userStatus.<br />";exit;
        try {
            $communityModule->createCommunityUser($idCommunity, $userStatus, $baseRole, $userId);
        } catch (CommunityException $exception) {
            \Yii::getLogger()->log($exception->getMessage(), Logger::LEVEL_ERROR);
        }
    }

    /**
     * Method to delete a community user for the course or category community
     * @param open20\amos\moodle\models\MoodleCourse | open20\amos\moodle\models\MoodleCategory $model
     * @param int $userId
     * @throws CommunityException
     */
    public static function deleteCommunityUser($model, $userId)
    {
        $communityModule = Yii::$app->getModule('community');
        $idCommunity = $model->community_id;

        try {
            $communityModule->deleteCommunityUser($idCommunity, $userId);
        } catch (CommunityException $exception) {
            \Yii::getLogger()->log($exception->getMessage(), Logger::LEVEL_ERROR);
        }
    }

    /**
     * Create a community for a course.
     * @param open20\amos\moodle\models\MoodleCategory $model
     * @return Community|null
     */
    public static function createCategoryCommunity($model)
    {
        /** @var AmosCommunity $communityModule */
        $communityModule = Yii::$app->getModule('community');
        $title = ($model->name ? $model->name : '');
        $type = CommunityType::COMMUNITY_TYPE_CLOSED;
        $context = Community::className();
        $managerRole = $model->getManagerRole();
        $description = ($model->description ? $model->description : '');
        $community = null;
        //print "title: $title, type $type, context: $context, managerRole: $managerRole,
        //        description: $description, model: $model.<br />";

        try {
            $model->community_id = $communityModule->createCommunity($title, $type, $context, $managerRole,
                $description, $model); // $managerStatus serve
            if ($model->community_id) {
                $community = Community::findOne($model->community_id);
            }
        } catch (CommunityException $exception) {
            \Yii::getLogger()->log($exception->getMessage(), Logger::LEVEL_ERROR);
        }

        return $community;
    }

    /**
     * Modify the data of an existing community.
     * @param open20\amos\moodle\models\MoodleCategory $model
     * @return true if community name or description are changed
     */
    public static function modifyCategoryCommunity($category)
    {

        $community = Community::findOne($category->community_id);
        if (!is_null($community)) {
            if (($community->name != $category->name) ||
                ($community->description != $category->description)) {
                $community->name = $category->name;
                $community->description = $category->description;
                $community->save(false);
                return true;
            }
        }

        return false;
    }

    /**
     * Modify the data of an existing community.
     * @param open20\amos\moodle\models\MoodleCourse $model
     * @return true if community name or description are changed
     */
    public static function modifyCourseCommunity($course)
    {
        $community = Community::findOne($course->community_id);
        if (!is_null($community)) {
            if (($community->name != $course->name) ||
                ($community->description != $course->summary)) {
                $community->name = $course->name;
                $community->description = $course->summary;
                $community->save(false);

                return true;
            }
        }

        return false;
    }

    /**
     * Cancella una community e le relazioni collegate
     * @param type $community_id
     */
    public static function deleteCommunity($community_id)
    {
        if ($community_id) {
            //print "community_id: $community_id.<br />"; exit;

            $userCommunityMm = CommunityUserMm::findAll([
                    'community_id' => $community_id,
            ]);
            foreach ($userCommunityMm as $cmm) {    // cancella tutti quelli che possono vedere la community
                //pr($cmm->toArray(), 'FindAll');
                $cmm->forceDelete();
            }
            $community = Community::findOne($community_id);
            $community->delete();   // soft delete
        }
    }

    /**
     * Ritorna il corso associato a quella community di corso
     * @return type
     */
    public static function getCommunityId()
    {
        $cwh = Yii::$app->getModule("cwh");
        $community = Yii::$app->getModule("community");

        if (isset($cwh) && isset($community)) {
            $cwh->setCwhScopeFromSession();

            if (!empty($cwh->userEntityRelationTable)) {
                $communityId = $cwh->userEntityRelationTable['entity_id'];
                
                return $communityId;
            }
        }
        
        return null;
    }

//
//    public static function getCommunityId()
//    {
//        $cwh = Yii::$app->getModule("cwh");
//        $community = Yii::$app->getModule("community");
//
//        if (isset($cwh) && isset($community)) {
//        //    $cwh->setCwhScopeFromSession();
//
//$scope = $cwh->getCwhScope();
//
//
//pr($scope);
//            if (!empty($scope) && !empty($scope['community'])) {
////                $communityId = $cwh->userEntityRelationTable['entity_id'];
//                
//                    //                return $communityId;
//                    //
//                    return $scope['community'];
//            }
//        }
//        
//        return null;
//    }
//
    
    /**
     * Ritorna il corso associato a quella community di corso
     * @param type $communityId
     * @return type
     */
    public static function getCommunityCourse($communityId = null)
    {
        if (is_null($communityId)) {
            $communityId = self::getCommunityId();
        }
        //pr($communityId );
        if (!is_null($communityId)) {
            $course = MoodleCourse::findOne([
                'community_id' => $communityId,
            ]);
            
            return $course;
        }
        
        return null;
    }

    /**
     * Ritorna la category associata a quella community
     * @param type $communityId
     * @return type
     */
    public static function getCommunityCategory($communityId = null)
    {
        if (is_null($communityId)) {
            $communityId = self::getCommunityId();
        }
        //pr($communityId );
        if (!is_null($communityId)) {
            $category = MoodleCategory::findOne([
                'community_id' => $communityId,
            ]);
            
            return $category;
        }
        
        return null;
    }

    /**
     * 
     * @return type
     */
    public static function getAllMoodleAdminUsers()
    {
        $moodleAdminUserIds = \Yii::$app->getAuthManager()->getUserIdsByRole(AmosMoodle::MOODLE_ADMIN);

        $userProfile = AmosAdmin::instance()->createModel('UserProfile');
        $query = $userProfile::find()
            ->andWhere(['user_id' => $moodleAdminUserIds])
            ->orderBy(['cognome' => SORT_ASC, 'nome' => SORT_ASC]);

        $moodleAdminUsers = $query->all();

        return $moodleAdminUsers;
    }

    /**
     * 
     * @return type
     */
    public static function getAllMoodleAdminUsersEmail()
    {
        $moodleAdminUsers = self::getAllMoodleAdminUsers();
        $emails = [];
        if (!is_null($moodleAdminUsers)) {
            foreach ($moodleAdminUsers as $userRecord) {
                $emails[] = $userRecord->user->email;
            }
        }
        
        return $emails;
    }

    /**
     * 
     * @return type
     */
    public static function getCategoryList()
    {
        $serviceCall = new ServiceCall();

        $allCategoryArray = $serviceCall->getCategoryList();

        //pr($allCategoryArray);

        return $allCategoryArray;
    }
    
    /**
     * 
     * @param type $uid
     * @param type $withImages
     * @param type $onlyCourseIds
     * @return \yii\data\ArrayDataProvider|array
     */
    public static function getUserCoursesList($uid = null, $withImages = true, $onlyCourseIds = false)
    {
        $moodleUser = \open20\amos\moodle\models\MoodleUser::find()
            ->select(['id'])
            ->andWhere(['user_id' => $uid])
            ->asArray()
            ->one();
        
        $coursesList = [];
        if (!empty($moodleUser)) {
            $serviceCall = new ServiceCall();
            $moodleCourse = new MoodleCourse();

            $category = self::getCommunityCategory();
            $categoryId = (is_null($category))
                ? MoodleCategory::GENERAL_CATEGORY_MOODLE_ID
                : $category->moodle_categoryid;

            $allCoursesArray = $serviceCall->getCoursesList($withImages, null, $categoryId);

            if ($uid != null) {
                $serviceCall->setUserMoodle($uid);
            }

            if ($serviceCall->getMoodleUserId()) {;
                $coursesUserEnrolledArray = $serviceCall->getCoursesUserEnrolled();
                $coursesList = $moodleCourse->getCourseList($allCoursesArray, $coursesUserEnrolledArray);
                $coursesListArray = [];
                
                foreach($coursesList as $key => $course) {
                    if ($course->userEnrolled == false) {
                        unset($coursesList[$key]);
                    } else if ($onlyCourseIds == true) {
                        $coursesListArray[] = $course->moodle_courseid;
                    }
                }
            }
        }
        
        if ($onlyCourseIds == false) {
            return new \yii\data\ArrayDataProvider(['allModels' => $coursesList]);
        }
        
        return $coursesListArray;
    }
    
    /**
     * Return just only id and displayname of a Moodle course
     */
    public static function getAllCoursesList() {
        $moodleObj = new MoodleCourse();
        $serviceCall = new ServiceCall();

        $withImages = false;

        $category = self::getCommunityCategory();
        $categoryId = (is_null($category))
            ? MoodleCategory::GENERAL_CATEGORY_MOODLE_ID
            : $category->moodle_categoryid;

        $allCourses = $serviceCall->getCoursesList($withImages, null, $categoryId);

        $coursesList = $moodleObj->getCourseList($allCourses, []);
        $tmp = [];
        foreach($coursesList as $c) {
            $tmp[] = ['id' => $c->moodle_courseid, 'displayname' => $c->name];
        }

        if ($allCourses) {
            $coursesList = ArrayHelper::map(
                $tmp,
                'id',
                'displayname'
            );
        }

        return $coursesList;
    }

    
}