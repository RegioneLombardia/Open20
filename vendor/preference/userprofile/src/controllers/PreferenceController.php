<?php

namespace preference\userprofile\controllers;

use DateTime;
use open20\amos\admin\models\UserOtpCode;
use open20\amos\admin\models\UserProfile;
use open20\amos\comuni\models\IstatProvince;
use open20\amos\core\controllers\BackendController;
use open20\amos\core\user\User;
use Exception;
use preference\userprofile\exceptions\HandlePreferenceException;
use preference\userprofile\models\IstatComuni;
use preference\userprofile\models\PersonalData;
use preference\userprofile\models\PreferenceChannel;
use preference\userprofile\models\PreferenceTopicChannelMm;
use preference\userprofile\models\PreferenceUsernameValidationToken;
use preference\userprofile\models\PreferenceUserTargetAttribute;
use preference\userprofile\models\StepPersonalData;
use preference\userprofile\models\Tag;
use preference\userprofile\models\UserChannel;
use preference\userprofile\models\UserEmail;
use preference\userprofile\models\UserPassword;
use preference\userprofile\models\UserProfile as MyUserProfile;
use preference\userprofile\models\UserUpdateEmail;
use preference\userprofile\models\ValidatePhone;
use preference\userprofile\utility\EmailUtility;
use preference\userprofile\utility\TargetAttributeUtility;
use preference\userprofile\utility\TargetTagUtility;
use preference\userprofile\utility\TopicTagUtility;
use preference\userprofile\utility\UserInterestTagUtility;
use preference\userprofile\utility\UserProfileUtility;
use Yii;
use yii\base\BaseObject;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\Cookie;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;
use backend\modules\campains\utility\HardBouncedEmailUtlity;
use app\assets\ResourcesAsset;
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    preference\userprofile\controllers
 * @category   CategoryName
 */

/**
 * Description of BaseController
 *
 */
