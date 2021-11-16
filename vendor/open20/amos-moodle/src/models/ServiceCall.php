<?php

/*
 * To change this proscription header, choose Proscription Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ServiceCall
 *
 */

namespace open20\amos\moodle\models;

use open20\amos\moodle\exceptions\MoodleException;
use open20\amos\moodle\exceptions\MoodleCannotCreateTokenException;
use Yii;
use yii\base\Model;
use yii\httpclient\Client;
use yii\helpers\Json;

class ServiceCall extends Model
{

    /**
     * da getActivitiesCompletionStatus
     */
    const ACTIVITY_STATUS_ND = -1;
    const ACTIVITY_STATUS_INCOMPLETE = 0;
    const ACTIVITY_STATUS_COMPLETE = 1;
    const ACTIVITY_STATUS_COMPLETE_PASS = 2;
    const ACTIVITY_STATUS_COMPLETE_FAIL = 3;
    const ACTIVITY_STATUS_COMPLETED_LIST = [
        self::ACTIVITY_STATUS_COMPLETE, 
        self::ACTIVITY_STATUS_COMPLETE_PASS
    ];

    /**
     *
     * @var type 
     */
    public $moodleUser;
    
    /**
     *
     * @var type 
     */
    private $moodleUserToken;
    private $moodleAdministratorToken;
    private $moodleApiUrl;

    /**
     * 
     */
    public function init()
    {
        parent::init();
        //$moodleApiUrl = "http://demo-cyber.cfiscuola.it/webservice/rest/server.php";
        //$moodle_ws_token = "5fda89d97847966f3ee7114d2e6e3c79";
        //pr(@Yii::$app->params);exit();
        //pr($moduleConfig);exit();
        $this->moodleApiUrl = Yii::$app->getModule('moodle')->moodleApiUrl ?: null;
        $this->moodleAdministratorToken = Yii::$app->getModule('moodle')->moodleAdministratorToken ?: null;

        $this->initUserMoodle();
    }

    /**
     * 
     */
    public function initUserMoodle()
    {
        //$this->moodleUser = Yii::$app->session->get("moodleUser", null);
        if (empty($this->moodleUser)) {
            $loggedUser = \Yii::$app->getUser()->identity;
            $loggedUserId = $loggedUser->id;
            $this->moodleUser = MoodleUser::findOne([
                    'user_id' => $loggedUserId,
            ]);
            /* if (!empty($this->moodleUser)) {
              pr("metto in sessione");
              Yii::$app->session->set("moodleUser", $this->moodleUser);
              } */
        }
        //pr($this->moodleUser,"moodleUser");

        if (!empty($this->moodleUser)) {
            $this->moodleUserToken = $this->moodleUser->moodle_token;
        }
    }

    /**
     * Used to "impersonate" another user during a PayPal transaction to check
     * if the user that be enrolled is already enrol!
     * 
     * @param type $userToken
     */
    public function setMoodleUserToken($userToken = null) {
        $this->moodleUserToken = $userToken;
    }
    
    /*
      Setta un moodle user che non è necessariamente quello loginato (serve per il MOODLE_RESPONSABILE che deve iscrivere ai corsi altre persone)
     * @param int $userId: id dello User
     */
    public function setUserMoodle($userId)
    {
        $this->moodleUser = MoodleUser::findOne([
            'user_id' => $userId,
        ]);

        if (!empty($this->moodleUser)) {
            $this->moodleUserToken = $this->moodleUser->moodle_token;
        }
    }

    /**
     * 
     * @return type
     */
    public function getMoodleUserId()
    {
        //$userId = "8";  // Chiara
        //$userId="3";  // Walter

        return $this->moodleUser->moodle_userid;
    }

    /**
     * Ritorna la lista dei badges ottenuti da un certo utente relativamente ad un certo corso
     * @param int|0 $courseId: id del corso su Moodle . default badge di tutti i corsi
     * @return array : lista dei badges ottenuti
     */
    public function getUserBadges($courseId = 0)
    {
        $userId = $this->getMoodleUserId();

        $badges = $this->askMoodle("core_badges_get_user_badges", ["userid" => $userId, "courseid" => $courseId]);
        // add token in url
        foreach ($badges["badges"] as &$b) {
            //pr($b); exit;
            if ($b["badgeurl"]) {
                $b["badgeurl"] .= '?token=' . $this->moodleUserToken;
            }
        }
        //pr($badges, '$badges');
        return $badges;
    }

