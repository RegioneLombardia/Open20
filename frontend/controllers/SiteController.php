<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

namespace app\controllers;

use amos\userauth\models\UserLoginForm;
use backend\modules\landings\models\PreferenceLanding;
use backend\modules\landings\models\PreferenceLandingRating;
use luya\exceptions\WhitelistedException;
use preference\userprofile\utility\UserInterestTagUtility;
use preference\userprofile\utility\TargetTagUtility;
use preference\userprofile\models\PreferenceUserTargetAttribute;
use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use frontend\utility\UserProfileUtility;
use frontend\models\FirstAccessForm;
use open20\amos\core\models\AccessTokens;
use open20\amos\core\user\User;
use open20\amos\socialauth\controllers\ShibbolethController;
use open20\amos\socialauth\models\SocialAuthUsers;
use open20\amos\socialauth\utility\SocialAuthUtility;
use Exception;
use frontend\models\ForgotPasswordForm;
use Google_Service_Slides_LayoutPlaceholderIdMapping;
use luya\admin\models\Config;
use luya\cms\helpers\Url;
use luya\cms\menu\QueryOperatorFieldInterface;
use preference\userprofile\models\StepPrivacy;
use Yii;
use yii\base\BaseObject;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\Response;
use Hybridauth\User\Profile;
use function Complex\theta;

/**
 * Class SiteController
 * @package frontend\controllers
 */
class SiteController extends Controller
{
    const USERAUTH_CONFIG_REDIRECT_NAV_ID     = 'userauth_redirect_nav_id';
    const USERAUTH_CONFIG_AFTER_LOGIN_NAV_ID  = 'userauth_afterlogin_nav_id';
    const NOPERMISSION_CONFIG_REDIRECT_NAV_ID = 'nopermission_redirect_nav_id';

    /**
     * @var AmosAdmin $adminModule
     */
    protected $adminModule;

