<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\moodle\controllers
 * @category   CategoryName
 */

namespace open20\amos\moodle\controllers;

use open20\amos\moodle\AmosMoodle;
use open20\amos\moodle\models\MoodleCourse;
use open20\amos\moodle\models\MoodleCategory;
use open20\amos\moodle\models\MoodleUser;
use open20\amos\moodle\utility\MoodleUtility;
use open20\amos\moodle\exceptions\MoodleException;
use open20\amos\core\user\User;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\moodle\utility\EmailUtil;
use Yii;

class CallbackController extends \yii\rest\Controller
{
    /**
     * 
     * @param type $action
     * @return boolean
     * @throws MoodleException
     */
    public function beforeAction($action)
    {
        if (true) { // quello giusto
            $authtoken = Yii::$app->request->post('authtoken');
            $timestamp = Yii::$app->request->post('timestamp');
        } else { // per debug
            $authtoken = Yii::$app->request->get('authtoken');
            $timestamp = Yii::$app->request->get('timestamp');
        }
        //print "authtoken: $authtoken; timestamp: $timestamp.<br />"; //exit;
        if (!$authtoken || !$timestamp || !$this->validateToken($authtoken, $timestamp)) {
            throw new MoodleException('Authentication error');
        } else {
            $moodleAdminUser = User::findByUsername(\Yii::$app->getModule('moodle')->adminUsername);
            Yii::$app->user->login($moodleAdminUser);
            return true;
        }
    }

    /**
     * La funzione viene chiamata da Moodle al momento in cui si fa un'azione
     *    * su un corso
     *    * su un user enrolment a un corso
     *    * su una categoria
     * 
     * Le azioni sono
     *    * creazione
     *    * modifica
     *    * cancellazione
     * 
     * NOn tutte le azioni sono già gestite
     * 
     * @return type
     */
    public function actionEndpoint()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;

        // legge i parametri
        if (true) { // quello giusto
            $objectid = Yii::$app->request->post('objectid');
            $action = Yii::$app->request->post('action');
            $target = Yii::$app->request->post('target');
            $courseid = Yii::$app->request->post('courseid');
            $relateduserid = Yii::$app->request->post('relateduserid'); // the student enrolled
        } else { // per debug
            $objectid = Yii::$app->request->get('objectid');
            $action = Yii::$app->request->get('action');
            $target = Yii::$app->request->get('target');
            $courseid = Yii::$app->request->get('courseid');
            $relateduserid = Yii::$app->request->get('relateduserid'); // the student enrolled
        }

        //print "target: $target; action: $action; objectid: $objectid; courseid: $courseid; relateduserid: $relateduserid.<br />"; exit;