    /**
     * Ritorna la lista dei badges di un certo corso
     * @return array : lista dei badges del corso
     */
    public function getCourseBadges($courseId)
    {
        $userId = $this->getMoodleUserId();

        $badges = $this->askMoodle("local_open20integration_get_course_badges", ["courseid" => $courseId]);
        foreach ($badges["badges"] as &$b) {
            if ($b["badgeurl"]) {
                $b["badgeurl"] .= '?token=' . $this->moodleUserToken;
            }
        }
        return $badges;
    }

    /**
     * Ritorna la classifica di studenti relativa ad un certo corso 
     * @param int $courseId: id del corso su Moodle
     * @return array : lista di studenti con il punteggio ottenuto da ciascuno
     */
    public function getRanking($courseId)
    {

        $rankingList = $this->askMoodle("blocks_ranking_get_ranking", ["courseids" => [$courseId]]);
        // add token in picture
        foreach ($rankingList["leaderboard"] as &$r) {
            if ($r["picture"]) {
                $r["picture"] .= '?token=' . $this->moodleUserToken;
            }
        }


        return $rankingList["leaderboard"];
    }

    /**
     * Ritorna i dettagli di uno o più corsi - usa core_course_get_courses_by_field
     * @param bool|false withImages: indica se si vuole o meno anche l'immagine del corso
     * @param int courseId: id del corso su Moodle
     * @param int categoryId: id categoria su cui filtrare i corsi su Moodle
     * @param bool|false forceAll: torna anche quelli NON visibili
     * @param bool onlySelfEnrolment: Ritorna solo i corsi a iscrizione autonoma (quelli aperti)
     * @return array : lista di corsi
     */
    public function getCoursesList($withImages = false, $courseId = null, $categoryId = null, $forceAll = false, $onlySelfEnrolment = false)
    {
        //pr($this->moodleUser);exit();
        $params = null;
        if (!is_null($courseId)) {
            $params = ["field" => "id", "value" => $courseId];
        } else if (!is_null($categoryId)) {
            $params = ["field" => "category", "value" => $categoryId];
        }
        //pr($params, '$params'); //exit;
        $tmp = $this->askMoodle("core_course_get_courses_by_field", $params, 'post', true);
        if (isset($tmp['courses'])) {
            $corsiRaw = $tmp['courses'];
        }
        //pr($corsiRaw, '$corsiRaw'); //exit;
        $corsi = [];
        if (is_array($corsiRaw) and ( count($corsiRaw) > 0)) {
            if ($withImages) {
                $coursesImages = $this->getCoursesImage();
            }

            foreach ($corsiRaw as $corso) {// toglie il primo che è l'home page e quelli non visibili
                if ($corso['id'] != 1 && (($corso['visible'] == 1) || $forceAll)) {
                    if ($withImages) {
                        $corso['imageurl'] = $this->findCourseImage($coursesImages, $corso["id"]);
                    }

                    if (!$onlySelfEnrolment || in_array('self', $corso["enrollmentmethods"])) {
                        $corsi[$corso['sortorder']] = $corso;
                    }

                    // Update enrollment_methods on moodle_course platform table
                    $moodleCourse = base\MoodleCourse::find()
                        ->andWhere(['moodle_courseid' => $corso['id']])
                        ->one();

                    if (!empty($moodleCourse)) {
                        $moodleCourse->enrollment_methods = serialize(array_shift($this->getEnrolInfo($corso['id'])));
                        $moodleCourse->save();
                    }
                }
            }
            ksort($corsi);  // li riordina per sortorder
        }

//      pr($corsi, 'ServiceCall - getCoursesList - $corsi'); exit;

        return array_values($corsi);    // la chiave da fastidio
    }

