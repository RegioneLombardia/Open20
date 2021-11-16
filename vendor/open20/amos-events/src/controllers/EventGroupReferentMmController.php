<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\controllers
 */

namespace open20\amos\events\controllers;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\user\AmosUser;
use open20\amos\core\user\User;
use open20\amos\events\models\EventGroupReferentMm;
use open20\amos\events\utility\EventMailUtility;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class EventGroupReferentMmController
 * This is the class for controller "EventGroupReferentMmController".
 * @package open20\amos\events\controllers
 */
class EventGroupReferentMmController extends \open20\amos\events\controllers\base\EventGroupReferentMmController
{


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'assign-operator-role',
                        ],
                        'roles' => ['EVENTS_ADMINISTRATOR', 'SUPER_USER_EVENT', 'EVENT_DG']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'complete-course',
                        ],
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAssignOperatorRole($id)
    {
        $this->model = $this->findModel($id);
        $this->model->user_id;
        $profile = UserProfile::find()->andWhere(['user_id' => $this->model->user_id])->one();

        if($this->model->status == EventGroupReferentMm::STATUS_ACTIVE){
            \Yii::$app->session->addFlash('warning', "L'Operatore è stato già attivato");
            return $this->redirect(['/events/event-group-referent-mm/index', 'group_id' => $this->model->event_group_referent_id]);
        }
        $currentRole = EventGroupReferentMm::getUserRole($this->model, true);
//        if($this->model->status != EventGroupReferentMm::STATUS_REQUEST_ACTIVATION){
//            \Yii::$app->session->addFlash('danger', "Non è possibile attivare l'operatore");
//            return $this->redirect(['/events/event-group-referent-mm/index', 'group_id' => $this->model->event_group_referent_id]);
//        }

        if (\Yii::$app->request->post()) {
            $role = \Yii::$app->request->post('role');
            if (!empty($role)) {
                if (!\Yii::$app->authManager->checkAccess($this->model->user_id, $role)) {
                    $roleobject = \Yii::$app->authManager->getRole($role);
                    if ($roleobject) {
                        if(\Yii::$app->authManager->assign($roleobject, $this->model->user_id)){
                            \Yii::$app->authManager->deleteAllCache();
                            \Yii::$app->session->addFlash('success', "Ruolo già assegnato correttamente utente.");
                            $this->model->status = EventGroupReferentMm::STATUS_ACTIVE;
                            $this->model->save(false);
                            if($roleobject->name == 'EVENT_DG'){
                                $rolename = \Yii::t('amoscore', 'Dg');
                            }else {
                                $rolename = \Yii::t('amoscore', 'Operatore');
                            }
                            EventMailUtility::sendEmailOperatorEnabledConfirmed($this->model, $this->model->user_id, $rolename, $role, $roleobject->name);
                            return $this->redirect(['/events/event-group-referent-mm/index', 'group_id' => $this->model->event_group_referent_id]);
                        }
                    }
                } else {
                    $this->model->status = EventGroupReferentMm::STATUS_ACTIVE;
                    $this->model->save(false);
                    $is_ok = \Yii::$app->request->post('is_ok');
                    if(!empty($is_ok)){
                        \Yii::$app->session->addFlash('success', \Yii::t('amosevents',"Utente Attivato"));
                        return $this->redirect(['/events/event-group-referent-mm/index', 'group_id' => $this->model->event_group_referent_id]);
                    }

                    \Yii::$app->session->addFlash('danger', "Ruolo già assegnato a questo utente.");
                }
            }
        }
        return $this->render('assign-operator-role', ['model' => $this->model, 'profile' => $profile,'currentRole' => $currentRole]);
    }

    /**
     * @return string
     */
    public function actionCompleteCourse(){
        $module = \Yii::$app->getModule('events');
        $moduleMooddle = \Yii::$app->getModule('moodle');
        $loggedUser = User::findOne(\Yii::$app->user->id);
        if($module && $moduleMooddle) {
            $operatorCandidate = $module->operatorCandidate;
            if ($operatorCandidate && !empty($operatorCandidate['courseid'])) {
                $serviceCall = new \open20\amos\moodle\models\ServiceCall();
//                $user = $serviceCall->getMoodleUser($loggedUser->email);
//                pr($user);die;

                $eventRoleUse = new \open20\amos\moodle\bootstrap\EventRoleUser();
                $eventRoleUse->enableMoodleUser(\Yii::$app->user->id, 'MOODLE_STUDENT');
                $courseid = $operatorCandidate['courseid'];

                $course = \open20\amos\moodle\models\MoodleCourse::find()
                    ->andWhere(['moodle_courseid' => $courseid])->one();
                    ;
                if (!empty($course)) {
                    $moodleCourseId = $course->moodle_courseid;
                    $selfEnrollment = false; //se l'utente può iscriversi al corso da solo

                    $userNotValid = true;
                    $userToEnrol = User::findOne(\Yii::$app->user->id);
                    if ($userToEnrol) {
                        $amosUser = new AmosUser(['identityClass' => User::className()]);
                        $amosUser->setIdentity($userToEnrol);
                        if ($amosUser->can(\open20\amos\moodle\AmosMoodle::MOODLE_STUDENT)) {
                            $userNotValid = false;
                            $serviceCall->setUserMoodle(\Yii::$app->user->id);
                        }
                    }

                    //se l'utente è iscritto al corso
                    try {
                        $courseEnrolled = $serviceCall->isUserEnrolledInCourse($moodleCourseId);

                        if (!$courseEnrolled) {
                            $selfEnrollment = $serviceCall->selfEnrollmentActive($moodleCourseId);
                        }
                    }catch (\open20\amos\moodle\exceptions\MoodleException $e){

                    }
                    return $this->render('complete-course', [
                        'course_id' => $course->id,
                        'selfEnrollment' => $selfEnrollment,
                        'courseEnrolled' => $courseEnrolled,
                        'course' => $course
                    ]);
                }
            }
        }
        return $this->redirect('/dashboard');
    }

}
