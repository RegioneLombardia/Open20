<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 05/11/2020
 * Time: 10:17
 */

namespace open20\amos\mobile\bridge\models;

use app\modules\cmsapi\frontend\models\CmsMailAfterLogin;
use backend\modules\eventsadmin\controllers\UserProfileController;
use backend\modules\eventsadmin\utility\EventsAdminUtility;
use open20\amos\admin\models\TokenGroup;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\models\ModelsClassname;
use open20\amos\core\record\RecordDynamicModel;
use open20\amos\core\user\User;
use open20\amos\core\validators\CFValidator;
use open20\amos\events\AmosEvents;
use open20\amos\events\controllers\EventController;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventGroupReferentMm;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\utility\EventsUtility;
use open20\amos\mobile\bridge\Module;
use open20\amos\socialauth\models\SocialAuthUsers;
use open20\amos\socialauth\utility\SocialAuthUtility;
use Exception;
use Hybrid_User_Profile;
use Mustache_Engine;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;


class RegisterUserEventsAria extends Model
{


    private $email_field = 'email';

    private $community_id;
    private $facilitator_id = null;
    public $send_credential = false;
    public $is_waiting = false;
    private $create_account_field;
    private $email_layout_after_login;
    private $from_email;
    private $email_subject_after_login;
    private $email_after_login_text;
    private $email_after_login;
    private $email_text_new_account;