    /**
     * Ritorna un elenco di categorie di Moodle. Se non vengono passati parametri ritorna l'elenco di tutte le categorie
     * @param int categoryParentId: id Moodle della categoria padre di cui si vogliono le sottocategorie
     * @param int $categoryId: id Moodle della categoria . Se viene passato questo parametro, il metodo ritorna un aray con una sola categoria
     * @return array : lista di categorie
     */
    public function getCategoryList($categoryParentId = null, $categoryId = null)
    {
        $params = null;
        // key: id, ids, parent
        if (!is_null($categoryId)) {
            $params = ["criteria" => [["key" => "id", "value" => $categoryId]]];
        } else if (!is_null($categoryParentId)) {
            $params = ["criteria" => [["key" => "parent", "value" => $categoryParentId]]];
        }
        //pr($params, '$params');
        $categorieRaw = $this->askMoodle("core_course_get_categories", $params, 'post', true);


        $categorie = [];
        if (is_array($categorieRaw)) {
            if (is_null($categoryId)) {
                foreach ($categorieRaw as $categoria) {// lascia solo quelle effettivamente figlie di $categoryParentId e non le sottofiglie
                    if ($categoria["parent"] == $categoryParentId && $categoria["visible"] == 1) {
                        $categorie[] = $categoria;
                    }
                }
            } else {
                $categorie = $categorieRaw;
            }
        }
        //pr($categorie, '$categorie'); exit;
        return $categorie;
    }

    /**
     * 
     * @param type $coursesImages
     * @param type $courseId
     * @return type
     */
    function findCourseImage($coursesImages, $courseId)
    {
        // pr($coursesImages);exit();
        foreach ($coursesImages as $course) {
            if ($course["courseid"] == $courseId) {
                return $course["imageurl"];
            }
        }
        return null;
    }

    /**
     * Ritorna le immagini di tutti i corsi
     * @return array : lista di immagini con il corso al quale si riferiscono
     */
    public function getCoursesImage()
    {
        $res = $this->askMoodle("local_open20integration_get_courses_imgs", null, 'get', true);
        return $res;
    }

    /**
     * Ritorna i dettagli riguardanti i tentativi fatti su uno scorm dall'utente corrente
     * @param int $moduleId: id del modulo del corso Moodle relativo allo scorm
     * @return array : il valore in corrispondenza della chiave "scormname" è l'html con le informazioni riguardanti i tentativi fatti sullo scorm. il valore in corrispondenza della chiave "playerurl" contiene l'url per vedere il video
     */
    public function getScormDetails($moduleId)
    {
        $res = $this->askMoodle("local_open20integration_get_scorm_data_by_cm", ["cmid" => $moduleId]);
        return $res;
    }

    /**
     * Ritorna un certo certificato di un corso Moodle per l'utente corrente. Se non è ancora stato creato lo crea.
     * @param int $certificateId: id di un certificato su un corso Moodle
     * @return array : dati del certificato
     */
    public function getCertificateDetails($certificateId)
    {
        $certificateDetails = $this->askMoodle("mod_certificate_issue_certificate", ["certificateid" => $certificateId]);
        if (!empty($certificateDetails["issue"]) && !empty($certificateDetails["issue"]["fileurl"])) {
            $certificateDetails["issue"]["fileurl"] .= '?token=' . $this->moodleUserToken;
        }

        return $certificateDetails["issue"];
    }

    /**
     * Iscrive l'utente corrente ad un corso con 'iscrizione spontanea' (self enrol)
     * @param int $courseId: id del corso su Moodle
     * @return array : dati relativi all'iscrizione avvenuta
     */
    public function selfEnrolInCourse($courseId)
    {
        $answer = $this->askMoodle("enrol_self_enrol_user", ["courseid" => $courseId]);
        return $answer;
    }

    /*     * *
     * Tutti gli utenti iscritti al corso
     */

    public function getUserEnrolledInCourse($courseId)
    {
        $users = $this->askMoodle("core_enrol_get_enrolled_users", ["courseid" => $courseId], 'get', true);
        return $users;
    }

    /**     *
     * Ritorna i corsi a cui è iscritto l'utente corrente
     * @return array : lista di corsi
     */
    public function getCoursesUserEnrolled()
    {
        $userId = $this->getMoodleUserId();
        $corsi = $this->askMoodle("core_enrol_get_users_courses", ["userid" => $userId]);
//        pr($corsi, 'corsi');exit;
        return $corsi;
    }

