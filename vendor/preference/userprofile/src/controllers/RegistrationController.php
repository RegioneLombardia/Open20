<?php

namespace preference\userprofile\controllers;

use backend\modules\userimporter\models\UserImportTaskUser;
use DateTime;
use open20\amos\admin\models\UserOtpCode;
use open20\amos\admin\models\UserProfile;
use open20\amos\comuni\models\IstatProvince;
use open20\amos\core\utilities\CurrentUser;
use open20\amos\socialauth\utility\SocialAuthUtility;
use preference\userprofile\exceptions\NotificationEmailException;
use preference\userprofile\models\base\PreferenceChannel;
use preference\userprofile\models\IstatComuni;
use open20\amos\core\controllers\BackendController;
use preference\userprofile\exceptions\CreationRegisterdUserException;
use preference\userprofile\exceptions\LoadWizardDataException;
use preference\userprofile\models\PreferenceTopicPrlToPc;
use preference\userprofile\models\PreferenceUserTargetAttribute;
use preference\userprofile\models\StepContacts;
use preference\userprofile\models\StepPersonalData;
use preference\userprofile\models\StepPreferences;
use preference\userprofile\models\StepPrivacy;
use preference\userprofile\utility\EmailUtility;
use preference\userprofile\utility\TargetAttributeUtility;
use preference\userprofile\utility\TargetTagUtility;
use preference\userprofile\utility\TopicTagUtility;
use preference\userprofile\utility\UserInterestTagUtility;
use preference\userprofile\utility\UserProfileUtility;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

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
class RegistrationController extends BackendController
{
    private $stepPreferenceSessionId = 'step-preferences';
    private $stepPersonalDataSessionId = 'step-personal-data';
    private $stepContactsSessionId = 'step-contacts';
    private $stepContactsPrivacyId = 'step-privacy';
    private $session = null;
    private $idmData = null;

