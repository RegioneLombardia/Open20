<?php

namespace open20\amos\moodle\models;

use open20\amos\moodle\AmosMoodle;
use open20\amos\community\models\CommunityContextInterface;
use open20\amos\moodle\models\ServiceCall;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "moodle_course".
 */
class MoodleCourse extends \open20\amos\moodle\models\base\MoodleCourse implements CommunityContextInterface
{

    /**
     * Constants for community roles
     */
//    const MOODLE_MANAGER = 'MOODLE_ADMIN';
//    const MOODLE_STUDENT = 'MOODLE_STUDENT';

    public $name;   // Moodle
    // va nel db public $moodle_categoryid;   // Moodle
    public $db_moodle_categoryid;   // La categoria moodle salvata sul db Open2.0 e non ricavata da Moodle
    public $summary;
    public $imageurl;
    public $userEnrolled; //Se l'utente corrente è iscritto o meno al corso

    /**
     * 
     * @return type
     */
    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    /**
     * 
     * @return type
     */
    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    /**
     * 
     * @return type
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
                [['name'], 'string', 'max' => 255],
                [['db_moodle_categoryid'], 'integer'],
        ]);
    }

    /**
     * 
     * @return type
     */
    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(), [
                'name' => AmosMoodle::t('amosmoodle', 'Nome'),
                // va nel db 'moodle_categoryid' => AmosMoodle::t('amosmoodle', 'Moodle Category Id'),
                'db_moodle_categoryid' => AmosMoodle::t('amosmoodle', 'Moodle Category Id sulla tabella e non letto da moodle'),
        ]);
    }

    /**
     * Ritorna l'url dell'avatar.
     *
     * @param string $dimension Dimensione. Default = small.
     * @return string Ritorna l'url.
     */
    public function getAvatarUrl($dimension = 'small')
    {
        $url = '/img/img_default.jpg';
        if ($this->filemanager_mediafile_id) {
            $mediafile = \pendalf89\filemanager\models\Mediafile::findOne($this->filemanager_mediafile_id);
            if ($mediafile) {
                $url = $mediafile->getThumbUrl($dimension);
            }
        }
        return $url;
    }

    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return "";
    }

    /**
     * Aggiunge ai dati del db il nome preso da moodle
     * 
     * @param type $condition
     */
    public static function findOne($condition)
    {
        $ret = parent::findOne($condition);
        if (!is_null($ret) && $ret->moodle_courseid) {

            $ret->getMoodleCourseData();
            //$ret->imageurl=null;
        }
        //pr($ret, 'ret');exit;
        return $ret;
    }

    /**
     * 
     * @param type $condition
     * @return type
     */
    public static function findOneOnlyDbData($condition)
    {
        $ret = parent::findOne($condition);

        return $ret;
    }

    /**
     * 
     */
    public function getMoodleCourseData()
    {
        if ($this->moodle_courseid) {
            $serviceCall = new ServiceCall();
            $courses = $serviceCall->getCoursesList(
                true,
                $this->moodle_courseid,
                null,
                true
            ); // li vuole anche se non visibili

            if (!empty($courses) && !empty($courses[0])) {
                $this->name = $courses[0]['displayname']; // oppure fullname
                $this->summary = $courses[0]['summary'];
                if (is_null($this->db_moodle_categoryid)) {
                    $this->db_moodle_categoryid = $this->moodle_categoryid;
                }
                $this->moodle_categoryid = $courses[0]['categoryid'];
                $this->imageurl = $courses[0]['imageurl'];

//                /**
//                 * Serialized because it is an array with multiple values too...
//                 */
//                $this->enrollment_methods = serialize($courses[0]['enrollmentmethods']);
            }
        }
    }

    /**
     * La categoria del corso su Moodle è cambiata (quella che viene richiesta 
     * a Moodle è diversa da quella sul database
     * 
     * @return boolean
     */
    public function isMoodleCategoryChanged()
    {
        if (is_null($this->db_moodle_categoryid)) {
            $this->getMoodleCourseData();
        }
        return $this->db_moodle_categoryid != $this->moodle_categoryid;
    }

    /**
     * Elenco degli utenti iscritti al corso su Moodle
     * 
     * @return array
     */
    public function getEnrolledUsers()
    {
        if ($this->moodle_courseid) {
            $serviceCall = new ServiceCall();
            $users = $serviceCall->getUserEnrolledInCourse($this->moodle_courseid);
            //pr($users, 'moodle_courseid '.$users);exit;
            return $users;
        }
    }

    /**
     * Elenco dei badge del corso
     * 
     * @return array
     */
    public function getBadges()
    {
        if ($this->moodle_courseid) {
            $serviceCall = new ServiceCall();
            $badges = $serviceCall->getCourseBadges($this->moodle_courseid);
            return (isset($badges['badges'])) ? $badges['badges'] : array();
        }
    }

    /**
     * Ritorna vero se il corso ha dei badge
     * 
     * @return array
     */
    public function hasBadges()
    {

        $badges = $this->getBadges();

        return(count($badges) > 0);
    }

    /**
     * @inheritdoc
     */
    public function getContextRoles()
    {
        $context_roles = [
            AmosMoodle::MOODLE_MANAGER,
            AmosMoodle::MOODLE_STUDENT
        ];
        return $context_roles;
    }

    /**
     * @inheritdoc
     */
    public function getBaseRole()
    {
        return AmosMoodle::MOODLE_STUDENT;
    }

    /**
     * @inheritdoc
     */
    public function getManagerRole()
    {
        return AmosMoodle::MOODLE_MANAGER;
    }

    /**
     * @inheritdoc
     */
    public function getRolePermissions($role)
    {
        switch ($role) {
            case AmosMoodle::MOODLE_MANAGER:
                return ['CWH_PERMISSION_CREATE', 'CWH_PERMISSION_VALIDATE'];
                break;
            case AmosMoodle::MOODLE_STUDENT:
                return ['CWH_PERMISSION_CREATE'];
                break;
            default:
                return ['CWH_PERMISSION_CREATE'];
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public function getCommunityModel()
    {
        return $this->community;
    }

    /**
     * @inheritdoc
     */
    public function getNextRole($role)
    {
        switch ($role) {
            case AmosMoodle::MOODLE_MANAGER:
                return AmosMoodle::MOODLE_STUDENT;
                break;
            case AmosMoodle::MOODLE_STUDENT:
                return AmosMoodle::MOODLE_MANAGER;
                break;
            default:
                return self::MOODLE_STUDENT;
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public function getPluginModule()
    {
        return 'moodle';
    }

    /**
     * @inheritdoc
     */
    public function getPluginController()
    {
        return 'moodle';
    }

    /**
     * @inheritdoc
     */
    public function getRedirectAction()
    {
        return 'view';  // TODO: verificare
    }

    /**
     * @inheritdoc
     */
    public function getAdditionalAssociationTargetQuery($communityId)
    {
        /** @var ActiveQuery $communityUserMms */
        // TODO: da verificare
        $communityUserMms = CommunityUserMm::find()->andWhere(['community_id' => $communityId]);
        return User::find()->andFilterWhere(['not in', 'id', $communityUserMms->select('user_id')]);
    }

    /*
      @param array $allCourseArray: Lista di corsi di Moodle (nel formato restituito dalle api di Moodle)
      @param array $coursesUserEnrolledArray: Corsi di Moodle a cui l'utente corrente è iscritto (nel formato restituito dalle api di Moodle)
      @return array Elenco dei corsi di Moodle (oggetti MoodleCourse) corrispondenti a quelli passati nel parametro $allCourseArray
     */

    public function getCourseList($allCourseArray, $coursesUserEnrolledArray)
    {
        //pr($courseArray);
        $courseList = array();

        foreach ($allCourseArray as $current) {

            $currCourse = MoodleCourse::findOneOnlyDbData([
                'moodle_courseid' => $current["id"],
            ]);

            if (!is_null($currCourse)) {
                $currCourse->name = $current["displayname"];
                $currCourse->db_moodle_categoryid = $currCourse->moodle_categoryid;
                $currCourse->moodle_categoryid = $current["categoryid"];
                $currCourse->summary = $current["summary"];
                if (isset($current["imageurl"])) {
                    $currCourse->imageurl = $current["imageurl"];
                }
                if ($this->isInArray($current, $coursesUserEnrolledArray)) {
                    $currCourse->userEnrolled = true;
                } else {
                    $currCourse->userEnrolled = false;
                }
//                \yii\helpers\VarDumper::dump([$currCourse->getAttributes()],3,true);
//                die();
                
                // pr($currCourse->imageurl);
                // pr($current["imageurl"]);
                array_push($courseList, $currCourse);
            }
        }

        return $courseList;
    }

    /*
      Restutuisce se un corso è presente o meno in un elenco di corsi
      @param array  $course: Corso, così come viene restutuito dalle api di Moodle
      @param array  $courseArray: Elenco di corsi, così come vengono restutuiti dalle api di Moodle
      @return bool : true se il corso è presente nell'elenco di corsi, false altrimenti
     */

    private function isInArray($course, $courseArray)
    {
        $ret = false;

        foreach ($courseArray as $current) {
            if ($course["id"] == $current["id"]) {
                $ret = true;
            }
        }

        return $ret;
    }

    /**
     * Return true for paypal course
     * 
     * @return type
     */
    public function isPaypalCourse()
    {
        $this->enrollment_methods = unserialize($this->enrollment_methods);

        return $this->enrollment_methods['enrol'] == 'paypal';
    }

    /**
     * @param null $userId
     * @param bool $isUpdate
     * @return mixed
     */
    public function getUserNetworkWidget($userId = null, $isUpdate = false)
    {
        /** @var AmosAdmin $adminModule */
        $moodleModule = AmosMoodle::getModuleName();

        if (is_null(Yii::$app->getModule($moodleModule))) {
            return '';
        }

        return \open20\amos\moodle\widgets\UserNetworkWidget::widget(['userId' => $userId, 'isUpdate' => $isUpdate]);
    }

    /**
     * Return classname of the MM table connecting user and network
     * @return string
     */
    public function getMmClassName()
    {
        return AmosMoodle::instance()->model('MoodleUser');
    }
    
    /**
     * Get the name of the table storing network-users associations
     * @return string
     */
    public function getMmTableName()
    {
        return MoodleUser::tableName();
    }
    
    /**
     * Get the name of field that contains user id in network-users association table
     * @return string
     */
    public function getMmNetworkIdFieldName()
    {
        return 'user_id';
    }

    /**
     * Get the name of field that contains network id in network-users association table
     * @return string
     */
    public function getMmUserIdFieldName()
    {
        return 'user_id';
    }
}
