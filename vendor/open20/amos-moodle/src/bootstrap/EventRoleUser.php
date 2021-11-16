<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\moodle\bootstrap
 * @category   CategoryName
 */

namespace open20\amos\moodle\bootstrap;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Application;
use open20\amos\moodle\models\ServiceCall;
use open20\amos\moodle\models\MoodleUser;
use open20\amos\moodle\models\MoodleCourse;
use open20\amos\moodle\models\MoodleCategory;
use open20\amos\moodle\utility\MoodleUtility;
use open20\amos\core\user\User;
use open20\amos\core\user\AmosUser;
use yii\base\Event;

/**
 * to-do
 */
class EventRoleUser implements BootstrapInterface
{
    private $_startTime;
    private $whiteListRoute;
    private $addAction;

    /**
     * Application-specific roles initialization
     * @uses onApplicationAction
     */
    public function bootstrap($app)
    {
        if (empty($this->whiteListRoute)) {
            $whiteListRoute = \Yii::$app->getModule('moodle')->bootstrapWhiteListRoute;
            $this->setWhiteListRoute($whiteListRoute);
        }

        if (\Yii::$app->getModule('moodle')->enableAddStudentRoleAfterLogin == true) {
            Event::on(\yii\web\User::className(), \yii\web\User::EVENT_AFTER_LOGIN, [$this, 'onAfterLogin']);
        }
        \Yii::$app->on(Application::EVENT_AFTER_ACTION, [$this, 'onAfterAction']);
    }