    /*
     * Restutuisce se l'utente corrente è iscritto o meno ad un corso
     * @param int $courseId: id del corso su Moodle
     * @return bool: true se l'utente è iscritto al corso, false altrimenti
     */

    public function isUserEnrolledInCourse($courseId)
    {
//        $ret = false;

        if (!empty($courseId)) {
            $coursesListUserEnrolled = $this->getCoursesUserEnrolled();

            foreach ($coursesListUserEnrolled as $course) {
                if ($course['id'] == $courseId) {
                    return true;
//                    break;
                }
            }
        }
        
        return false;
    }

    /*
     * Ritorna i metodi di iscrizione ad un corso
     * @param int $courseId: id del corso su Moodle
     * @return array : lista dei metodi di iscrizione al corso
     */

    public function getCourseEnrollmentMethods($courseId)
    {
        $userId = $this->getMoodleUserId();
        $methods = $this->askMoodle("core_enrol_get_course_enrolment_methods", ["courseid" => $courseId]);

        return $methods;
    }

    /*
     * Restituisce se a questo corso è possibile iscriversi con iscrizione spontanea (self enrol), false altrimenti
     * @param int $courseId: id del corso su Moodle
     * @return bool: true se a questo corso è possibile iscriversi con iscrizione spontanea (self enrol), false altrimenti
     */

    public function selfEnrollmentActive($courseId)
    {
        $ret = false;
        $methods = $this->getCourseEnrollmentMethods($courseId);
        //pr($methods);
        foreach ($methods as $method) {
            if ($method["type"] == "self" && $method["status"] == 1) {
                $ret = true;
                break;
            }
        }

        return $ret;
    }

    /*
     * Ritorna lo stato di completamento delle attività di un corso da parte dell'utente corrente 
     * status: completion state value - 0 means incomplete, 1 complete, 2 complete pass, 3 complete fail
     * tracking: type of tracking - 0 means none, 1 manual, 2 automatic
     * @param int $courseId: id del corso su Moodle
     * @return array : lista di attività di un corso
     */

    public function getActivitiesCompletionStatus($courseId)
    {
        $userId = $this->getMoodleUserId();
        $activitiesCompletionStatusRaw = $this->askMoodle("core_completion_get_activities_completion_status", ["courseid" => $courseId, "userid" => $userId]);
        //pr($activitiesCompletionStatusRaw, 'activitiesCompletionStatusRaw');exit;
        $activitiesCompletionStatus = [];
        foreach ($activitiesCompletionStatusRaw['statuses'] as $acs) {
            $activitiesCompletionStatus[$acs['cmid']] = $acs;
        }
        return $activitiesCompletionStatus;
    }

    /*
     * Ritorna i contenuti di un corso visibili all'utente corrente.
     * Vengono restituiti solo i tipi di contenuto gestiti da Open 2.0 ovvero scorm, certificati e risorse
     * @param int $courseId: id del corso su Moodle
     * @param int|null $topicId: id dell'argomento del corso su Moodle. Se presente vengono restituiti solo i contenuti di questo argomento
     * @return array : lista di contenuti del corso
     */