    public function init()
    {
        $this->session = \Yii::$app->session;

        if (!$this->session->getIsActive()) {
            $this->session->open();
        }

//        VarDumper::dump(Yii::$app->controller->, 3, true); die;
        $this->idmData = Yii::$app->session->get('IDM');

        // Layout Bootstrap Italia
        $this->setUpLayout('@vendor/open20/design/src/views/layouts/bi-main-layout');
        $this->view->params['hideUserMenuHeader'] = true;


        parent::init();
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'preferences',
                    'personal-data',
                    'contacts',
                    'privacy',
                    'registrate-user',
//                    'test-data-ajax',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'preferences',
                            'personal-data',
                            'contacts',
                            'privacy',
                            'registrate-user',
//                            'test-data-ajax',
//                            'send-otp',
                        ],
                        'allow' => true,
                        'roles' => ['?', '@'],
                        'matchCallback' => function ($rule, $action) {
                            // BLOCCO l'accesso alle azioni del wizard se non esistono i dati in SPID.
                            // l'ultima azione della registrazione è l'eliminazione di questo dato in sessione.
                            $val = true;
                            if (empty(Yii::$app->session->get('IDM'))) {
//                                throw new HttpException('Impossibile recuperare i dati inviati da SPID');
                                $val = false;
                            }
                            return $val;
                        }
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'preferences' => ['get', 'post'],
                    'personal-data' => ['get', 'post'],
                    'contacts' => ['get', 'post'],
                    'privacy' => ['get', 'post'],
                    'registrate-user' => ['get', 'post'],
                    'test-data-ajax' => ['get', 'post'],
                ],
            ],
        ];
    }

    /**
     * STEP 1 - inserimento dati personali e di accesso
     *
     * @return string
     */
    public function actionPersonalData()
    {
        // $this->session->remove($this->stepPersonalDataSessionId);
        $model = new StepPersonalData();
        if ($this->session->has($this->stepPersonalDataSessionId)) {
            $model->load($this->session[$this->stepPersonalDataSessionId]);
        }

        // se ricarico la pagina resetto la situazione anche gli OTP della sessione corrente!
        if (!Yii::$app->request->post()) {
            UserOtpCode::deleteAll(['session_id' => \Yii::$app->session->getId()]);
        }

        // SET dello scenario
        // se esiste un OPT valido ripropongo lo scenario dell'OTP da inserire
        // altrimenti se già settata una mail lascio lo scenario di default
        // (che permette di salvare senza validare la mail - presumo che sia già validata se caricata in sessione!)
        // --- solo dopo al post controllo che sia cambiata la mail se è cambiata allora va rivalidata!
        if ($this->validOtpExist()) {
            $model->setScenario(StepPersonalData::SCENARIO_OTP);
        } else {
            if (empty($model->email)) {
                $model->setScenario(StepPersonalData::SCENARIO_EMAIL);
            } else {
                $model->setScenario(StepPersonalData::SCENARIO_DEFAULT);
            }
        }

        //$model->birth_date = date("d/m/Y", strtotime($model->birth_date));

        // 2 - salvo in sessione le scelte fatte
        if (Yii::$app->request->post()) {
            $step = Yii::$app->request->post('submit-action', null);
            if ($step == 'forward') {
                // controllo se hanno cambiato la mail!
                $emailOld = $model->email;
                $model->load(Yii::$app->request->post());

                // se mail modificata, allora setto lo scenario che valida la mail
                if (($emailOld != $model->email) && ($model->getScenario() != StepPersonalData::SCENARIO_OTP)){
                    $model->setScenario(StepPersonalData::SCENARIO_EMAIL);
                }

                if ($model->validate()) {
                    $this->session->remove($this->stepPersonalDataSessionId);
                    $this->session[$this->stepPersonalDataSessionId] = Yii::$app->request->post();
                    $this->redirect('preferences');
                }
            }
        }

        $items = ArrayHelper::map(IstatProvince::find()->orderBy('nome')->all(), 'id', 'nome');

        return $this->render("personal_data", [
            'model' => $model,
            'items' => $items,
            'idmData' => $this->idmData,
        ]);
    }

    private function validOtpExist()
    {
        $id_session = \Yii::$app->session->getId();
        $authentication = UserOtpCode::find()->andWhere(['session_id' => $id_session])->one();
        if (!empty($authentication) ) {
            return true;
        }
        return false;
    }

    /**
     * STEP 2 - la scelta delle preferenze
     *
     * @return void
     */
    public function actionPreferences($cod_tematica = null)
    {

        // 1 - recupero le tematiche che sono già state scelte
        $topicsModel = new StepPreferences();
        if ($this->session->has($this->stepPreferenceSessionId)) {
            $topicsModel->load($this->session[$this->stepPreferenceSessionId]);
        }

        if (!empty(Yii::$app->session->get('__cod_tematica'))) {
            $cod_tematica = Yii::$app->session->get('__cod_tematica');

            $listOfRel = PreferenceTopicPrlToPc::find()->where(['like', 'prl', $cod_tematica])->all();
            foreach ($listOfRel as $rel) {
                $select = TopicTagUtility::getCrossTopicCodeByName($rel->pc);
                $topicsModel->topics = ArrayHelper::merge($topicsModel->topics, [$select]);
            }

        }

        // 2 - salvo in sessione le scelte fatte
        if (Yii::$app->request->post()) {
            $step = Yii::$app->request->post('submit-action', null);
            if ($step == 'forward') {
                $topicsModel = new StepPreferences();
                $topicsModel->load(Yii::$app->request->post());
                $this->session->remove($this->stepPreferenceSessionId);
                $this->session[$this->stepPreferenceSessionId] = Yii::$app->request->post();

                $this->redirect('contacts');

            }
        }

        return $this->render("preferences", [
            'model' => $topicsModel,
        ]);
    }



    /**
     * STEP 3 - Scelta contatti - i target e le informazioni di contatto
     *
     * @return void
     */
    public function actionContacts()
    {

        $model = new StepContacts();
        if ($this->session->has($this->stepContactsSessionId)) {
            $model->load($this->session[$this->stepContactsSessionId]);
        }

        $errorMessage = '';

        // 2 - salvo in sessione le scelte fatte
        if (Yii::$app->request->post()) {
            
            $step = Yii::$app->request->post('submit-action', null);
            if ($step == 'forward') {               
                if ($model->load(Yii::$app->request->post())) {

                    // aggiusto i dati... i target non vengono modificati se non cekkati..
                    if (!isset(Yii::$app->request->post()['StepContacts']['target_cittadini'])) {
                        $model->target_cittadini = null;
                    }
                    if (!isset(Yii::$app->request->post()['StepContacts']['target_impresa'])) {
                        $model->target_impresa = null;
                    }
                    if (!isset(Yii::$app->request->post()['StepContacts']['target_enti_operatori'])) {
                        $model->target_enti_operatori = null;
                    }


                    if (!$this->checkAtLeastOneTarget($model)) {
                        $errorMessage = Yii::t('preferenceuser', 'Scegli almeno un target');
                    }

                    if (empty($errorMessage) && $model->validate()) {
                        $this->session->remove($this->stepContactsSessionId);
                        $this->session[$this->stepContactsSessionId] = Yii::$app->request->post();

                        $this->redirect('privacy');
                    }

                    // VarDumper::dump($model->errors, $depth = 10, $highlight = true);
                }
            }
        }

        // recupero la mail dallo STEP precedente per indicarla quando viene abilitato un target
        $email = $this->getRegistrationEmail();

        return $this->render("contacts", [
            'model' => $model,
            'errorMessage' => $errorMessage,
            'email' => $email
        ]);
    }

    private function getRegistrationEmail()
    {
        $email = null;
        $model = new StepPersonalData();
        if ($this->session->has($this->stepPersonalDataSessionId)) {
            $model->load($this->session[$this->stepPersonalDataSessionId]);
            $email = $model->email;
        } 
        return $email;
    }

    /**
     * 
     */
    private function checkAtLeastOneTarget(StepContacts $model)
    {
        if (!empty($model->target_cittadini)) {
            return true;
        }

        if (!empty($model->target_impresa)) {
            return true;
        }

        if (!empty($model->target_enti_operatori)) {
            return true;
        }

        return false;
    }

    /**
     * STEP 4 - Privacy
     *
     * @return void
     */
    public function actionPrivacy()
    {

        $model = new StepPrivacy();
        if ($this->session->has($this->stepContactsPrivacyId)) {
            $model->load($this->session[$this->stepContactsPrivacyId]);
        }

        // 2 - salvo in sessione le scelte fatte
        if (Yii::$app->request->post()) {
            $step = Yii::$app->request->post('submit-action', null);
            if ($step == 'forward') {
                $model->load(Yii::$app->request->post());
                if ($model->validate()) {
                    // VarDumper::dump( $model->toArray(), $depth = 10, $highlight = true); die;
                    $this->session->remove($this->stepContactsPrivacyId);
                    $this->session[$this->stepContactsPrivacyId] = Yii::$app->request->post();

                    $this->redirect('registrate-user');
                }
            }
        }

        return $this->render("privacy", [
            'model' => $model,
        ]);
    }

    /**
     * STEP FINE - Registra utente
     *
     * @return void
     */
    public function actionRegistrateUser()
    {

        //die('STOP registrazione');

        $preferencesModel = null;
        $personalDataModel = null;
        $contactsModel = null;
        $privacyModel = null;

        try {
            // Recupero dalla sessione i dati che l'utente ha inserito
            $preferencesModel = new StepPreferences();
            if (!($this->session->has($this->stepPreferenceSessionId) && $preferencesModel->load($this->session[$this->stepPreferenceSessionId]))) {
                // non sono obbligatori
                // throw new LoadWizardDataException();
            }

            $personalDataModel = new StepPersonalData();
            if (!($this->session->has($this->stepPersonalDataSessionId) && $personalDataModel->load($this->session[$this->stepPersonalDataSessionId]))) {
                throw new LoadWizardDataException();
            }

            $contactsModel = new StepContacts();
            if (!($this->session->has($this->stepContactsSessionId) && $contactsModel->load($this->session[$this->stepContactsSessionId]))) {
                throw new LoadWizardDataException();
            }

            $privacyModel = new StepPrivacy();
            if (!($this->session->has($this->stepContactsPrivacyId) && $privacyModel->load($this->session[$this->stepContactsPrivacyId]))) {
                throw new LoadWizardDataException();
            }
        } catch (LoadWizardDataException $we) {
            throw $we;
        }

        // i dati in sessione sono stati caricati sui model
        // inizio la costruzione dello user profile

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $module = \Yii::$app->getModule('amosadmin');
            $userCreatedArray = $module->createNewAccount(
                empty($personalDataModel->name) ? '-' : $personalDataModel->name,
                empty($personalDataModel->surname) ? '-' : $personalDataModel->surname,
                $personalDataModel->email,
                ($privacyModel->privacy == 'on')? true: false,
                false,
                null,
                null,
                'amosadmin'
            );
            

            if (isset($userCreatedArray['error']) && ($userCreatedArray['error'] >= 1)) {
                $str = VarDumper::dumpAsString($userCreatedArray['messages'], $depth = 10, $highlight = false);
                throw new CreationRegisterdUserException('Impossibile creare l\'anagrafica: ' . $str);
            }

            // utente creato vado a settare i dati inseriti in registrazione.
            // 1 inserico i dati sul profilo
            $user = $userCreatedArray['user'];
            // DL semplificazioni (no password + utente creato già abilitato satus 10)
//            $user->setPassword($personalDataModel->password);
//            $user->password_reset_token = null;
            $user->status = 10;
            $user->save(false);

            /** @var UserProfile $userProfile */
            $userProfile =  $user->userProfile;
            $userProfile->nome = empty($personalDataModel->name) ? '' : $personalDataModel->name;
            $userProfile->cognome = empty($personalDataModel->surname) ? '' : $personalDataModel->surname;

            $date = DateTime::createFromFormat ('d/m/Y', $personalDataModel->birth_date);
            $userProfile->nascita_data = (empty($personalDataModel->birth_date))? null: ($date)? $date->format('Y-m-d'): null;

            $userProfile->provincia_residenza_id = $personalDataModel->residence_province;
            $userProfile->comune_residenza_id = $personalDataModel->residence_city;
//            $userProfile->codice_fiscale = ($personalDataModel->fiscal_code == 'NON_DISPONIBILE')? null: $personalDataModel->fiscal_code;
            $userProfile->sesso =  empty($personalDataModel->gender) ? null: (($personalDataModel->gender == 'm') ? 'Maschio' : 'Femmina');

            // Gestione del token di invito
            // 1) controllo il token se è arrivato
            // --- 2) se è scaduto elimino tutti i token come quello...
            // --- 3) se non è scaduto e quindi mi arriva da un invito faccio l'aggionamento dei campi
            // 2) se trovato l'utente allora faccio l'aggionamento dei campi

            $tokenInvitation = Yii::$app->session->get('token_invitation', null);
            if (!is_null($tokenInvitation)) {
                $userImported = UserImportTaskUser::find()->andWhere(['token' => $tokenInvitation])->one();

//                if (!empty($userImported) && $userImported->expire_date < date('Y-m-d H:i:s')) {
//                    $this->cancelExpiredToken($tokenInvitation);
//                }

                if(!empty($userImported) /* && $userImported->expire_date >= date('Y-m-d H:i:s') */ ) {
                    $userImported->token = null;
                    $userImported->user_id = $userProfile->user_id;
                    $userImported->save(false);

                    $userProfile->is_imported = true;
                    $userProfile->user_import_task_id = $userImported->user_import_task_id;
                }
            } // FINE Gestione del token di invito

            $userProfile->save(false);

            // 2 inserisco i dati sulle preferenze
            // Cittadino
            $interest_classname = 'simple-choice';

            if ($contactsModel->target_cittadini == 'on') {
                $this->saveRegisteredUserInterest('cittadino', $userProfile, $interest_classname, $preferencesModel->topics, $contactsModel);
            }
            if ($contactsModel->target_impresa == 'on') {
                $this->saveRegisteredUserInterest('impresa', $userProfile, $interest_classname, $preferencesModel->topics, $contactsModel);
            }
            if ($contactsModel->target_enti_operatori == 'on') {
                $this->saveRegisteredUserInterest('enteeoperatore', $userProfile, $interest_classname, $preferencesModel->topics, $contactsModel);
            }

            // CREO gli attributi all'utente su ogni target anche se vuoti!

            $uta = new PreferenceUserTargetAttribute();
            $uta->sendEmailValidationComunication = false;
            $uta->email = empty(trim($contactsModel->email_cittadini)) ? null : $contactsModel->email_cittadini;
            $uta->validated_email_flag = false;
            $uta->phone = empty(trim($contactsModel->phone_cittadini)) ? null : $contactsModel->phone_cittadini;
            $uta->validated_phone_flag = false;
            $uta->target_code = TargetTagUtility::getTargetByKey('cittadino')->codice;
            $uta->user_id = $user->id;
            $uta->save(false);

            $uta = new PreferenceUserTargetAttribute();
            $uta->sendEmailValidationComunication = false;
            $uta->email = empty(trim($contactsModel->email_impresa)) ? null : $contactsModel->email_impresa;
            $uta->validated_email_flag = false;
            $uta->phone = empty(trim($contactsModel->phone_impresa)) ? null : $contactsModel->phone_impresa;
            $uta->validated_phone_flag = false;
            $uta->target_code = TargetTagUtility::getTargetByKey('impresa')->codice;
            $uta->user_id = $user->id;
            $uta->save(false);

            $uta = new PreferenceUserTargetAttribute();
            $uta->sendEmailValidationComunication = false;
            $uta->email = empty(trim($contactsModel->email_enti_operatori)) ? null : $contactsModel->email_enti_operatori;
            $uta->validated_email_flag = false;
            $uta->phone = empty(trim($contactsModel->phone_enti_operatori)) ? null : $contactsModel->phone_enti_operatori;
            $uta->validated_phone_flag = false;
            $uta->target_code = TargetTagUtility::getTargetByKey('enteeoperatore')->codice;
            $uta->user_id = $user->id;
            $uta->save(false);

            $this->sendUniqueEmailValidationToken($user->id, $transaction);

            // aggiornamento sistema di provenienza
            /** @var \preference\userprofile\models\UserProfile $upForOriginSystem */
            $upForOriginSystem = \preference\userprofile\models\UserProfile::find()->andWhere(['id' => $userProfile->id])->one();
            if(!empty($upForOriginSystem)) {
                $userProfile->preference_origin_system_id = 1;
                $userProfile->save(false);
            }

            // VarDumper::dump($uta->toArray(), 2, true);
            // $transaction->rollBack(); die;

            // nessuna mail di validazione deve essere manda per il DL semplificazioni
//            $valToken = new PreferenceUsernameValidationToken();
//            $valToken->token = TargetAttributeUtility::generateNewEmailToken();
//            $valToken->user_id = $user->id;
//            $valToken->save(false);
//            EmailUtility::sendUserMailValidationToken($valToken->token, $user->username, 'Lombardia Informa, conferma registrazione');

            // VarDumper::dump($user->toArray(), 2, true);
            // VarDumper::dump($userProfile->toArray(), 2, true);

            $this->session->remove($this->stepPreferenceSessionId);
            $this->session->remove($this->stepPersonalDataSessionId);
            $this->session->remove($this->stepContactsSessionId);
            $this->session->remove($this->stepContactsPrivacyId);

            // Per SPID, dovrebbe essere già creata (questa riga in tabella) da un evento messo bootstrap ma non vo visto la riga in tabella
            // formarsi... lo forzo...
            if (!empty(Yii::$app->session->get('IDM'))) {
                SocialAuthUtility::createIdmUser(Yii::$app->session->get('IDM'), $user->id);
            }

            $transaction->commit();
            // 
        } catch (CreationRegisterdUserException $ue) {
            $transaction->rollBack();
            throw $ue;
        } catch (LoadWizardDataException $ue) {
            $transaction->rollBack();
            throw $ue;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $this->view->params['customClassMainContent'] = 'container';

        $loginTimeout = \Yii::$app->params['loginTimeout'] ?: 3600;
        \Yii::$app->user->login($user, $loginTimeout);
        $this->session->remove('IDM');
        return $this->goHome();
//        return $this->render("registrate_user_success", [
//            'email' =>  $user->email
//        ]);
    }

    /**
     *  UNUSED!!!!!!
     *
     *  non esiste in preference fare un controllo su token scaduto...
     *
     * @param $invitationToken
     */
    private function cancelExpiredToken($invitationToken)
    {
        $allToken = UserImportTaskUser::find()->andWhere(['token' => $invitationToken])->all();
        foreach ($allToken as $token) {
            $token->token = null;
            $token->save(false);
        }
    }

    /**
     * 
     */
    private function sendUniqueEmailValidationToken($userId, $transaction)
    {
        $listOfAttributes = PreferenceUserTargetAttribute::findAll(['user_id' => $userId]);
        $comunicationArray = [];
        /** @var PreferenceUserTargetAttribute $attribute */
        foreach ($listOfAttributes as $attribute) {
            $comunicationArray[$attribute->email][$attribute->id]['id'] = $attribute->id;
            $comunicationArray[$attribute->email][$attribute->id]['targhet-name'] = TargetTagUtility::getTargetByCode($attribute->target_code)->nome;
        }

        // Per ogni mail invio una sola comunicazione - setto un token uguale per validarli in blocco
        foreach ($comunicationArray as $email => $recipientsArray) {
            $newToken = TargetAttributeUtility::generateNewEmailToken();

            $names = [];
            foreach ($recipientsArray as $val) {
                $attr = PreferenceUserTargetAttribute::findOne($val['id']);
                $attr->email_validation_token = $newToken;
                $attr->sendEmailValidationComunication = false;
                $attr->save(false); 

                $names[] = $val['targhet-name'];
            }

           if (!empty($email)) {
                EmailUtility::sendTargetMailValidationToken($newToken
                        , $email
                        , 'Lombardia Informa: validazione email di contatto'
                        , $names);
           }
        }
    }

    /**
     * Undocumented function
     *
     * @param string $key
     * @param UserProfile $userProfile
     * @param string $interest_classname
     * @param array $topics
     * @param StepContacts $contactsModel
     * @return void
     */
    private function saveRegisteredUserInterest($key, $userProfile, $interest_classname, $topics)
    {
        $tag = TargetTagUtility::getTargetByKey($key);
        if (!empty($tag)) {
            UserInterestTagUtility::saveRegisteredUserInterestTag($userProfile, $interest_classname, $tag);
            foreach ($topics as $strId) {
                $tag = TopicTagUtility::getTopicByTargetCode($key, $strId);
                if (!empty($tag)) {
                    if (empty($tag->codice)) {
                        throw new CreationRegisterdUserException();
                    }
                    UserInterestTagUtility::saveRegisteredUserInterestTag($userProfile, $interest_classname, $tag);
                    // non attivo tutti i canali, essendo una registrazione WEB attivo solo i canali WEB e non quello APP
//                    UserInterestTagUtility::addAllPreferenceTopicChannelByUserAndCode($userProfile->user->id, $tag);
                    UserInterestTagUtility::addSinglePreferenceTopicChannel($userProfile->user->id, $tag, PreferenceChannel::NEWSLETTER_ID);
                    UserInterestTagUtility::addSinglePreferenceTopicChannel($userProfile->user->id, $tag, PreferenceChannel::SMS_ID);
                } else {
                    throw new CreationRegisterdUserException();
                }
            }
        } else {
            throw new CreationRegisterdUserException($key);
        }
    }

    /**
     * 
     */
    public function actionRemoveAccount($token = '3ea3c34b41fe8189f060c62f121f2010')
    {

        // TODO Sistema token per l'eliminazione dell'account

        return $this->render("remove_account", [
        ]);
    }

    public function actionTestDataAjax()
    {  
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $relatedValue = \Yii::$app->request->post('related_value');
        $toret = [];

        if (!empty($relatedValue)){
            $arrayComuni = ArrayHelper::map(IstatComuni::find()->andWhere(['istat_province_id' => $relatedValue])->all(),'id','nome');
            foreach ( $arrayComuni as $key => $value){ 
                $toret[] = ['value' => $key, 'label' => $value];
            }
        }

        return $toret;
    }

    public function actionSendOtp()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = \Yii::$app->request->post();
        $toret['status'] = 'ko';
        if (!empty($request)) {
            try {
                $email = isset($request['email'])? $request['email']: null;
                if (!is_null($email)) {
                    $user = CurrentUser::isPlatformGuest() ? null : Yii::$app->user;
                    /** @var UserOtpCode $otp */
                    $otp = UserProfileUtility::generateOPT($user);
                    EmailUtility::sendUserMailValidationEmailOtp($otp->otp_code, $email, 'Lombardia Informa, validazione email');
                    $toret['status'] = 'ok';
                }
            } catch (NotificationEmailException $e) {
                $toret['status'] = 'ko - invio mail';
            }
        }

        return $toret;
    }

}