    /**
     * Application-specific roles initialization
     */
    public function onAfterAction($event)
    {
        $actionId = $event->action->uniqueId;
        try {
            $whiteListRoute = $this->getWhiteListRoute();

            if (in_array($actionId, $whiteListRoute)) {
                if (isset($event->sender) && isset($event->sender->controller)) {
                    $controller = $event->sender->controller;
                    //$actionMethod = $controller->action->actionMethod;
                    $userId     = $controller->actionParams["userId"];
                    $role       = $controller->actionParams["priv"];
                    if ($role == "MOODLE_STUDENT" || $role == "MOODLE_ADMIN") {
                        if ($actionId == "privileges/privileges/enable" /* || $actionId == "amministra-utenti/assignment/assign" */) {
                            $this->enableMoodleUser($userId, $role);
                            $this->clearCache();
                        } else if ($actionId == "privileges/privileges/disable" /* || $actionId == "amministra-utenti/assignment/revoke" */) {
                            $this->disableMoodleUser($userId, $role);
                            $this->clearCache();
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
            
        }
    }

    public function onAfterLogin()
    {
        try {
            $userId = \Yii::$app->user->id;
            $role   = 'MOODLE_STUDENT';
            if (!\Yii::$app->user->can('MOODLE_STUDENT')) {
                $roleObj = \Yii::$app->authManager->getRole($role);
                \Yii::$app->authManager->assign($roleObj, $userId);
                $this->enableMoodleUser($userId, $role);
                $this->clearCache();
            }
        } catch (\Exception $e) {
           
        }
    }

    /**
     * Controlla che esista un utente su Moodle corrispondente a questo utente di Open 2.0.
     * Se non esiste lo crea. Se esiste ed è sospeso su Moodle lo riattiva.
     * @param integer $userId
     * @param string $role
     */
    public function enableMoodleUser($userId, $role)
    {
        $moodleUser  = MoodleUser::findOne([
                'user_id' => $userId,
        ]);
        $serviceCall = new ServiceCall();
        if (is_null($moodleUser)) {//L'utente Moodle non è mai stato creato
            $user        = User::findOne($userId);
            $userProfile = $user->getProfile();

            $username        = "open2_0_".(($user->username) ? $user->username : $user->email);
            $username        = strtolower(str_replace("+", "_", $username));
            $email           = $user->email;
            $moodle_password = Yii::$app->security->generateRandomString(16);

            $nome    = $userProfile->nome;
            $cognome = $userProfile->cognome;

            $moodleUserId = $serviceCall->createUser($username, $moodle_password, $nome, $cognome, $email); //Creo l'utente su Moodle
            if ($moodleUserId != null) {
                //creo l'utente Moodle su Open 2.0
                //pr("sono qui");
                $moodleUser                 = new MoodleUser();
                $moodleUser->moodle_name    = $nome;
                $moodleUser->moodle_surname = $cognome;
                $moodleUser->moodle_email   = $email;
                $moodleUser->user_id        = $userId;
                $moodleUser->moodle_userid  = $moodleUserId;

                $moodleUser->save();

                // il token è creato in modod permanente sia per MOODLE_ADMIN che per MOODLE_STUDENT
                $userToken                = $serviceCall->getUserToken($moodleUser->moodle_userid); //Creo il Token dell'utente su Moodle per poi usarlo nelle chiamate alle API
                $moodleUser->moodle_token = $userToken;
                $moodleUser->save();
            }
        } else {//L'utente Moodle esiste. Lo riattivo
            $serviceCall->enableMoodleUser($moodleUser->moodle_userid);
        }
        if ($role == "MOODLE_ADMIN") {
            $serviceCall->addSiteAdminPermission($moodleUser->moodle_userid);

            // lo metto come membro di tutte le community di categoria e corso
            $moodleCategories = MoodleCategory::find()->all();
            foreach ($moodleCategories as $currCategory) {
                MoodleUtility::createCommunityUser($currCategory, $userId);
            }
            $moodleCourses = MoodleCourse::find()->all();
            foreach ($moodleCourses as $currCourse) {
                MoodleUtility::createCommunityUser($currCourse, $userId);
            }
        }
    }

    /**
     * Controlla se esiste un utente su Moodle corrispondente a questo utente di Open 2.0.
     * Se esiste lo sospende.
     * Nel caso l'utente avesse già anche un altro ruolo relativo a Moodle, la disattivazione dell'utente su Moodle non viene eseguita.
     * @param integer $userId
     * @param string $role
     */
    public function disableMoodleUser($userId, $role)
    {
        $moodleUser = MoodleUser::findOne([
                'user_id' => $userId,
        ]);
        $user       = User::findOne($userId);

        if (!is_null($moodleUser) && !is_null($user)) {
            $toDisable = true;
            $userRoles = Yii::$app->authManager->getRolesByUser($userId);

            $amosUser = new AmosUser(['identityClass' => User::className()]);
            $amosUser->setIdentity($user);


            if ($role == "MOODLE_STUDENT" && $amosUser->can("MOODLE_ADMIN")) {
                $toDisable = false;
            } else if ($role == "MOODLE_ADMIN" && $amosUser->can("MOODLE_STUDENT")) {
                $toDisable = false;
            }

            if ($toDisable) {
                $serviceCall = new ServiceCall();
                $serviceCall->disableMoodleUser($moodleUser->moodle_userid);
            }

            if ($role == "MOODLE_ADMIN" && !$amosUser->can("MOODLE_STUDENT")) {
                // lo tolgo  come membro di tutte le community di categoria e corso
                $moodleCategories = MoodleCategory::find()->all();
                foreach ($moodleCategories as $currCategory) {
                    MoodleUtility::deleteCommunityUser($currCategory, $userId);
                }
                $moodleCourses = MoodleCourse::find()->all();
                foreach ($moodleCourses as $currCourse) {
                    MoodleUtility::deleteCommunityUser($currCourse, $userId);
                }
            }
        }
    }

    /**
     *
     * @param array|string $whiteListRoute
     */
    public function setWhiteListRoute($whiteListRoute)
    {
        if (is_array($whiteListRoute)) {
            $this->whiteListRoute = $whiteListRoute;
        } else if (is_string($whiteListRoute)) {
            $this->whiteListRoute = [$whiteListRoute];
        }
    }

    /**
     *
     * @return array
     */
    public function getWhiteListRoute()
    {
        return $this->whiteListRoute;
    }

    protected function clearCache()
    {
        $cache = \Yii::$app->get('rbacCache');
        if (!empty($cache)) {
            $cache->flush();
        } else {
            $cache = \Yii::$app->get('cache');
            if (!empty($cache)) {
                $cache->flush();
            }
        }
    }
}