    public function getCourseContents($courseId, $topicId = null)
    {
        $whiteListContents = ["scorm", "resource", "certificate"]; //Tipi di contenuti gestiti da Open2.0

        $options = array();
        if (!isset($topicId)) {
            $options = [["name" => "excludecontents", "value" => true]];
        } else {
            $options = [["name" => "sectionid", "value" => $topicId]];
        }
        $attivitaRaw = $this->askMoodle("core_course_get_contents", ["courseid" => $courseId, "options" => $options]);

        if (!is_array($attivitaRaw)) {
            $attivitaList = [];
        } else {
            $activitiesCompletionStatus = $this->getActivitiesCompletionStatus($courseId);
            //pr($activitiesCompletionStatus);
            foreach ($attivitaRaw as $attivita) {
                if (isset($attivita['modules'])) {
                    $completate = 0;    // attività completate
                    foreach ($attivita['modules'] as $i => &$module) {
                        if (!in_array($module["modname"], $whiteListContents)) {
                            unset($attivita['modules'][$i]); //tolgo le attività non gestite
                        } else {
                            if ($module["modname"] == "resource") {
                                $module["url"] .= "&redirect=1";
                            }

                            //pr($module);
                            // attacca le iformazioni relative al completamento
                            $attivita['modules'][$i]['moodleActivitiesCompletionStatus'] = (isset($activitiesCompletionStatus[$module['id']])) ?
                                $activitiesCompletionStatus[$module['id']]['state'] :
                                self::ACTIVITY_STATUS_ND;
                            if (\in_array($attivita['modules'][$i]['moodleActivitiesCompletionStatus'], self::ACTIVITY_STATUS_COMPLETED_LIST)) {
                                $completate++;
                            }
                        }
                    }
                    //pr($attivita, 'attivita');exit;
                    if (count($attivita['modules'])) {
                        $attivita['moodleActivitiesCompleted'] = $completate;
                        $attivitaList[] = $attivita;
                    }
                }
            }
        }

        if (!isset($attivitaList)) {
            $attivitaList = [];
        }
        //pr($attivitaList, '$attivitaList'); exit;


        return $attivitaList;
    }

    /* public function getMoodleUserDetails($moodleUserId) {
      $userDetails = $this->askMoodle("core_user_get_users_by_field",["field" => "id","values" => [$moodleUserId]], 'post', true);
      $res =[];
      if(isset($userDetails) && isset($userDetails[0])){
      $res = $userDetails[0];
      }
      return $res;
      } */

    /*
     * Crea un nuovo utente su Moodle. Gli viene assegnato il ruolo di base per Open2.0 (open20base) che serve per poter generare il token utente che permette di chiamare le api
     * @param string $username: username dell'utente da creare
     * @param string $password: password dell'utente da creare
     * @param string $firstname: nome dell'utente da creare
     * @param string $lastname: cognome dell'utente da creare
     * @param string $email: email dell'utente da creare
     * @return int id dell'utente creato 
     */

    public function createUser($username, $password, $firstname, $lastname, $email)
    {
        $moduleConfig = Yii::$app->getModule('moodle')->config;

        $user = [
            'username' => $username,
            'password' => $password,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'auth' => $moduleConfig['moodleAuthName'], // manual
            'lang' => 'it',
            'timezone' => $moduleConfig['moodleTimezoneServer'],
            'country' => 'it' // TODO - da open
        ];

        try {
            $ret = $this->askMoodle("core_user_create_users", ["users" => [$user]], 'post', true);
            //pr($ret, 'createUser'); //exit;
            $userId = $ret[0]['id'];
            $assignmentsOpen20Base = [
                'roleid' => Yii::$app->getModule('moodle')->moodleOpen20baseRoleId, // Role to assign to the user
                'userid' => $userId, // The user that is going to be assigned
                'contextid' => 1, // Optional - The context to assign the user role in
                'contextlevel' => 'system', // Optional - The context level to assign the user role in (block, course, coursecat, system, user, module)
                //'instanceid' => ?,  // Optional - The Instance id of item where the role needs to be assigned
            ];
            $this->askMoodle("core_role_assign_roles", ["assignments" => [$assignmentsOpen20Base]], 'post', true);

            return $userId;
        } catch (MoodleException $e) {
            // TODO: cosa facciamo?
            echo $e->getMessage();
            return null;
        }
    }

    /*
     * Abilita un utente su Moodle
     * @param int $moodleUserId: id dell'utente Moodle
     * @return void
     */

    public function enableMoodleUser($moodleUserId)
    {
        $user = [
            'id' => $moodleUserId,
            'suspended' => 0
        ];
        $userDetails = $this->askMoodle("core_user_update_users", ["users" => [$user]], 'post', true);
    }

    /*
     * Sospende un utente su Moodle
     * @param int $moodleUserId: id dell'utente Moodle
     * @return void
     */

    public function disableMoodleUser($moodleUserId)
    {
        $user = [
            'id' => $moodleUserId,
            'suspended' => 1
        ];
        $userDetails = $this->askMoodle("core_user_update_users", ["users" => [$user]], 'post', true);
    }

    /*
     * Aggiunge un utente alla lista dei "Site admin" di Moodle
     * @param int $moodleUserId: id dell'utente Moodle
     * @return void
     */