    public $event_id;
    public $userSocial;
    public $datiRecuperatiDaSocial = 0;
    public $socialScelto = '';
    public $associaNuovoAccountSocial = 0;
    public $name;
    public $surname;
    public $email;
    public $company;
    public $sex;
    public $age;
    public $country;
    public $city;
    public $fiscal_code;
    public $telefon;
    public $privacy;
    public $privacy_2;
    public $user_id;
    public $preference_tags;


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'email', 'event_id'], 'required', 'message' => Module::t('amosmobilebridge', "Il campo non può essere vuoto")],
            [['preference_tags', 'company', 'name', 'surname', 'email', 'sex', 'fiscal_code', 'telefon', 'userSocial', 'datiRecuperatiDaSocial', 'associaNuovoAccountSocial'], 'safe'],
            [['age', 'country', 'city', 'privacy_2', 'privacy', 'user_id', 'event_id'], 'integer'],
            ['email', 'email', 'message' => "L'indirizzo email non è valido"],
            [['fiscal_code'], CFValidator::className()],
            [['privacy_2'], 'isRequiredPreferenceTags'],
            [['privacy'], 'required', 'requiredValue' => 1, 'message' => Module::t('amosmobilebridge', 'It is mandatory to accept the informations about the privacy')],
        ];
    }

    /**
     * @param string $attribute
     * @return mixed|string
     */
    public function getAttributeLabel($attribute)
    {
        $array = [
            'email' => Module::t('amosmbilebridge', "Email"),
            'name' => Module::t('amosmbilebridge', "Nome"),
            'surname' => Module::t('amosmbilebridge', "Cognome"),
            'company' => Module::t('amosmbilebridge', "Azienda"),
            'sex' => Module::t('amosmbilebridge', "Sesso"),
            'fiscal_code' => Module::t('amosmbilebridge', "Codice Fiscale"),
            'telefon' => Module::t('amosmbilebridge', "Telefono"),
            'age' => Module::t('amosmbilebridge', "Età"),
            'country' => Module::t('amosmbilebridge', "Provincia"),
            'city' => Module::t('amosmbilebridge', "Città"),
            'privacy' => Module::t('amosmbilebridge', "Privacy"),
            'privacy_2' => Module::t('amosmbilebridge', "Privacy 2"),
            'preference_tags' => Module::t('amosmbilebridge', "Preference tags"),
        ];
        if (!empty($array[$attribute])) {
            return $array[$attribute];
        }
        return '';
    }

    public function isRequiredPreferenceTags()
    {
        if ($this->privacy_2) {
            if (empty($_POST['preference_tags'])) {
                $this->addError('preference_tags', Module::t('amosmobilebridge', "E' necessario selezionare almeno un tag"));
                $this->addError('privacy_2', Module::t('amosmobilebridge', "E' necessario selezionare almeno un tag"));
            }
        }

    }

    public function getEmail_after_login()
    {
        return $this->email_after_login;
    }

    public function setEmail_after_login($email_after_login)
    {
        $this->email_after_login = boolval($email_after_login);
    }

    public function getCommunityID()
    {
        return $this->community_id;
    }

    public function setCommunityID($id)
    {
        if (!empty($id)) {
            $this->community_id = $id;
        }
    }

    public function getFacilitatorID()
    {
        return $this->facilitator_id;
    }

    public function setFacilitatorID($id)
    {
        $this->facilitator_id = $id;
    }

    public function getSendCredential()
    {
        return $this->send_credential;
    }

    public function setSendCredential($send)
    {
        $this->send_credential = $send;
    }

    public function getCreate_account_field()
    {
        return $this->create_account_field;
    }

    public function setCreate_account_field($create_account_field)
    {
        $this->create_account_field = $create_account_field;
    }

    public function getEmail_layout_after_login()
    {
        return $this->email_layout_after_login;
    }

    public function getFrom_email()
    {
        return $this->from_email;
    }

    public function getEmail_subject_after_login()
    {
        return $this->email_subject_after_login;
    }

    public function getEmail_after_login_text()
    {
        return $this->email_after_login_text;
    }

    public function setEmail_layout_after_login($email_layout_after_login)
    {
        $this->email_layout_after_login = $email_layout_after_login;
    }

    public function setFrom_email($from_email)
    {
        $this->from_email = $from_email;
    }

    public function setEmail_subject_after_login($email_subject_after_login)
    {
        $this->email_subject_after_login = $email_subject_after_login;
    }

    public function setEmail_after_login_text($email_after_login_text)
    {
        $this->email_after_login_text = $email_after_login_text;
    }

    public function getEmail_text_new_account()
    {
        return $this->email_text_new_account;
    }

    public function setEmail_text_new_account($email_text_new_account)
    {
        $this->email_text_new_account = $email_text_new_account;
    }

    /**
     * @param $model
     * @return null
     * @throws InvalidConfigException
     */
    public function registerToPlatform($event, $data, $isWaiting)
    {
        $giaRegistratoInPiattaforma = 0;
        $user = $this->isEmailRegisteredInPoi($this->email);
        if (!is_null($user)) {
            $giaRegistratoInPiattaforma = 1;
            if ($data['send_mail']) {
                $this->sendMail($event, $data, $isWaiting, $user, $isWaiting);
            }
        }
        $user = $this->RegisterUserToPlatform($event, $giaRegistratoInPiattaforma, $data, $isWaiting);
        return $user;
    }

    /**
     * @param $model
     * @param $giaRegistratoInPiattaforma
     * @return null
     * @throws InvalidConfigException
     */
    public function RegisterUserToPlatform($event, $giaRegistratoInPiattaforma, $data, $isWaiting = false)
    {
        /** @var  $community  Community */
        $community = Community::findOne($event->community_id);
        $user = null;

        // creo un nuovo utente
        if ((empty($giaRegistratoInPiattaforma) || $giaRegistratoInPiattaforma == 0)) {
            UserProfileUtility::createNewAccount($this->name,
                $this->surname, $this->email, 1,
                $this->getSendCredential());
            $user = User::find()->andWhere(['email' => $this->email])->one();
            if ($user) {
                if (!$this->getSendCredential()) {
                    $user->generatePasswordResetToken();
                    $user->save(false);
                }
                /** @var  $profile UserProfile */
                $profile = $user->userProfile;
                $profile->privacy_2 = $this->privacy_2;

                EventsAdminUtility::savePreferencesTags($profile, $this->preference_tags);

                $profile->facilitatore_id = $this->getFacilitatorID();
                $profile->first_access_redirect_url = !empty($event->community_id) ? '/community/join?id=' . $event->community_id : '';
//                if ($this->getEmail_after_login()) {
//                    $cms_mail_after_login = $this->saveCmsMailAfterLogin($model);
//                    $profile->first_access_redirect_url = \Yii::$app->params['platform']['frontendUrl'] . '/api/1/send-mail-after-login?id=' . $cms_mail_after_login->id . '&redirect=/community/join?id=' . $this->getCommunityID();
//                }
                if (!$this->send_credential) {
                    if ($data['send_mail']) {
                        $ok = $this->sendNewAccountMail($event, $user, $data, $isWaiting);
                    }
                }
                $profile->user_profile_role_id = 7;
                $profile->user_profile_role_other = '';
                $profile->created_by = $profile->user_id;
                $profile->date_privacy = date('Y-m-d H:i:d');
                $profile->privacy = $this->privacy;
                $this->setUserProfileMoreFields($profile);
//                pr($profile->attributes);die;
                $profile->save(false);
                $this->registerToCommunity($community, $user, $isWaiting);

                //associo il social all'utente
                if (!empty($user) && !empty($this->userSocial)) {
                    $userSocial = Json::decode($this->userSocial);
                    $socialProfile = $this->getClassHybridUserProfile($userSocial);
//                    pr($socialProfile);
                    $this->createSocialUser($user->userProfile,
                        $socialProfile, $this->socialScelto);
                }

                $spidData = \Yii::$app->session->get('IDM');
                if (!empty($user) && $spidData) {
                    $createdSpidUser = SocialAuthUtility::createIdmUser($spidData, $user->id);
                }
            }
        } else {
            $user = User::find()->andWhere(['email' => $this->email])->one();
            if ($user) {
                $profile = $user->userProfile;
                $this->registerToCommunity($community, $user, $isWaiting);
                $this->setUserProfileMoreFields($profile);
                $profile->privacy_2 = $this->privacy_2;
                $profile->save(false);
            }
        }
//            $model->user_id = $user->id;
//            $model->save();

        $this->registerInvitation($user->id, $event);
        $this->assignAutomaticSeat($user->id, $event);
        $this->assignToDg($user->id, $event);
        return $user;
    }


    /**
     *
     * @param UserProfile $userprofile
     */
    protected function setUserProfileMoreFields(UserProfile $userprofile)
    {

        if (empty($userprofile->user_profile_age_group_id) && !empty($this->age)) {
            $userprofile->user_profile_age_group_id = $this->age;
        }


        if (empty($userprofile->nascita_comuni_id) && !empty($this->city)) {
            $userprofile->nascita_comuni_id = $this->city;
        }


        if (empty($userprofile->nascita_province_id) && !empty($this->country)) {
            $userprofile->nascita_province_id = $this->country;
        }

        if (empty($userprofile->azienda) && !empty($this->company)) {
            $userprofile->azienda = $this->company;
        }

        if (empty($userprofile->codice_fiscale) && !empty($this->fiscal_code)) {
            $userprofile->codice_fiscale = $this->fiscal_code;
        }


        if (empty($userprofile->sesso) && !empty($this->sex)) {
            $userprofile->sesso = $this->sex;
        }


        if (empty($userprofile->telefono) && !empty($this->telefon)) {
            $userprofile->telefono = $this->telefon;
        }

        $userprofile->nascita_nazioni_id = 1;

    }

    /**
     * @param $community
     * @param $user
     * @param bool $isWaiting
     * @return bool
     * @throws InvalidConfigException
     */
    public function registerToCommunity($community, $user, $isWaiting = false)
    {
        if ($community) {
            $moduleCommunity = Yii::$app->getModule('community');
            if ($moduleCommunity) {
                $count = CommunityUserMm::find()->andWhere(['user_id' => $user->id,
                    'community_id' => $community->id])->count();
                if ($count == 0) {
                    $context = $community->context;
                    if ($context == 'open20\amos\events\models\Event') {
                        $role = Event::EVENT_PARTICIPANT;
                    } else {
                        $role = CommunityUserMm::ROLE_PARTICIPANT;
                    }

                    if ($isWaiting) {
                        $status = CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER;
                    } else {
                        $status = CommunityUserMm::STATUS_ACTIVE;
                    }
                    $moduleCommunity->createCommunityUser($community->id,
                        $status, $role, $user->id);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $array
     * @return Hybrid_User_Profile
     */
    public function getClassHybridUserProfile($array)
    {
        $socialProfile = new Hybrid_User_Profile();
        foreach ($array as $key => $value) {
            $socialProfile->{$key} = $value;
        }
        return $socialProfile;
    }

    /**
     * @param $email
     */
    public function isEmailRegisteredInPoi($email)
    {
        $user = User::find()->andWhere(
            ['LIKE', 'email', $email]
        )->one();
        return $user;
    }

    /**
     * @param UserProfile $userProfile
     * @param \Hybrid_User_Profile $socialProfile
     * @param $provider
     * @return bool|SocialAuthUsers
     */
    public function createSocialUser($userProfile,
                                     \Hybrid_User_Profile $socialProfile,
                                     $provider)
    {
        try {
            /**
             * @var $socialUser SocialAuthUsers
             */
            $socialUser = new SocialAuthUsers();

            /**
             * @var $socialProfileArray array User profile from provider
             */
            $socialProfileArray = (array)$socialProfile;
            $socialProfileArray['provider'] = $provider;
            $socialProfileArray['user_id'] = $userProfile->user_id;

            /**
             * If all data can be loaded to new record
             */
            if ($socialUser->load(['SocialAuthUsers' => $socialProfileArray])) {
                /**
                 * Is valid social user
                 */
                if ($socialUser->validate()) {
                    $socialUser->save();
                    return $socialUser;
                } else {
                    \Yii::$app->session->addFlash('danger',
                        \Yii::t('amossocialauth',
                            'Unable to Link The Social Profile'));
                    return false;
                }
            } else {
                \Yii::$app->session->addFlash('danger',
                    \Yii::t('amossocialauth',
                        'Invalid Social Profile, Try again'));
                return false;
            }
        } catch (Exception $e) {
            return false;
        }

        return false;
    }

    /**
     * @param $user_id
     * @param $community
     * @return null
     * @throws InvalidConfigException
     */
    public function assignAutomaticSeat($user_id, $event)
    {
        if ($event->seats_management) {
            $seat = $event->assignAutomaticSeats($user_id);
            return $seat;
        }
        return null;
    }

    /**
     * @param $user_id
     * @param $community
     * @param $model
     * @return bool
     * @throws InvalidConfigException
     */
    public function assignToDg($user_id, $event)
    {
        if ($event && $event->event_group_referent_id) {
            $member = new EventGroupReferentMm();
            $member->user_id = $user_id;
            $member->event_group_referent_id = $event->event_group_referent_id;
            $member->exclude_from_query = 0;
            $member->save(false);
            return true;
        }
        return false;
    }

    /**
     * @param $event_id
     * @param $user_id
     * @param $nameField
     * @param $surnameField
     * @param $emailField
     */
    public function registerInvitation($user_id, $event)
    {
        $gdpr = [];

        $dataParticipant ['nome'] = $this->name;
        $dataParticipant ['cognome'] = $this->surname;
        $dataParticipant ['email'] = $this->email;
        $eventControllers = new EventController('event',
            \Yii::$app->getModule('events'));
        $ok = $eventControllers->addParticipant($event->id,
            $dataParticipant, $user_id, $gdpr);
        \open20\amos\core\models\UserActivityLog::registerLog(AmosEvents::t('amosevents', 'Registrazione ad un evento'), $event, Event::LOG_TYPE_SUBSCRIBE_EVENT, null, $user_id);

        return $ok;
    }

    /**
     *
     * @param type $model
     * @param type $data
     */
    public function saveCmsMailAfterLogin($model)
    {
        $appLink = \Yii::$app->params['platform']['backendUrl'] . '/';
        $link = $appLink . 'community/join?id=' . $this->community_id;

        $m = new Mustache_Engine;
        $cms_after = new CmsMailAfterLogin();

        $cms_after->layout_email = $this->getEmail_layout_after_login();
        $cms_after->email_from = $this->getFrom_email();
        $toField = $this->email_field;
        $tos = $model->$toField;
        $cms_after->email_to = $tos;
        $cms_after->subject = $this->getEmail_subject_after_login();
        $params = ArrayHelper::toArray($model);
        $params['link'] = $link;
        $text = $m->render($this->getEmail_after_login_text(),
            $params);
        $cms_after->body = $text;
        $cms_after->save(false);
        return $cms_after;
    }

    /**
     *
     * @param RecordDynamicModel $model
     */
    public function sendMail($event, $data, $waiting, $user = null, $isWaiting = null)
    {
        $linkToken = "";
        $mailup = false;

        $m = new Mustache_Engine;
        $result = "";
//        if (!is_null($user) && !empty($data['token_group_string_code'])) {
//
//            $linkToken = $this->getLinkWithToken($user->id,
//                $data['token_group_string_code']);
//        }
//        $params = ArrayHelper::toArray($model);
        $params = [];
        $params['token'] = $linkToken;
        if ($event) {
            $linkToken .= "&url_previous=" . urlencode(EventsUtility::getUrlLanding($event));
            $eventTemplates = $event->eventEmailTemplates;
            if ($eventTemplates) {
                if ($isWaiting) {
                    $text = $eventTemplates->info_waiting_list;
                    $subject = $eventTemplates->info_waiting_list_subject;

                } else {
                    $text = $eventTemplates->confirm_registration;
                    $subject = $eventTemplates->confirm_registration_subject;
                }

//                if (!empty($linkToken)) {
//                    $linkReg = "<p>Per completare la registrazione <a href='$linkToken'>clicca qui</a></p>";
//                    $linkReg .= "<p>In caso di problemi con il precedente link copia il seguente indirizzo ed incollalo nella barra indirizzo del tuo browser <a href='$linkToken'>.$linkToken.</a></p>";
//
//                    if (strpos($text, "Gentile {NOME} {COGNOME},") >= 0) {
//                        $text = str_replace("Gentile {NOME} {COGNOME},", "Gentile {NOME} {COGNOME},<br>" . $linkReg, $text);
//                    } else {
//                        $text .= $linkReg;
//                    }
//                }
                $subject = \open20\amos\events\utility\EventMailUtility::parseEmailWithParams($event, $user->id, $subject, false);
                $text = \open20\amos\events\utility\EventMailUtility::parseEmailWithParams($event, $user->id, $text);

                $data['email_subject'] = $subject;
                $mailup = true;
            }
        } else {
            if ($waiting) {
                $text = $m->render($data['email_waiting_list_text'], $params);
            } else {
                $text = $m->render($data['email_text'], $params);
            }
        }
        $result = $this->baseSendMail($data, $text, $mailup, $event);

        return $result;
    }

    /**
     *
     * @param type $user
     * @param type $model
     * @param type $data
     * @return type
     */
    public function sendNewAccountMail($event, $user, $data, $isWaiting = false)
    {
        $m = new Mustache_Engine;
        $mailup = false;
        $result = false;
        $linkToken = "";
        $appLink = Yii::$app->params['platform'] ['backendUrl'] . "/";
//        if (!is_null($user) && !empty($data['token_group_string_code'])) {
//
//            $linkToken = $this->getLinkWithToken($user->id,
//                $data['token_group_string_code']);
//        } else {
        $linkToken = $appLink . 'admin/security/insert-auth-data?token=' . $user->password_reset_token;
//        }
        $params = [];
        $params['token'] = $linkToken;
        if ($event) {
            $linkToken .= "&url_previous=" . urlencode(EventsUtility::getUrlLanding($event));
            $eventTemplates = $event->eventEmailTemplates;
            if ($eventTemplates) {
                if ($isWaiting) {
                    $text = $eventTemplates->info_waiting_list;
                    $subject = $eventTemplates->info_waiting_list_subject;

                } else {
                    $text = $eventTemplates->confirm_registration;
                    $subject = $eventTemplates->confirm_registration_subject;
                }

                if (!empty($linkToken) && empty($this->userSocial)) {
                    $linkReg = "<p>Per completare la registrazione <a href='$linkToken'>clicca qui</a></p>";
                    $linkReg .= "<p>In caso di problemi con il precedente link copia il seguente indirizzo ed incollalo nella barra indirizzo del tuo browser <a href='$linkToken'>.$linkToken.</a></p>";

                    if (strpos($text, "Gentile {NOME} {COGNOME},") >= 0) {
                        $text = str_replace("Gentile {NOME} {COGNOME},", "Gentile {NOME} {COGNOME},<br>" . $linkReg, $text);
                    } else {
                        $text .= $linkReg;
                    }
                }
                $subject = \open20\amos\events\utility\EventMailUtility::parseEmailWithParams($event, $user->id, $subject, false);
                $text = \open20\amos\events\utility\EventMailUtility::parseEmailWithParams($event, $user->id, $text);
                $data['email_subject'] = $subject;
                $mailup = true;
            }
        } else {
            $text = $m->render($this->email_text_new_account, $params);
        }
        $result = $this->baseSendMail($data, $text, $mailup, $event);
        return $result;
    }

    /**
     * @param $model
     * @param $data
     * @param $message
     * @param bool $mailup
     * @param null $event
     * @return mixed
     */
    private function baseSendMail($data, $message, $mailup = false, $event = null)
    {
        /**
         * $data => [
         *  'from_email' => 'email@exapmle.com',
         *  'ccn_email' => ['email@exapmle.com'],
         *  'email_subject' => ['Benvenuto sulla piattaforma']
         * ]
         */
        $mailModule = Yii::$app->getModule("email");
        if (isset($mailModule)) {
//            $from = $data['from_email'];
            $from = null;
            if (empty($form)) {
                $from = Yii::$app->params['supportEmail'];
            }

            if (!empty($data['email_layout'])) {
                $mailModule->defaultLayout = $data['email_layout'];
            }

            $text = $message;
            $ccn = [];
            if (!empty($data['ccn_email'])) {
                $ccn = [$data['ccn_email']];
            }

            $tos = $this->email;
            if ($mailup) {
                $result = \open20\amos\events\utility\EventMailUtility::sendEmailTest($from, $tos, $data['email_subject'], $message, $event, 'tag_unsubscribe_platform_footer');
            } else {
                $result = $mailModule->send($from, $tos, $data['email_subject'],
                    $text, [], $ccn, []);
            }
        }
        return $result;
    }

    /**
     *
     * @param type $user_id
     * @param type $event_string
     * @return string
     */
    public function getLinkWithToken($user_id, $event_string)
    {
        $link = null;
        $tokengroup = TokenGroup::getTokenGroup($event_string);

        if ($tokengroup) {

            $tokenUser = $tokengroup->generateSingleTokenUser($user_id);
            if (!empty($tokenUser)) {
                $link = $tokenUser->getBackendTokenLink();
            }
        }
        return $link;
    }

//    /**
//     * @return null
//     * @throws InvalidConfigException
//     */
//    public function getEvent()
//    {
//        $event = null;
//        $community_id = $this->getCommunityID();
//        $community = Community::findOne($community_id);
//        if ($community) {
//            $context = $community->context;
//            if ($context == 'open20\amos\events\models\Event') {
//                $event = Event::find()->andWhere(['community_id' => $community_id])->one();
//            }
//        }
//        return $event;
//    }


    /**
     * @param $event
     * @param $data
     * @return bool
     */
    public function isAlreadyPresent($event)
    {
        $ret = false;
        $isParticipant = EventInvitation::find()
            ->innerJoinWith('user')
            ->andWhere(['or',
                ['user.email' => $this->email],
                ['user.username' => $this->email]
            ])
            ->andWhere(['event_id' => $event->id])->count();

        return $isParticipant > 0;
    }


}