class PreferenceController extends BackendController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['settings'],
                'rules' => [
                    [
                        'actions' => [
                            'settings',
                            'modal-preference-setting',
                            'user-profile',
                            'enable-target-ajax',
                            'disable-target-ajax',
                            'validate-phone-ajax',
                            'send-validation-token-phone-ajax',
                            'send-validation-token-email-ajax',
                            'user-profile-update-email',
                            'user-profile-email',
                            'user-profile-password',
                            'delete-profile',
                            
                        ],
                        'allow' => true,
                        'roles' => ['PC_REGISTERD_USER'],
                    ],
                    [
                        'actions' => [
                            'set-dl-semplification-modal-cookie',
                            'validate-contact-email',
                            'validate-registration-email',
                            'test-email',
                            'updated-email',
                            'updated-password',
                            'validated-email-ok',
                            'validated-email-ko',
                            'registration-confirmed',
                            'email-validation-success',
                            'app-end-point',
                        ],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],


                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'settings' => ['get', 'post'],
                    'modal-preference-setting' => ['get'],
                ],
            ],
        ];
    }

    public function init()
    {
        // Layout Bootstrap Italia
        $this->setUpLayout('@vendor/open20/design/src/views/layouts/bi-main-layout');
        $this->view->params['customClassMainContent'] = 'container';
        $this->view->params['hideUserMenuHeader'] = false;
        ResourcesAsset::register($this->getView());
        parent::init();
    }

    /**
     *
     * @return void
     */
    public function actionSettings($target = null)
    {
        $nameTopicSelected = 'topic_selected';
        $nameTopicDeselected = 'topic_deselected';

        //  VarDumper::dump(Yii::$app->request->post(), $depth = 10, $highlight = true); die;

        // se il target mi arriva da un post
        if (is_null($target)) {
            $target = Yii::$app->request->post('target', null);
        }

        // se target non arriva nè da get nè da post allora prendo il primo
        if (empty($target)) {
            $target = TargetTagUtility::getFirstTargetTag(Yii::$app->user->id)->codice;
        }

        $loggedUserProfile = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
        if (empty($loggedUserProfile)) {
            throw new UnauthorizedHttpException();
        }

        $targetTag = TargetTagUtility::getTargetByCode($target);
        if (empty($targetTag)) {
            throw new BadRequestHttpException();
        }

        /** @var PreferenceUserTargetAttribute $targetAttributes */
        $targetAttributes = TargetAttributeUtility::getAttributesByUserCode(Yii::$app->user->id, $target);
        //VarDumper::dump( $targetAttributes->toArray(), $depth = 3, $highlight = true);

        if (Yii::$app->request->post()) {
            //  VarDumper::dump( Yii::$app->request->post(), 3, $highlight = true);

            $userChannel = new UserChannel();
            if ($userChannel->load(Yii::$app->request->post())) {

                // Salvo i canali poi la preferenza + controllo messaggio di errore
                try {
                    $this->saveChannels($userChannel);

                    // MESSAGGI personalizzati in base alla situazione!
                    // 1) se la tematica è stata salvata senza canali allora diamo un warning
                    $warningMessageFlag = false;
                    if (count($userChannel->channels) == 0) {
                        Yii::$app->session->addFlash('modal', [
                                'title' => 'Preferenza attivata con successo',
                                'description' => 'Attenzione: seleziona almeno un canale di contatto per ricevere notizie relative alla tematica indicata',
                                'icon' => Url::to('/img/warning.svg'),
                                'icon-alt' => 'icona spunta vede di successo',
                                'ok-button-label' => 'OK Grazie'
                            ]
                        );
                        $warningMessageFlag = true;
                    } elseif ($this->checkChannelsAndAttributesWarning($userChannel->channels, $targetAttributes)) {
                        Yii::$app->session->addFlash('modal', [
                                'title' => 'Preferenza attivata con successo',
                                'description' => 'Attenzione: inserisci e valida i dati di contatto per ricevere le notizie di Lombardia Informa',
                                'icon' => Url::to('/img/warning.svg'),
                                'icon-alt' => 'icona spunta vede di successo',
                                'ok-button-label' => 'OK Grazie'
                            ]
                        );
                        $warningMessageFlag = true;
                    }

                    $ret = $this->savePreference($loggedUserProfile, Tag::findOne($userChannel->tag_id)); 
                    if ($ret) {
                        // se non ho già messaggi di warning... ed è stata attivata un'altra tematica...
                         if (!$warningMessageFlag) {
                            // tutto ok - situazione di messaggio OK!
                            Yii::$app->session->addFlash('modal', [
                                    'title' => 'Preferenza attivata con successo',
                                    'description' => 'La tematica è stata aggiunta alle tue preferenze, riceverai comunicazioni sui canali da te indicati',
                                    'icon' => Url::to('/img/success.svg'),
                                    'icon-alt' => 'icona spunta vede di successo',
                                    'ok-button-label' => 'OK Grazie'
                                ]
                            );
                        }
                    } else {
                        // do nothing
                        // andrebbe un messaggio per dire, ok modifica della tematica andata a buon fine... ma per ora non si vuole fare.
                    }
                } catch (Exception $e) {
                    Yii::$app->session->addFlash('modal', [
                        'title' => 'Attivazione preferenza non riuscita',
                        'description' => 'Ti ricordiamo che per poter attivare le preferenze e ricevere le comunicazioni è necessario inserire almeno una <b>modalità di contatto</b>',
                        'icon' => Url::to('/img/phone-alert.svg'),
                        'icon-alt' => 'icona telefono con simbolo di attenzione',
                        'ok-button-label' => 'OK Grazie'
                        ]
                    );
                }
                //  } 

            }

            if (!empty(Yii::$app->request->post($nameTopicDeselected))) {
                // REMOVE PREFERENCE
                $this->removePreference($loggedUserProfile, Tag::findOne(Yii::$app->request->post($nameTopicDeselected)));                
            }

            if ($targetAttributes->load(Yii::$app->request->post()) && $targetAttributes->validate()) {
                $targetAttributes->save();
            }
        }

        //  VarDumper::dump( $nameTopicSelected, $depth = 10, $highlight = true);

        $hbEmails = HardBouncedEmailUtlity::allHBEmailForUser(Yii::$app->user->id);

        return $this->render("settings", [
            'listOfTarget' => TargetTagUtility::getAllTargetTag(),
            'currentTargetCode' => $target,
            'currentTargetTag' => $targetTag,
            'allUserSelectedTopics' => UserInterestTagUtility::getUserInterestsTagByTarget($loggedUserProfile->user->id, $target),
            'allTopicsChoices' => TopicTagUtility::getAllTopicByTargetCode($target),
            'nameTopicSelected' => $nameTopicSelected,
            'nameTopicDeselected' => $nameTopicDeselected,
            'targetAttributes' => $targetAttributes,
            'isTargetActive' => TargetTagUtility::isTargetSelectedForUser($targetTag, $loggedUserProfile->user->id),
            'isHBEmail' => (count($hbEmails) > 0),
            'hbEmail' => $hbEmails,
        ]);
    }

    /**
     * @param $channels
     * @param PreferenceUserTargetAttribute $targetAttributes
     * @return bool
     */
    private function checkChannelsAndAttributesWarning($channels, $targetAttributes)
    {
        $toret = false;
        foreach ($channels as $channelId) {
            if (PreferenceChannel::NEWSLETTER_ID == $channelId){
                if (empty($targetAttributes->email) || ($targetAttributes->validated_email_flag != 1)){
                    $toret = true;
                }
            }

            if (PreferenceChannel::SMS_ID == $channelId){
                if (empty($targetAttributes->phone) || ($targetAttributes->validated_phone_flag != 1)){
                    $toret = true;
                }
            }
        }
        return $toret;
    }

    private function savePreference($userProfile, $tag)
    {
        $interest_classname = 'simple-choice';
        $ret = UserInterestTagUtility::saveRegisteredUserInterestTag($userProfile, $interest_classname, $tag);
        if ($ret === false) {
            return false;
        } else {
            return true;
        }
        // // ogni volta che viene aggiunta come preferenza una nuova tematica
        // // 1 elimino tutti i canali sulla tematica/topic dell'utente
        // // 2 li abilito e quindi li creo nuovi, di default abilitiamo tutti i canali
        // UserInterestTagUtility::deletePreferenceTopicChannelByUserAndCode($userProfile->user->id, $tag);
        // UserInterestTagUtility::addAllPreferenceTopicChannelByUserAndCode($userProfile->user->id, $tag);
    }

    private function removePreference($userProfile, $tag)
    {
        // vengono rimossi anche i canali di comunicazione sulla tematica
        UserInterestTagUtility::deletePreferenceTopicChannelByUserAndCode($userProfile->user->id, $tag);

        // die(1);
        // ora elimino la preferenza su questa tematica
        UserInterestTagUtility::removeRegisteredUserInterestTag($userProfile, $tag);
    }

    public function actionModalPreferenceSetting($id, $form, $addTopic = 'false')
    {
        $tag = Tag::findOne($id);
        $userChannel = new UserChannel();
        $tagRoot = Tag::findOne($tag->root);
        // $listOfTopicChannes = ArrayHelper::map(PreferenceTopicChannelMm::find()
            // ->where(['user_id' => Yii::$app->user->id])
            // ->andWhere(['topic_code' => $tag->codice])
            // ->all(), 'preference_channel_id', 'preferenceSendingFrequency');
        return $this->renderPartial('_modal_preference_setting', [
            'tag' => $tag,
            'tagRoot' => $tagRoot,
            'userChannel' => $userChannel,
            'formId' => $form,
            // 'listOfTopicChannes' => $listOfTopicChannes,
            'disableTopic' => ($addTopic == 'true')? false: true,
        ]);
    }

    /**
     * Undocumented function
     *
     * @param UserChannel $userChannel
     * @return void
     */
    private function saveChannels($userChannel)
    {
        $listOfChannels = $userChannel->channels;
        $tag = Tag::findOne($userChannel->tag_id);
        UserInterestTagUtility::deletePreferenceTopicChannelByUserAndCode($userChannel->user_id, $tag);
        foreach ($listOfChannels as $channelId) {
            UserInterestTagUtility::addSinglePreferenceTopicChannel($userChannel->user_id, $tag, $channelId);
        }
    }

    /**
     * Undocumented function
     *
     * @param string $token
     * @return void
     */
    public function actionValidateContactEmail($token)
    {
        $utas = PreferenceUserTargetAttribute::findAll(['email_validation_token' => $token]);
        if (!empty($utas)) {
            $tcs = [];
            foreach ($utas as $uta) {
                $uta->validated_email_flag = 1;
                $uta->email_validation_token = null;
                $uta->save(false);

                $tcs[] = TargetTagUtility::getTargetByCode($uta->target_code)->nome ;
            }
            
            return $this->redirect(['email-validation-success', 'email' => $uta->email, 'tc' => implode(', ',$tcs)]);
        }
        return $this->render('validated_contact_email_ko', []);
    }

    /**
     * Undocumented function
     *
     * @param string $token
     * @return void
     */
    public function actionValidateRegistrationEmail($token)
    {
        $usernameValidation = PreferenceUsernameValidationToken::findOne(['token' => $token]);
        //VarDumper::dump( $usernameValidation, $depth = 12, $highlight = true); die;
        if (!empty($usernameValidation)) {
            $user = $usernameValidation->user;
            $user->status = 10;
            $user->save(false);
            $usernameValidation->delete();
            return $this->redirect('registration-confirmed');
        }
        return $this->render('validated_registration_email_ko', []);
    }

    // Decommenta per accedere alle view direttamente...  
    // public function actionValidatedContactEmailKo()
    // {
    //     return $this->render('validated_contact_email_ko', []);
    // }
    // public function actionValidatedRegistrationEmailKo()
    // {
    //     return $this->render('validated_registration_email_ko', []);
    // }

    /**
     * Validazione ajax del cellulare
     */
    public function actionValidatePhoneAjax()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = \Yii::$app->request->post();
        $uta = PreferenceUserTargetAttribute::find()
            ->where(['phone_validation_token' => $request['token']])
            ->andWhere(['user_id' => Yii::$app->user->id])->one();
        // VarDumper::dump( \Yii::$app->request->post(), $depth = 10, $highlight = true);
        if (!empty($uta)) {
            $uta->phone_validation_token = null;
            $uta->validated_phone_flag = 1;
            $uta->save(false);
            return 'true';
        } else {
            return 'false';
        }
    }

    public function actionSendValidationTokenPhoneAjax()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = \Yii::$app->request->post();
        $uta = TargetAttributeUtility::getAttributesByUserCode(Yii::$app->user->id, $request['target_code']);

        if (!empty($uta)) {
            $uta->phoneModified();
            $uta->save(false);
            return 'true';
        } else {
            return 'false';
        }
    }

    /**
     * @return string
     */
    public function actionSendValidationTokenEmailAjax(): string
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = \Yii::$app->request->post();
        /** @var PreferenceUserTargetAttribute $uta */
        $uta = TargetAttributeUtility::getAttributesByUserCode(Yii::$app->user->id, $request['target_code']);

        if (!empty($uta) && !empty($uta->email_validation_token)) {
            /** @var Tag $tag */
            $tag = TargetTagUtility::getTargetByCode($uta->target_code);
            try {
                EmailUtility::sendTargetMailValidationToken($uta->email_validation_token
                    , $uta->email
                    , 'Lombardia Informa: validazione email di contatto'
                    , $tag->nome);
            } catch (Exception $e) {
                return 'false';
            }
            return 'true';
        } else {
            return 'false';
        }
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function actionUserProfile()
    {
        /** @var UserProfile $loggedUserProfile */
        $loggedUserProfile = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
        if (empty($loggedUserProfile)) {
            throw new UnauthorizedHttpException();
        }

        // VarDumper::dump( $loggedUserProfile->sesso, 3,true);

        $personalData = new PersonalData();
        $personalData->name = $loggedUserProfile->nome;
        $personalData->surname = $loggedUserProfile->cognome;
        
        $personalData->gender = (empty($loggedUserProfile->sesso))? null: (($loggedUserProfile->sesso == 'Maschio')? 'm': 'f');

        $date = DateTime::createFromFormat ('Y-m-d', $loggedUserProfile->nascita_data);
        //VarDumper::dump( $date->format('d/m/Y'), $depth = 10, $highlight = true);
        $personalData->birth_date = ($date)? $date->format('d/m/Y'): null;
        $personalData->residence_city = $loggedUserProfile->comune_residenza_id;
        $personalData->residence_province = $loggedUserProfile->provincia_residenza_id;
        $personalData->fiscal_code = $loggedUserProfile->codice_fiscale;

        if (Yii::$app->request->post()) {

            if ($personalData->load(Yii::$app->request->post()) && $personalData->validate()) {
                $loggedUserProfile->nome = $personalData->name;
                $loggedUserProfile->cognome = $personalData->surname;
                $loggedUserProfile->sesso  = (empty($personalData->gender))? null: (($personalData->gender == 'm')? 'Maschio': 'Femmina');

                $date = DateTime::createFromFormat ('d/m/Y', $personalData->birth_date);
                $loggedUserProfile->nascita_data = ($date)? $date->format('Y-m-d'): null;

                $loggedUserProfile->comune_residenza_id = $personalData->residence_city;
                $loggedUserProfile->provincia_residenza_id = $personalData->residence_province;
                $loggedUserProfile->codice_fiscale = $personalData->fiscal_code;

                $ret = $loggedUserProfile->save(false);
                if ($ret) {
                    Yii::$app->session->addFlash('success', 'Dati profilo salvati correttamente');
                }
                
            }

            if (Yii::$app->request->post('target_code_to_modify')) {
                $targetAttributes = TargetAttributeUtility::getAttributesByUserCode(Yii::$app->user->id, Yii::$app->request->post('target_code_to_modify'));
                if ($targetAttributes->load(Yii::$app->request->post()) && $targetAttributes->validate()) {
                    $targetAttributes->save();
                }
            }
        }

        $items = ArrayHelper::map(IstatProvince::find()->orderBy('nome')->all(), 'id', 'nome');

        return $this->render('user_profile', [
            'listOfTarget' => TargetTagUtility::getAllTargetTag(),
            'userProfile' => $loggedUserProfile,
            'model' => $personalData,
            'items' => $items,
        ]);
    }

    public function actionEnableTargetAjax()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $request = \Yii::$app->request->post();
            $userProfile = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
            $targetTag = TargetTagUtility::getTargetByCode($request['target_code']);
            $interest_classname = 'simple-choice';

            if (!TargetTagUtility::isTargetSelectedForUser($targetTag, $userProfile->user->id)) {
                UserInterestTagUtility::saveRegisteredUserInterestTag($userProfile, $interest_classname, $targetTag);
            }

            $uta = TargetAttributeUtility::getAttributesByUserCode($userProfile->user->id, $request['target_code']);

            if (empty($uta)) {
                $uta = new PreferenceUserTargetAttribute();
                $uta->target_code = $request['target_code'];
                $uta->user_id = $userProfile->user->id;
                $uta->save();
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return 'true';
    }

    public function actionDisableTargetAjax()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $request = \Yii::$app->request->post();
            $userProfile = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
            $targetTag = TargetTagUtility::getTargetByCode($request['target_code']);

            if (count(TargetTagUtility::getAllTargetTagForUSer($userProfile->user->id)) <= 1) {
                return 'Devi mantenere almeno un target in interesse';
            }

            // Quando si disattiva un profilo bisogna preserrvare le selezioni effettuate sulle tematiche e non resettarle
            // $listTagToDelete = UserInterestTagUtility::getUserInterestsTagByTarget($userProfile->user->id, $targetTag->codice);
            // foreach ($listTagToDelete as $key => $tag) {
            //     UserInterestTagUtility::removeRegisteredUserInterestTag($userProfile, $tag);
            // }
            UserInterestTagUtility::removeRegisteredUserInterestTag($userProfile, $targetTag);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return 'true';
    }

    // /**
    //  * Undocumented function
    //  *
    //  * @return void
    //  */
    // public function actionTestEmail()
    // {
    //     // EmailUtility::sendTargetMailValidationToken('test-12344555', 'michele.zucchini+TARGET@open20.it', 'oggetto', 'Cittadini');
    //     EmailUtility::sendUserMailRegistrationOk('michele.zucchini+USER@open20.it', 'oggetto');
    // }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function actionUserProfileEmail()
    {
        //Annullo la action

        throw new ForbiddenHttpException('');
        $loggedUserProfile = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
        if (empty($loggedUserProfile)) {
            throw new UnauthorizedHttpException();
        }

        $model = new UserEmail();

        if (Yii::$app->request->post()) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // VarDumper::dump( $model->toArray(), $depth = 10, $highlight = true);

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $valToken = new PreferenceUsernameValidationToken();
                    $valToken->token = TargetAttributeUtility::generateNewEmailToken();
                    $valToken->user_id = $loggedUserProfile->user->id;
                    $valToken->save(false);
                    // modifico lo user con quello nuovo
                    $user = $loggedUserProfile->user;
                    $user->username = $model->email;
                    $user->email = $model->email;
                    $user->status = 0;
                    $user->save(false);
                    $transaction->commit();
                    EmailUtility::sendUserMailValidationToken($valToken->token, $user->username, 'Lombardia Informa, conferma registrazione');

                    Yii::$app->user->logout();
                    $this->redirect(Url::to(['updated-email', 'email' => $user->email]));
                } catch (Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }

        return $this->render('user_profile_email', [
            'model' => $model,
        ]);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function actionUserProfileUpdateEmail()
    {
        $loggedUserProfile = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
        if (empty($loggedUserProfile)) {
            throw new UnauthorizedHttpException();
        }

        // se esco ed entro dalla finestra di modifica, i token legati all'utente li elimino
        if (!Yii::$app->request->post()) {
            UserOtpCode::deleteAll(['user_id' => \Yii::$app->user->id, 'type' => UserOtpCode::TYPE_AUTH_EMAIL]);
        }

        $model = new UserUpdateEmail();
        $otp = $this->validOtpExist();
        // se esiste un OTP valido allora setto lo scenario per l'inserimento dell'OTP
        if (!empty($otp)) {
            $model->setScenario(UserUpdateEmail::SCENARIO_OTP);
        } else {
            $model->setScenario(UserUpdateEmail::SCENARIO_EMAIL);
        }

        if (Yii::$app->request->post()) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // modifico lo user con quello nuovo
                    $user = $loggedUserProfile->user;
                    $user->email = $model->email;
                    $user->username = $model->email;
                    $user->save(false);
                    $transaction->commit();

                    Yii::$app->session->addFlash('success', 'Email modificata correttamente');
                    return $this->redirect(Url::to(['user-profile']));
                } catch (Exception $e) {
                    Yii::$app->session->addFlash('error', 'Email non modificata correttamente');
                    $transaction->rollBack();
                    return $this->redirect(Url::to(['user-profile']));
                }
            } else {
                $otp = $this->validOtpExist();
            }
        }

        $dateExpire = '';
        if (!empty($otp)) {
            $formatter = \Yii::$app->formatter;
            $dateExpire = $formatter->asDatetime($otp->expire, 'H:m');
        }

        return $this->render('user_profile_update_email', [
            'model' => $model,
            'dateExpire' => $dateExpire
        ]);
    }

    private function validOtpExist()
    {
        $authentication = UserOtpCode::find()->andWhere(['user_id' => \Yii::$app->user->id, 'type' => UserOtpCode::TYPE_AUTH_EMAIL])->one();
        if (!empty($authentication) ) {
            return $authentication;
        }
        return false;
    }

    /**
     */
    public function actionUpdatedEmail($email = '')
    {
        return $this->render('updated_email', [
            'email' => $email,
        ]);
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function actionUserProfilePassword()
    {
        $loggedUserProfile = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
        if (empty($loggedUserProfile)) {
            throw new UnauthorizedHttpException();
        }

        $model = new UserPassword();

        if (Yii::$app->request->post()) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // VarDumper::dump( $model->toArray(), $depth = 10, $highlight = true);

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // modifico lo user con quello nuovo
                    $user = $loggedUserProfile->user;
                    $user->setPassword($model->password);
                    $user->save(false);
                    $transaction->commit();

                    Yii::$app->user->logout();
                    $this->redirect(Url::to(['updated-password']));
                } catch (Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }

        return $this->render('user_profile_password', [
            'model' => $model,
            'userProfile' => $loggedUserProfile,
        ]);
    }

    /**
     */
    public function actionUpdatedPassword()
    {
        return $this->render('updated_password', []);
    }


    public function actionValidatedEmailOk()
    {
        return $this->render('validated_email_ok', []);
    }

    public function actionRegistrationConfirmed()
    {
        return $this->render('registration_confirmed', []);
    }

    public function actionEmailValidationSuccess($email, $tc)
    {
        // $tag = TargetTagUtility::getTargetByCode($tc);
        return $this->render('contact_email_validation_success', [
            'email' => HtmlPurifier::process($email),
            'labelTarget' => HtmlPurifier::process($tc),
        ]);
    }

    public function actionDeleteProfile()
    {

        $loggedUserProfile = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
        if (empty($loggedUserProfile)) {
            throw new UnauthorizedHttpException();
        }

        try {
            UserProfileUtility::deleteProfile($loggedUserProfile);
            Yii::$app->user->logout();
            $this->redirect(Url::to(['/']));
        } catch (Exception $e) {
            throw $e;
        }

    }

    public function actionAppEndPoint($token)
    {
        echo('ok'); die;
    }

    /**
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function actionSetDlSemplificationModalCookie()
    {
        if (!\Yii::$app->request->isAjax || !\Yii::$app->request->isPost) {
            throw new ForbiddenHttpException(Yii::t('amoscore', 'Non sei autorizzato a visualizzare questa pagina'));
        }
        $expireDate = new \DateTime('2021-09-30 23:59:59');
        $cookie = new Cookie([
            'name' => 'dl_semplification_modal_cookie',
            'value' => '1',
            'expire' => $expireDate->getTimestamp()
        ]);
        Yii::$app->getResponse()->getCookies()->add($cookie);
        return true;
    }


}