    public function addSiteAdminPermission($moodleUserId)
    {
        $ret = $this->askMoodle("local_open20integration_add_siteadmin", ["userid" => $moodleUserId], 'post', true);
    }

    /*     * *
     * Esegue una chiamata alle api di moodle e ne ritorna il risultato
     * @param string $wsFunction: nome dell'api da chiamare
     * @param array $params: parametri da passare alla chiamata
     * @param string $method|post: metodo di chiamata
     * @param bool $useAdminToken|false: indica se usare il token di amministratore o il token dell'utente corrente 
     * @param int $attemptNum: numero di volte successive in cui si tenta la chiamata
     * @throws MoodleException
     */

    public function askMoodle($wsFunction, $params, $method = 'post', $useAdminToken = false, $attemptNum = 1)
    {
        if ($useAdminToken) {
            $currentToken = $this->moodleAdministratorToken;
        } else {
            $currentToken = $this->moodleUserToken;
        }

        $url = $this->moodleApiUrl . '?wstoken=' . $currentToken . '&wsfunction=' . $wsFunction . '&moodlewsrestformat=json';
        //pr($url);
        $client = new Client();     // httpclient
        $request = $client->createRequest()
            ->setMethod($method)
            ->setUrl($url);

        if ($params) {
            $request->setData($params);
        }

        $response = $request->send();
        if ($response->isOk) {
            $data = $response->getData();
            if (isset($data['errorcode'])) {
                if (!$useAdminToken && (($data['errorcode'] == "invalidtoken") || ($data['errorcode'] == "accessexception")) && ($attemptNum < 2)) {
                    $this->regenerateExpiredToken();
                    return $this->askMoodle($wsFunction, $params, $method, $useAdminToken, 2);
                }
                /**
                  if ($params) {
                  pr($params, 'params: ');
                  }
                  pr($data, 'askMoodle: ' . $wsFunction);
                  exit; * */
                throw new MoodleException($data['errorcode']);
            }

            return $data;
        }

        $status = $response->getStatusCode();
        throw new MoodleException($status);
    }

    /**
     * 
     * @return type
     */
    public function getMoodleAdministratorToken()
    {
        return $this->moodleAdministratorToken;
    }

    /**
     * 
     */
    private function regenerateExpiredToken()
    {
        if (isset($this->moodleUser)) {
            //pr("rigenero il token");//exit;
            $userToken = $this->getUserToken($this->moodleUser->moodle_userid);
            $this->moodleUser->moodle_token = $userToken;
            $this->moodleUser->save();
            $this->moodleUserToken = $userToken;
            //Yii::$app->session->set("moodleUser", $this->moodleUser);
            //$this->initUserMoodle();
        }
    }

    /*
     * Ritorna il token dell'utente. Se non è presente o è scaduto ne crea uno nuovo e lo ritorna 
     * Crea token permanenti
     * @param string $userid: id dell'utente Moodle
     * @return string: token dell'utente
     * @throws MoodleException
     */

    function getUserToken($userid)
    {
        $data = $this->askMoodle(
            'local_open20integration_generate_user_token',
            [
                'userid' => $userid,
                'serviceshortname' => 'O20'
            ],
            'post',
            true
        );

        if (isset($data['token'])) {
            return $data['token'];
        }

        throw new MoodleException(Yii::t('servicecall', 'getUserToken Error'));
    }

    /**
     * Ritorna la lista dei badges di un certo corso
     * @return array : lista dei badges del corso
     */
    public function getEnrolInfo($courseId)
    {
        $enrolInfo = $this->askMoodle('local_open20integration_get_enrol_info', ["courseid" => $courseId]);

        return $enrolInfo;
    }
    
    /**
     * Enrol user after an OK PayPal payment 
     * 
     * @param type $enrolid
     * @param type $userid
     */
    public function setEnrolUserViaPayPal($enrolid = null, $userid = null ) {
        $enrolUser = $this->askMoodle(
            'local_open20integration_enrol_user_via_paypal',
            [
                'enrolid' => $enrolid,
                'userid' => $userid
            ],
            'post',
            true
        );
        
        return $enrolUser;
    }

    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return '';
    }

}
