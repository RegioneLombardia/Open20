<?php

namespace app\modules\cms\utility;

use app\modules\cmsapi\frontend\models\CmsMailAfterLogin;
use open20\amos\admin\models\TokenGroup;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\record\RecordDynamicModel;
use open20\amos\core\user\User;
use open20\amos\events\AmosEvents;
use open20\amos\events\controllers\EventController;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventGroupReferentMm;
use open20\amos\socialauth\models\SocialAuthUsers;
use Exception;
use Hybrid_User_Profile;
use Mustache_Engine;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class RegisterUser extends BaseObject
{
    private $name_field = 'name';
    private $surname_field = 'surname';
    private $email_field = 'email';
    private $privacy_field = 'privacy';
    private $sex_field;
    private $age_field;
    private $county_field;
    private $city_field;
    private $telefon_field;
    private $fiscal_code_field;
    private $company_field;
    private $community_id;
    private $facilitator_id = null;
    private $send_credential = false;
    public $is_waiting = false;
    private $create_account_field;
    private $email_layout_after_login;
    private $from_email;
    private $email_subject_after_login;
    private $email_after_login_text;
    private $email_after_login;
    private $email_text_new_account;

    public function getEmail_after_login()
    {
        return $this->email_after_login;
    }

    public function setEmail_after_login($email_after_login)
    {
        $this->email_after_login = boolval($email_after_login);
    }

    public function getNameField()
    {
        return $this->name_field;
    }

    public function setNameField($name)
    {
        if (!empty($name)) {
            $this->name_field = $name;
        }
    }

    public function getSurnameField()
    {
        return $this->surname_field;
    }

    public function setSurnameField($name)
    {
        if (!empty($name)) {
            $this->surname_field = $name;
        }
    }

    public function getEmialField()
    {
        return $this->email_field;
    }

    public function setEmailField($name)
    {
        if (!empty($name)) {
            $this->email_field = $name;
        }
    }

    public function getPrivacyField()
    {
        return $this->privacy_field;
    }

    public function setProvacyField($name)
    {
        if (!empty($name)) {
            $this->privacy_field = $name;
        }
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

    public function getSexField()
    {
        return $this->sex_field;
    }

    public function getAgeField()
    {
        return $this->age_field;
    }

    public function getCountyField()
    {
        return $this->county_field;
    }

    public function getCityField()
    {
        return $this->city_field;
    }

    public function getTelefonField()
    {
        return $this->telefon_field;
    }

    public function getFiscalCodeField()
    {
        return $this->fiscal_code_field;
    }

    public function getCompanyField()
    {
        return $this->company_field;
    }

    public function setSexField($sex_field)
    {
        $this->sex_field = $sex_field;
    }

    public function setAgeField($age_field)
    {
        $this->age_field = $age_field;
    }

    public function setCountyField($county_field)
    {
        $this->county_field = $county_field;
    }

    public function setCityField($city_field)
    {
        $this->city_field = $city_field;
    }

    public function setTelefonField($telefon_field)
    {
        $this->telefon_field = $telefon_field;
    }

    public function setFiscalCodeField($fiscal_code_field)
    {
        $this->fiscal_code_field = $fiscal_code_field;
    }

    public function setCompanyField($company_field)
    {
        $this->company_field = $company_field;
    }

    /**
     * @param $model
     * @return null
     * @throws InvalidConfigException
     */
    public function registerToPlatform($model, $data, $isWaiting)
    {
        $emailField = $this->getEmialField();

        $giaRegistratoInPiattaforma = 0;
        $user = $this->isEmailRegisteredInPoi($model->$emailField);
        if (!is_null($user)) {
            $giaRegistratoInPiattaforma = 1;
            if ($data['send_mail']) {
                $community = Community::findOne($this->getCommunityID());
                $this->sendMail($model, $data, $isWaiting, $user, $isWaiting);
            }
        }
        $user = $this->RegisterUserToPlatform($model,
            $giaRegistratoInPiattaforma, $data, $isWaiting);
        return $user;
    }

    /**
     * @param $model
     * @param $giaRegistratoInPiattaforma
     * @return null
     * @throws InvalidConfigException
     */
    public function RegisterUserToPlatform($model, $giaRegistratoInPiattaforma,
                                           $data, $isWaiting = false)
    {
        $nameField = $this->getNameField();
        $surnameField = $this->getSurnameField();
        $emailField = $this->getEmialField();
        $privacyField = $this->getPrivacyField();

        /** @var  $community  Community */
        $community = Community::findOne($this->getCommunityID());
        $user = null;
        $accept_to_create_account = true;
        if ($data['register_on_platform_addicted']) {
            $cr_act_field = $this->getCreate_account_field();
            if (!empty($cr_act_field)) {
                $accept_to_create_account = boolval($model->$cr_act_field);
            }
        }
        if ($accept_to_create_account) {
            // creo un nuovo utente
            if ((empty($giaRegistratoInPiattaforma) || $giaRegistratoInPiattaforma
                == 0)) {
                UserProfileUtility::createNewAccount($model->$nameField,
                    $model->$surnameField, $model->$emailField, 1,
                    $this->getSendCredential());
                $user = User::find()->andWhere(['email' => $model->$emailField])->one();
                if ($user) {
                    if (!$this->getSendCredential()) {
                        $user->generatePasswordResetToken();
                        $user->save(false);
                    }
                    /** @var  $profile UserProfile */
                    $profile = $user->userProfile;
                    $profile->facilitatore_id = $this->getFacilitatorID();
                    $profile->first_access_redirect_url = !empty($this->getCommunityID())
                        ? '/community/join?id=' . $this->getCommunityID() : '';
                    if ($this->getEmail_after_login()) {
                        $cms_mail_after_login = $this->saveCmsMailAfterLogin($model);
                        $profile->first_access_redirect_url = \Yii::$app->params['platform']['frontendUrl'] . '/api/1/send-mail-after-login?id=' . $cms_mail_after_login->id . '&redirect=/community/join?id=' . $this->getCommunityID();
                    }
                    if (!$this->getSendCredential()) {
                        if ($data['send_mail']) {
                            $this->sendNewAccountMail($user, $model, $data, $isWaiting);
                        }
                    }

                    $profile->user_profile_role_id = 7;
                    $profile->user_profile_role_other = '';
                    $profile->privacy = !empty($privacyField) ? $model->$privacyField
                        : 0;
                    $profile->save(false);
                    $this->registerToCommunity($community, $user, $isWaiting);

                    //associo il social all'utente
                    if (!empty($user) && !empty($model->userSocial)) {
                        $userSocial = Json::decode($model->userSocial);
                        $socialProfile = $this->getClassHybridUserProfile($userSocial);
                        $this->createSocialUser($user->userProfile,
                            $socialProfile, $model->socialScelto);
                    }
                }
            } else {
                $user = User::find()->andWhere(['email' => $model->$emailField])->one();
                    if ($user) {
                        $this->registerToCommunity($community, $user, $isWaiting);
                }
            }
//            $model->user_id = $user->id;
//            $model->save();
        }
        $this->registerInvitation($user->id, $community, $model);
        $this->assignAutomaticSeat($user->id, $community);
        $this->assignToDg($user->id, $community, $model);
        return $user;
    }

    /**
     *
     * @param UserProfile $userprofile
     */
    protected function setUserProfileMoreFields(UserProfile $userprofile,
                                                array $data, $model)
    {
        if (!empty($this->getAgeField())) {
            $age = $this->getAgeField();
            $userprofile->user_profile_age_group_id = $model->$age;
        }
        if (!empty($this->getCityField())) {

        }
        if (!empty($this->getCountyField())) {

        }
        if (!empty($this->getCompanyField())) {
            $company = $this->getCompanyField();
            //$userprofile->company = $model->$company;
        }
        if (!empty($this->getFiscalCodeField())) {
            $code_f = $this->getFiscalCodeField();
            $userprofile->codice_fiscale = $model->$code_f;
        }
        if (!empty($this->getSexField())) {
            $sex = $this->getSexField();
            $userprofile->sesso = $model->$sex;
        }
        if (!empty($this->getTelefonField())) {
            $telefon = $this->getTelefonField();
            $userprofile->telefono = $model->$telefon;
        }
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
    public function assignAutomaticSeat($user_id, $community)
    {
        $context = $community->context;
        if ($context == 'open20\amos\events\models\Event') {
            $event = Event::find()->andWhere(['community_id' => $community->id])->one();
            if ($event->seats_management) {
                $seat = $event->assignAutomaticSeats($user_id);
                return $seat;
            }
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
    public function assignToDg($user_id, $community, $model)
    {
        $context = $community->context;
        if ($context == 'open20\amos\events\models\Event') {
            $event = Event::find()->andWhere(['community_id' => $community->id])->one();
            if ($event && $event->event_group_referent_id) {
                $member = new EventGroupReferentMm();
                $member->user_id = $user_id;
                $member->event_group_referent_id = $event->event_group_referent_id;
                $member->exclude_from_query = 0;
                $member->save(false);
                return true;
            }
        }
    }

    /**
     * @param $event_id
     * @param $user_id
     * @param $nameField
     * @param $surnameField
     * @param $emailField
     */
    public function registerInvitation($user_id, $community, $model)
    {
        $context = $community->context;
        if ($context == 'open20\amos\events\models\Event') {
            $event = Event::find()->andWhere(['community_id' => $community->id])->one();
            $gdpr = [];

            $nameField = $this->getNameField();
            $surnameField = $this->getSurnameField();
            $emailField = $this->getEmialField();

            $dataParticipant ['nome'] = $model->$nameField;
            $dataParticipant ['cognome'] = $model->$surnameField;
            $dataParticipant ['email'] = $model->$emailField;
            $eventControllers = new EventController('event',
                \Yii::$app->getModule('events'));
            $ok = $eventControllers->addParticipant($event->id,
                $dataParticipant, $user_id, $gdpr);
            \open20\amos\core\models\UserActivityLog::registerLog(AmosEvents::t('amosevents', 'Registrazione ad un evento'), $event, Event::LOG_TYPE_SUBSCRIBE_EVENT, null, $user_id);

            return true;
        }
        return false;
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
    public function sendMail($model, $data, $waiting, $user = null, $isWaiting = null)
    {
        $linkToken = "";
        $event = $this->getEvent();
        $mailup = false;

        $m = new Mustache_Engine;
        $result = "";
        if (!is_null($user) && !empty($data['token_group_string_code'])) {

            $linkToken = $this->getLinkWithToken($user->id,
                $data['token_group_string_code']);
        }
        $params = ArrayHelper::toArray($model);
        $params['token'] = $linkToken;

        if($event){
            $eventTemplates = $event->eventEmailTemplates;
            if($eventTemplates){
                if($isWaiting){
                    $text = $eventTemplates->info_waiting_list;
                    $subject = $eventTemplates->info_waiting_list_subject;

                }else {
                    $text = $eventTemplates->confirm_registration;
                    $subject = $eventTemplates->confirm_registration_subject;
                }
                $subject = \open20\amos\events\utility\EventMailUtility::parseEmailWithParams($event, $user->id, $subject, false);
                $text = \open20\amos\events\utility\EventMailUtility::parseEmailWithParams($event, $user->id, $text);
                if(!empty($linkToken)) {
                    $text .= "<p>Per completare la registrazione <a href='$linkToken'>clicca qui</a></p>";
                    $text .= "<p>In caso di problemi con il precedente link copia il seguente indirizzo ed incollalo nella barra indirizzo del tuo browser <a href='$linkToken'>.$linkToken.</a></p>";
                }
                $data['email_subject'] = $subject;
                $mailup = true;
            }
        }else {
            if ($waiting) {
                $text = $m->render($data['email_waiting_list_text'], $params);
            } else {
                $text = $m->render($data['email_text'], $params);
            }
        }
        $result = $this->baseSendMail($model, $data, $text, $mailup);

        return $result;
    }

    /**
     *
     * @param type $user
     * @param type $model
     * @param type $data
     * @return type
     */
    public function sendNewAccountMail($user, $model, $data, $isWaiting = false)
    {
        $m = new Mustache_Engine;
        $event = $this->getEvent();
        $mailup = false;

        $result = false;
        $linkToken = "";
        $appLink = Yii::$app->params['platform'] ['backendUrl'] . "/";
        if (!is_null($user) && !empty($data['token_group_string_code'])) {

            $linkToken = $this->getLinkWithToken($user->id,
                $data['token_group_string_code']);
        } else {
            $linkToken = $appLink . 'admin/security/insert-auth-data?token=' . $user->password_reset_token;
        }
        $params = ArrayHelper::toArray($model);
        $params['token'] = $linkToken;
        if($event){
            $eventTemplates = $event->eventEmailTemplates;
            if($eventTemplates){
                if($isWaiting){
                    $text = $eventTemplates->info_waiting_list;
                    $subject = $eventTemplates->info_waiting_list_subject;

                }else {
                    $text = $eventTemplates->confirm_registration;
                    $subject = $eventTemplates->confirm_registration_subject;
                }
                $subject = \open20\amos\events\utility\EventMailUtility::parseEmailWithParams($event, $user->id, $subject, false);
                $text = \open20\amos\events\utility\EventMailUtility::parseEmailWithParams($event, $user->id, $text);
                $text.="<p>Per completare la registrazione <a href='$linkToken'>clicca qui</a></p>";
                $text.="<p>In caso di problemi con il precedente link copia il seguente indirizzo ed incollalo nella barra indirizzo del tuo browser <a href='$linkToken'>.$linkToken.</a></p>";
                $data['email_subject'] = $subject;
                $mailup = true;
            }
        }else {
            $text = $m->render($this->email_text_new_account, $params);
        }
        $result = $this->baseSendMail($model, $data, $text, $mailup);
        return $result;
    }

    /**
     *
     * @param type $model
     * @param type $data
     * @param type $message
     */
    private function baseSendMail($model, $data, $message, $mailup = false)
    {
        $mailModule = Yii::$app->getModule("email");
        if (isset($mailModule)) {
            $from = $data['from_email'];
            if(empty($form)){
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

            $toField = $data['to_form_field'];
            $tos = [$model->$toField];
            if($mailup){
                $result = \open20\amos\events\utility\EventMailUtility::sendEmailTest($from, $tos, $data['email_subject'], $message);
            }else {
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

    /**
     * @return null
     * @throws InvalidConfigException
     */
    public function getEvent(){
        $event = null;
        $community_id = $this->getCommunityID();
        $community = Community::findOne($community_id);
        if($community) {
            $context = $community->context;
            if ($context == 'open20\amos\events\models\Event') {
                $event = Event::find()->andWhere(['community_id' => $community_id])->one();
            }
        }
        return $event;
    }
}