    public function init()
    {
        /** @var AmosAdmin $adminModule */
        $this->adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'login'],
                'rules' => [
                    [
                        'actions' => ['login', 'forgot-password', 'insert-auth-data', 'register', 'login-social', 'social-sign-in'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [
                            'logout', 'rating'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionSocialSignIn($provider)
    {
//        $user = User::findOne(2);
//        Yii::$app->user->login($user);
//        VarDumper::dump(Yii::$app->user->id, 3,true);

        if (\Yii::$app->user->id == 2){
            Yii::$app->session->set('redirectToSignIn', '/site/login-social');
            Yii::$app->session->set('redirect_url_spid', '/admin/security/register');

            switch ($provider) {
                case 'spid':
                    return $this->redirect(['/socialauth/shibboleth/endpoint']);
                    break;
                
                default:
                    return $this->redirect(['/socialauth/social-auth/sign-in', 'provider' => $provider]);
                    break;
            }
        } 

        throw new ForbiddenHttpException();
    }

    public function actionLoginSocial()
    {
        // solo la app dovrebbe accedere a questa rotta dopoche l'utente ha fatto login
        if (!empty(Yii::$app->user->id) && (Yii::$app->user->id != 2)) {
            $userMobile = \open20\amos\mobile\bridge\modules\v1\models\User::findOne(['id' => Yii::$app->user->id]);
            $token = $userMobile->refreshAccessToken('webcms', 'cms');
            return $this->redirect(['/preference-userprofile/preference/app-end-point', 'token' => $token->access_token]);
        }
        return $this->goHome();
    }

    /**
     * Logs in a user.
     * @return string|Response
     */
    public function actionLogin($redir = null)
    {
        if (!Yii::$app->user->isGuest) {
            return Yii::$app->response->redirect(Url::home(true));
        }

        $model = new UserLoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate() && Yii::$app->user->login($model->user)) {
            return $this->goBack();
        }

        $navItem = Yii::$app->menu->find()->where([QueryOperatorFieldInterface::FIELD_NAVID => Config::get(self::USERAUTH_CONFIG_REDIRECT_NAV_ID)])->with([
                'hidden'])->one();


        // redirect to the given nav item
        return Yii::$app->response->redirect($navItem->absoluteLink);
    }

    /**
     * Logs out the current user.
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(\Yii::$app->params['platform']['frontendUrl'].'/Shibboleth.sso/Logout?return='.Url::to(Yii::$app->getHomeUrl()) );
  
        // return $this->goHome();
    }

    /**
     * Logs out the current user.
     * @return Response
     */
    public function actionForgotPassword()
    {

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        /** @var ForgotPasswordForm $userProfileModel */
        $model = new ForgotPasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->email != NULL) {
                $dati_utente = $model->verifyEmail($model->email);
                if ($dati_utente) {
                    if ($dati_utente->userProfile->isDeactivated()) {
                        return $this->redirect('/admin/security/reactivate-profile?userdisabled');
                    }
                    $urlCurrent      = null;
                    $urlCurrentParam = Yii::$app->getRequest()->get('url_current');
                    if (!is_null(Yii::$app->getRequest()->get('url_current'))) {
                        $urlCurrent = $urlCurrentParam;
                    }
                    $this->actionSpedisciCredenziali($dati_utente->userProfile->id,
                        true, true, $urlCurrent);
                }
                return $this->render('security-message',
                        [
                            'title_message' => AmosAdmin::t('amosadmin',
                                '#msg_forgot_pwd_title'),
                            'result_message' => [
                                AmosAdmin::t('amosadmin',
                                    '#msg_forgot_pwd_result_1').'<br>'.Html::tag('strong',
                                    $model->email),
                                AmosAdmin::t('amosadmin',
                                    '#msg_forgot_pwd_result_2')
                            ],
                            'go_to_login_url' => !is_null(Yii::$app->getRequest()->get('return_url'))
                                ? Yii::$app->getRequest()->get('return_url') : Url::current(),
                ]);
            }
        }
        return $this->render('forgot_password',
                [
                    'model' => $model,
        ]);
    }

    /**
     *
     * @param type $id
     * @param type $isForgotPasswordView
     * @param type $isForgotPasswordRequest
     * @param type $urlCurrent
     * @return type
     */
    public function actionSpedisciCredenziali($id,
                                              $isForgotPasswordView = false,
                                              $isForgotPasswordRequest = false,
                                              $urlCurrent = null)
    {
        /** @var UserProfile $userProfileModel */
        $userProfileModel = $this->adminModule->createModel('UserProfile');
        $model            = $userProfileModel::findOne($id);
        if ($model && $model->user && $model->user->email) {
            $model->user->generatePasswordResetToken();
            $model->user->save(false);
            if (!$isForgotPasswordRequest) {
                $sent = UserProfileUtility::sendCredentialsMail($model);
            } else {
                $sent = UserProfileUtility::sendPasswordResetMail($model, null,
                        $urlCurrent);
            }
            if ($sent) {
                if (!$isForgotPasswordView) {
                    Yii::$app->session->addFlash('success',
                        AmosAdmin::t('amosadmin',
                            'Credenziali spedite correttamente alla email {email}',
                            ['email' => $model->user->email]));
                }
            } else {
                if (!$isForgotPasswordView) {
                    Yii::$app->session->addFlash('danger',
                        AmosAdmin::t('amosadmin',
                            'Si è verificato un errore durante la spedizione delle credenziali'));
                }
            }
        } else {
            if (!$isForgotPasswordView) {
                //Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', 'L\'utente non esiste o è sprovvisto di email, impossibile spedire le credenziali'));
                Yii::$app->session->addFlash('danger',
                    AmosAdmin::t('amosadmin',
                        'Si è verificato un errore durante la spedizione delle credenziali'));
            }
        }
        if (!$isForgotPasswordView) {
            return $this->redirect(Url::previous());
        }
    }

    /**
     *
     * @return type
     */
    public function actionInsertAuthData()
    {
        $password_reset_token            = null;
        $user                            = null;
        $username                        = null;
        $redirectUrl                     = \Yii::$app->getUser()->loginUrl;
        $model = new FirstAccessForm();
        //$precompileUsernameOnFirstAccess = $this->module->precompileUsernameOnFirstAccess;
        $isFirstAccess                   = false;
        if (NULL !== (Yii::$app->getRequest()->getQueryParam('token'))) {
            $password_reset_token = Yii::$app->getRequest()->getQueryParam('token');
            $user                 = User::findByPasswordResetToken($password_reset_token);
            if ($user) {
                $username      = $user->username;
                $isFirstAccess = (empty($user->password_hash) && !$user->userProfile->privacy);
                $model->username = $username;
            }
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $user->setPassword($model->password);

            if ($user->validate() && $user->save()) {
                Yii::$app->getSession()->addFlash('success',
                    Yii::t('amosadmin',
                        'Perfetto! Hai scelto correttamente la tua password.'));
                $user->removePasswordResetToken();
                $user->save();
                if ($isFirstAccess) {
                    $profile          = $user->userProfile;
                    $profile->privacy = 1;
                    $profile->save(false);
                }
                return Yii::$app->getResponse()->redirect("/site/login");
            } else {
                return $this->render('security-message',
                        [
                            'title_message' => AmosAdmin::t('amosadmin',
                                'Spiacenti'),
                            'result_message' => AmosAdmin::t('amosadmin',
                                " Errore! Il sito non ha risposto, probabilmente erano in corso operazioni di manutenzione. Riprova più tardi.")
                ]);
            }
        } else {
//            if ($precompileUsernameOnFirstAccess) {
//                $model->username = $user->email;
//            }
            if ($isFirstAccess) {
                $model->setScenario(FirstAccessForm::SCENARIO_CHECK_PRIVACY);
            }
            $model->token    = $password_reset_token;
            $model->username = $username;
            return $this->render('reset_password',
                    [
                        'model' => $model,
                        'isFirstAccess' => $isFirstAccess && is_null($user->userProfile->privacy)
            ]);
        }
    }

    /**
     *  Unico metodo di registrazione da WEB il Wizard di registrazione
     *  Quindi, qui controllo solo che siano arrivati i dati in sessione da SPID
     *
     * @return Response
     * @throws \HttpInvalidParamException
     */
    public function actionRegister()
    {
        // ***************** LOGIN **************
        // se arriviamo qui con un utente che non è app guest allora lo mando al login della APP
        // ora l'url di redirezione in caso di registrazione e di login sono uguali!
        // logica di shibolethController...
        if (!empty(Yii::$app->user->id) && (Yii::$app->user->id != 2)) {
            $userMobile = \open20\amos\mobile\bridge\modules\v1\models\User::findOne(['id' => Yii::$app->user->id]);
            $token = $userMobile->refreshAccessToken('webcms', 'cms');
            return $this->redirect(['/preference-userprofile/preference/app-end-point', 'token' => $token->access_token]);
        }
        // superato questo controllo andiamo alla registrazione, sempre se e solo se IDM è in sessione

        // ***************** REGISTGER **************
        // vai inregistrazione solo se sono stati impostati i dati in sessione da SPID
        if (empty(Yii::$app->session->get('IDM'))) {
            throw new HttpException('Impossibile recuperare i dati inviati da SPID');
        }

        if (\Yii::$app->user->id == 2){
            // vai al primo step di profilazione
            return $this->redirect(['/preferenceuser/registration-app/personal-data']);
        } else {
            // vai al primo step di profilazione
            return $this->redirect(['/preferenceuser/registration/personal-data']);
        }

    }

    /**
     * @return bool|\yii\web\Response

    public function actionRegister()
    {
        $model = new StepPrivacy();
        if (Yii::$app->request->post() && ($model->load(Yii::$app->request->post()) || (!empty(Yii::$app->request->post('submit-action')))) ) {
            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {

                    // VarDumper::dump(Yii::$app->session->get('social-pending'), $depth = 10, $highlight = true);
                    // VarDumper::dump(Yii::$app->session->get('social-profile'), $depth = 10, $highlight = true); die;
                    $email = null;
                    $name = null;
                    $surname = null;
                    $fiscalCode = null;

                    switch (Yii::$app->session->get('social-pending')) {
                        case 'facebook':
                            $email = Yii::$app->session->get('social-profile')->email;
                            $name = Yii::$app->session->get('social-profile')->firstName;
                            $surname = Yii::$app->session->get('social-profile')->lastName;
                        break;
                        case 'google':
                            $email = Yii::$app->session->get('social-profile')->email;
                            $name = Yii::$app->session->get('social-profile')->firstName;
                            $surname = Yii::$app->session->get('social-profile')->lastName;
                        break;
                        case 'twitter':
                            $email = Yii::$app->session->get('social-profile')->email;
                            $name = Yii::$app->session->get('social-profile')->firstName;
                            $surname = Yii::$app->session->get('social-profile')->lastName;
                        break;
                        case 'linkedin':
                            $email = Yii::$app->session->get('social-profile')->email;
                            $name = Yii::$app->session->get('social-profile')->firstName;
                            $surname = Yii::$app->session->get('social-profile')->lastName;
                        break;
                    }

                    // VarDumper::dump(Yii::$app->session->get('social-pending'), $depth = 10, $highlight = true); die;

                    if ( (!empty(Yii::$app->session->get('IDM'))) && (is_null($email)) ) {
                        // allora stanno cercando di registrare un utente da SPID
                        $email = Yii::$app->session->get('IDM')['emailAddress'];
                        $name = Yii::$app->session->get('IDM')['nome'];
                        $surname = Yii::$app->session->get('IDM')['cognome'];
                        $fiscalCode = Yii::$app->session->get('IDM')['codiceFiscale'];
                    }

                    if (empty($email)) {
                        Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', 'Unable to register, missing mail permission'));
                        return $this->goHome();
                    }

                    // controlliamo che sia già registrato l'utente...
                    // se sì ed è attivo allora lo loggo...
                    $user = User::find()->where(['email' => $email])->andWhere(['status' => 10])->one();
                    if (!empty($user)) {
                        // if (Yii::$app->user->login($user)) {
                        //     return Yii::$app->getResponse()->redirect(Url::to('/preferenceuser/preference/settings'));
                        // } else {
                            Yii::$app->session->addFlash('danger', 'Utente già registrato');
                            return $this->goHome();
                        // }
                    }

                    // non esiste lo registro

                    $module = \Yii::$app->getModule('amosadmin');
                    $userCreatedArray = $module->createNewAccount(
                        empty($name) ? '-' : $name,
                        empty($surname) ? '-' : $surname,
                        $email,
                        0,
                        false,
                        null,
                        null,
                        'amosadmin'
                    );


                    if (isset($userCreatedArray['error']) && ($userCreatedArray['error'] >= 1)) {
                        $str = VarDumper::dumpAsString($userCreatedArray['messages'], $depth = 10, $highlight = false);
                        Yii::$app->session->addFlash('danger', 'Impossibile creare l\'anagrafica: ' . $str);
                        return $this->goHome();
                    }

                    // utente creato vado a settare i dati inseriti in registrazione.
                    // 1 inserico i dati sul profilo
                    $user = $userCreatedArray['user'];
                    $user->password_reset_token = null;
                    $user->status = 10;
                    $user->save(false);

                    $userProfile =  $user->userProfile;
                    $userProfile->nome = empty($name) ? '' : $name;
                    $userProfile->cognome = empty($surname) ? '' : $surname;
                    $userProfile->privacy = ($model->privacy) ? 1 : 0;
                    $userProfile->codice_fiscale = empty($fiscalCode) ? null : $fiscalCode;
                    $userProfile->save(false);

                    // Aggncio il social appena registrato! lo aggancio all'utenza appena creata
                    // ha senso farlo perchè già abbiamo appena inserito un utente mai registrato a sistema
                    switch (Yii::$app->session->get('social-pending')) {
                        case 'facebook':
                        case 'google':
                        case 'twitter':
                        case 'linkedin':
                            $this->registerSocialAuthUser(Yii::$app->session->get('social-pending'), Yii::$app->session->get('social-profile'), $user->id);
                            break;
                    }

                    // Per SPID, dovrebbe essere già creata (questa riga in tabella) da un evento messo bootstrap ma non vo visto la riga in tabella 
                    // formarsi... lo forzo...
                    if (!empty(Yii::$app->session->get('IDM'))) {
                        SocialAuthUtility::createIdmUser(Yii::$app->session->get('IDM'), $user->id);
                    }

                    $interest_classname = 'simple-choice';
                    $tag = TargetTagUtility::getTargetByKey('cittadino');
                    if (!empty($tag)) {
                        UserInterestTagUtility::saveRegisteredUserInterestTag($userProfile, $interest_classname, $tag);
                    } else {
                        throw new Exception('Impossibile creare target cittadino...');
                    }

                    $uta = new PreferenceUserTargetAttribute();
                    if (Yii::$app->user->id != 2) {
                        $uta->email = $email;
                    }
                    $uta->validated_email_flag = false;
                    $uta->target_code = TargetTagUtility::getTargetByKey('cittadino')->codice;
                    $uta->user_id = $user->id;
                    $uta->save(false);

                    $transaction->commit();
                    if (!empty(Yii::$app->session->get('IDM'))) {
                        Yii::$app->session->remove('IDM');
                    }

                    if (!empty(Yii::$app->session->get('social-pending'))) {
                        Yii::$app->session->remove('social-pending');
                        Yii::$app->session->remove('social-profile');
                    }

                } catch (\Exception $e) {
                    Yii::$app->session->addFlash('danger', 'Impossibile creare l\'anagrafica: ' . $e->getMessage());
                    return $this->goHome();
                }

                if (Yii::$app->user->id == 2) {
                    $userMobile = \open20\amos\mobile\bridge\modules\v1\models\User::findOne(['id' => $user->id]);
                    $token = $userMobile->refreshAccessToken('webcms', 'cms');
                    return $this->redirect(['/preference-userprofile/preference/app-end-point?token=' . $token->access_token]);
                }

                // utente creato lo loggo!
                if (Yii::$app->user->login($user)) {
                    // return $this->redirect('/preferenceuser/preference/settings');
                    return $this->render("@vendor/preference/userprofile/src/views/registration/registrate_social_user_success", [
                        'email' =>  $user->email
                    ]);
                } else {
                    Yii::$app->session->addFlash('danger', 'Impossibile loggare l\'utente');
                    return $this->render("social_privacy", [
                        'model' => $model,
                    ]);
                }
            } else {
                return $this->render("social_privacy", [
                    'model' => $model,
                ]);
            }
        }

        // propongo la compilazione della privacy
        return $this->render("social_privacy", [
            'model' => $model,
        ]);
    }
    */

    /**
     * 
     */
    private function registerSocialAuthUser($provider, $socialProfile, $userId ) {
        /**
         * @var $userProfileArray array User profile from provider
         */
        $userProfileArray = (array)$socialProfile;
        $userProfileArray['provider'] = $provider;
        $userProfileArray['user_id'] = $userId;

        /**
         * @var $socialUser SocialAuthUsers
         */
        $socialUser = new SocialAuthUsers();

        /**
         * If all data can be loaded to new record
         */
        if ( empty( SocialAuthUsers::findOne(['user_id' => $userId]) )) {
            if ($socialUser->load(['SocialAuthUsers' => $userProfileArray])) {
                /**
                 * Is valid social user
                 */
                if ($socialUser->validate()) {
                    $socialUser->save();
                    // Yii::$app->session->addFlash('success', AmosAdmin::t('amosadmin', 'Profilo social agganciato '));
                }
            }
        }
    }

    /**
     * @return false|string
     */
    public function actionRating() {
        $landing = PreferenceLanding::findOne(Yii::$app->request->post('landing_id'));
        $ratingValue = Yii::$app->request->post('landing-rating-value');
        $thankyouMessage = null;

        if (!empty($landing)) {
            /** @var PreferenceLandingRating $review */
            $review = new PreferenceLandingRating();
            $review->preference_landing_id = $landing->id;
            $review->rating = $ratingValue;
            $review->save(false);

            // vado ad aggiornare il cookie
            $cookies = \Yii::$app->request->cookies;
            $arrayCookieContent = [];
            if ($cookies->has(PreferenceLandingRating::FRONTEND_RATING_COOKIE_NAME)) {
                $arrayCookieContent = unserialize($cookies->getValue(PreferenceLandingRating::FRONTEND_RATING_COOKIE_NAME));
            }

            // Aggiungo e se esiste sovrascrivo
            $arrayCookieContent[$landing->id] = [
                'id' => $landing->id,
                'idRating' => $review->id,
            ];

            $cookiesOnResponse = \Yii::$app->response->cookies;
            $cookiesOnResponse->add(new \yii\web\Cookie([
                'name' => PreferenceLandingRating::FRONTEND_RATING_COOKIE_NAME,
                'value' => serialize($arrayCookieContent),
                'expire' => time() + 86400 * 365,
            ]));

            $thankyouMessage = 'Ti ringraziamo per avere espresso la tua opinione';
        }

        return $this->render('@frontend/modules/backendobjects/frontend/views/default/_ratingContent', [
            'landing' => $landing,
            'ratingReadOnly' => true,
            'ratingValue' => $ratingValue,
            'thankyouMessage' => $thankyouMessage,
        ]);

    }

}