        switch ($target . '_' . $action) {
            case 'course_created':
                $this->courseCreated($courseid);
                break;
            case 'course_updated':
                $this->courseUpdated($courseid);
                break;
            case 'course_deleted':
                $this->courseDeleted($courseid);
                break;
            case 'course_category_created':
                $this->categoryCreated($objectid);
                break;
            case 'course_category_updated':
                $this->categoryUpdated($objectid);
                break;
            case 'course_category_deleted':
                $this->categoryDeleted($objectid);
                break;  // non ancora gestiti
            case 'user_enrolment_created':
                $this->userEnrolmentCreated($courseid, $relateduserid);
                break;
            case 'user_enrolment_updated':
                break;  // non ancora gestiti
            case 'user_enrolment_deleted':
                $this->userEnrolmentDeleted($courseid, $relateduserid);
                break;  // non ancora gestiti
        }
    }

    /**
     * Su Moodle è stato creato un corso.
     * Viene creata la community corrispondente al corso
     * 
     * @return type
     */
    public function courseCreated($moodle_courseid)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;

        $course = MoodleCourse::findOne([
            'moodle_courseid' => $moodle_courseid,
        ]);

        if (is_null($course)) {
            $course = new MoodleCourse();
            $course->moodle_courseid = $moodle_courseid;
            $course->save();
        }
        $prev_moodle_categoryid = $course->moodle_categoryid;
        $course->getMoodleCourseData();

        if ($prev_moodle_categoryid != $course->moodle_categoryid) {
            $course->save(); // salva la categoria
        }

        if (is_null($course->community_id)) {
            $community = MoodleUtility::createCourseCommunity($course);
            if (!is_null($community)) {
                $course->community_id = $community->id;
                $course->save();    // salva l'id della community
                //inserisco nella nuova community tutti i moodle admin
                $moodleAdminUserIds = \Yii::$app->getAuthManager()->getUserIdsByRole(AmosMoodle::MOODLE_ADMIN);
                foreach ($moodleAdminUserIds as $userId) {
                    MoodleUtility::createCommunityUser($course, $userId);
                }
            }
        }

        \Yii::info('Course created Moodle id:' . $moodle_courseid, __METHOD__);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        return [
            'status' => 'OK',
        ];
    }

    /**
     * Su Moodle è stato modificato un corso.
     * Viene creata la community corrispondente al corso
     * 
     * @return type
     */
    public function courseUpdated($moodle_courseid)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;

        $course = MoodleCourse::findOne([
                'moodle_courseid' => $moodle_courseid,
        ]);
        /*  if (is_null($course)) {
          return $this->courseCreated($moodle_courseid);
          } */

        if (!is_null($course)) {
            /**
             * Recupero info sul metodo di pagamento e altre info che vengono specificate 
             * DOPO la creazione del corso stesso
             */
            $course->getMoodleCourseData();
            $course->save();

            $done = MoodleUtility::modifyCourseCommunity($course);

            if ($done) {
                \Yii::info('Course updated Moodle id:' . $moodle_courseid, __METHOD__);
            }
        }

        // è cambiata la categoria del corso su Moodle? La cambio anche sul db Open 
        if ($course->isMoodleCategoryChanged()) {
            if ($course->moodle_categoryid != MoodleCategory::GENERAL_CATEGORY_MOODLE_ID) {//Se La nuova NON è quella Generale
                // aggiunge alla community della nuova categoria tutti gli iscritti al corso
                $users = $course->getEnrolledUsers();
                //pr($users, 'courseUpdated: $users'); exit;

                if (count($users)) {
                    $category = MoodleCategory::findOne([
                            'moodle_categoryid' => $course->moodle_categoryid,
                    ]);
                    if ($category) {
                        foreach ($users as $u) {
                            //print "moodle_userid: ".$u['id'].'<br />';
                            $moodle_user = MoodleUser::findOne([
                                    'moodle_userid' => $u['id'],
                            ]);
                            MoodleUtility::createCommunityUser($category, $moodle_user->user_id);
                        }
                    } // if category
                }   // solo se ci sono users            
            }
            $course->save(); // salva la categoria
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'status' => 'OK',
        ];
    }

    /**
     * Su Moodle è stato cancellato un corso.
     * Viene cancellata la community corrispondente al corso
     * 
     * @return type
     */
    public function courseDeleted($moodle_courseid)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;

        $course = MoodleCourse::findOne([
                'moodle_courseid' => $moodle_courseid,
        ]);

        if (is_null($course)) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'status' => 'OK',
            ];
        }
        MoodleUtility::deleteCommunity($course->community_id);

        $course->delete();    // soft_delete

        \Yii::info('Course deleted Moodle id:' . $moodle_courseid, __METHOD__);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'status' => 'OK',
        ];
    }

    /**
     * Su Moodle un utente viene iscritto a un corso.
     * L'utente viene associato alla community corrispondente al corso
     * 
     * @param type $moodle_courseid
     * @param type $moodle_relateduserid
     * @param type $paypal
     * @return type
     */
    public function userEnrolmentCreated($moodle_courseid, $moodle_relateduserid, $paypal = false, $courseCost = null)
    {
        //print "moodle_courseid: $moodle_courseid. moodle_relateduserid: $moodle_relateduserid.<br />";//exit;

        $course = MoodleCourse::findOne([
            'moodle_courseid' => $moodle_courseid,
        ]);

        if (is_null($course) || is_null($course->community_id)) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'status' => 'KO',
            ];
        }
        $course->getMoodleCourseData();

        $moodle_user = MoodleUser::findOne([
            'moodle_userid' => $moodle_relateduserid,
        ]);

        if (is_null($moodle_user)) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'status' => 'KO',
            ];
        }
        //pr($moodle_user->toArray(), '$moodle_user: '.$moodle_relateduserid);exit;

        MoodleUtility::createCommunityUser($course, $moodle_user->user_id);

        if ($course->moodle_categoryid) {   // il corso è in una categoria?
            $category = MoodleCategory::findOne([
                'moodle_categoryid' => $course->moodle_categoryid,
            ]);
            //pr($category->toArray(), '$category');exit;

            if (is_null($category)) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'status' => 'KO',
                ];
            }

            if (!is_null($category->community_id)) {
                MoodleUtility::createCommunityUser($category, $moodle_user->user_id);

                $moodleCourseId = $course->moodle_courseid;
            }
        }

        // Mando la mail allo studente per comunicare che è stato iscritto al corso e quindi ora può accedere ai contenuti.
        EmailUtil::sendEmailEnrolledInCourse($course, $moodle_user->user_id, $paypal, $courseCost);

        \Yii::info('User enrolled - created - moodle_courseid: ' . $moodle_courseid . ', moodle_relateduserid: ' . $moodle_relateduserid, __METHOD__);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'status' => 'OK',
        ];
    }

    /**
     * Su Moodle un utente viene iscritto a un corso.
     * L'utente viene associato alla community corrispondente al corso
     * 
     * @return type
     */
    public function userEnrolmentDeleted($moodle_courseid, $moodle_relateduserid)
    {

        //print "userEnrolmentDeleted moodle_courseid: $moodle_courseid. moodle_relateduserid: $moodle_relateduserid.<br />";//exit;

        $course = MoodleCourse::findOne([
                'moodle_courseid' => $moodle_courseid,
        ]);
        if (is_null($course) || is_null($course->community_id)) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'status' => 'KO',
            ];
        }
        // non serve $course->getMoodleCourseData();

        $moodle_user = MoodleUser::findOne([
                'moodle_userid' => $moodle_relateduserid,
        ]);
        if (is_null($moodle_user)) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'status' => 'KO',
            ];
        }
        //pr($moodle_user->toArray(), '$moodle_user: '.$moodle_relateduserid.' community_id: ', $course->community_id);//exit;
        $userCommunityMm = CommunityUserMm::findAll([
                'community_id' => $course->community_id,
                'user_id' => $moodle_user->user_id
        ]);
        foreach ($userCommunityMm as $cmm) {
            //pr($cmm->toArray(), 'FindAll');
            $cmm->forceDelete();
        }

        \Yii::info('User unenrolled - delete - moodle_courseid: ' . $moodle_courseid . ', moodle_relateduserid: ' . $moodle_relateduserid, __METHOD__);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'status' => 'OK',
        ];
    }

    /**
     * Su Moodle viene creata una categoria.
     * Viene creata la community corrispondente alla categoria
     * 
     * @return type
     */
    public function categoryCreated($moodle_categoryid)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;

        //print "moodle_categoryid: $moodle_categoryid.<br />"; exit;
        $category = MoodleCategory::findOne([
                'moodle_categoryid' => $moodle_categoryid,
        ]);

        if (is_null($category)) {
            $category = new MoodleCategory();
            $category->moodle_categoryid = $moodle_categoryid;
            $category->save();
        }
        $category->getMoodleCategoryData();
        //pr($category, $category->name);exit;
        if (is_null($category->community_id)) {
            $community = MoodleUtility::createCategoryCommunity($category);
            if (!is_null($community)) {
                $category->community_id = $community->id;
                $category->save();    // salva l'id della community
                //inserisco nella nuova community tutti i moodle admin
                $moodleAdminUserIds = \Yii::$app->getAuthManager()->getUserIdsByRole(AmosMoodle::MOODLE_ADMIN);
                foreach ($moodleAdminUserIds as $userId) {
                    MoodleUtility::createCommunityUser($category, $userId);
                }
            }
        }

        \Yii::info('Category created Moodle id:' . $moodle_categoryid, __METHOD__);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'status' => 'OK',
        ];
    }

    /**
     * Su Moodle viene aggiornata una categoria.
     * Viene modifcata la community corrispondente alla categoria
     * 
     * @return type
     */
    public function categoryUpdated($moodle_categoryid)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;

        //print "moodle_categoryid: $moodle_categoryid.<br />"; exit;
        $category = MoodleCategory::findOne([
                'moodle_categoryid' => $moodle_categoryid,
        ]);
        /*  if (is_null($category)) {
          return $this->categoryCreated($moodle_categoryid);
          } */

        if (!is_null($category)) {
            $done = MoodleUtility::modifyCategoryCommunity($category);

            if ($done) {
                \Yii::info('Category updated Moodle id:' . $moodle_categoryid, __METHOD__);
            }
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'status' => 'OK',
        ];
    }

    /**
     * Su Moodle viene aggiornata una categoria.
     * Viene modifcata la community corrispondente alla categoria
     * 
     * @return type
     */
    public function categoryDeleted($moodle_categoryid)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;

        //print "moodle_categoryid: $moodle_categoryid.<br />"; exit;
        $category = MoodleCategory::findOne([
                'moodle_categoryid' => $moodle_categoryid,
        ]);

        if (is_null($category)) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'status' => 'KO',
            ];
        }
        // non si vuole cancellare la Community MoodleUtility::deleteCommunity($category->community_id);
        $category->delete();    // soft_delete

        \Yii::info('Category deleted Moodle id:' . $moodle_categoryid, __METHOD__);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'status' => 'OK',
        ];
    }

    /**
     * 
     * @param type $token
     * @param type $timestamp
     * @return boolean
     */
    public function validateToken($token, $timestamp)
    {
        $enforceTimeCheck = false; //Attiva la validazione del timestamp non oltre i 600 secondi da ora.
        $maxTimeDiff = 600;
        $timeDiff = time() - $timestamp;

        if ($enforceTimeCheck && ($timeDiff > $maxTimeDiff || $timeDiff < -$maxTimeDiff)) {
            return false;
        } else {
            $challenge = hash('sha256', \Yii::$app->getModule('moodle')->secretKey . $timestamp);
            if ($challenge == $token) {
                return true;
            } else {
                return false;
            }
        }
    }

}
