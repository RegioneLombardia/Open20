<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\socialauth
 * @category   CategoryName
 */

namespace open20\amos\socialauth\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\attachments\components\FileImport;
use open20\amos\core\controllers\BackendController;
use open20\amos\core\forms\editors\AmosDatePicker;
use open20\amos\core\user\User;
use open20\amos\mobile\bridge\modules\v1\models\AccessTokens;
use open20\amos\socialauth\models\SocialAuthUsers;
use open20\amos\socialauth\Module;
use Hybridauth\Adapter\AbstractAdapter;
use Hybridauth\User\Profile;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class FileController
 * @package open20\amos\socialauth\controllers
 */
class SocialAuthController extends BackendController
{
    /**
     * @var string $layout
     */
    public $layout = 'login';

    /**
     * @var $userProfile Profile
     */
    protected $userProfile;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'link-user',
                            'link-social-account',
                            'unlink-user',
                            'unlink-social-account',
                        ],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'endpoint',
                            'sign-in',
                            'sign-up',
                            'mobile',
                            'land',
                            'get-user-social'
                        ],
                        //'roles' => ['*']
                    ]
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setUpLayout();
        // custom initialization code goes here
    }

    /**
     *
     * @param Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Endpoint bridge for auth actions
     *
     * @param $action
     * @param $provider
     */
    public function actionEndpoint($provider = null, $action = null, $backTo = null, $redirectTo = null)
    {
//        pr($redirectTo,'enpoint redir');

        /**
         * @var $provider string
         */
        $provider = $provider ?: \Yii::$app->session->get('socialAuthProvider');

        /**
         * @var $action string
         */
        $action = $action ?: \Yii::$app->session->get('socialAuthAction');

        /**
         * @var $backTo string
         */
        $backTo = $backTo ?: \Yii::$app->session->get('socialAuthBackTo');

        //Get out, cant proceed without those infos
//        pr($redirectTo,'redir');die;
        if($redirectTo) {
            \Yii::$app->session->set('redirectToSignIn', $redirectTo);
        }

        if(!$provider || (!$action && !$backTo)) {
            return $this->goHome();
        } else {
            \Yii::$app->session->set('socialAuthProvider', $provider);
            \Yii::$app->session->set('socialAuthAction', $action);
            \Yii::$app->session->set('socialAuthBackTo', $backTo);
        }

        /**
         * @var $adapter AbstractAdapter
         */
        $adapter = $this->authProcedure($provider);

        /**
         * If the adapter is not set go back to home
         */
        if (!$adapter) {
//            return $this->goHome();
            return $this->goBack();
        }

        /**
         * @var $userProfile Profile
         */
        $this->userProfile = $adapter->getUserProfile();

        /**
         * Kick off social user
         */
        $adapter->disconnect();

        //Store profile in session for custom usage
        \Yii::$app->session->set('socialAuthUserProfile', $this->userProfile);

        //Custo  back to url
        if($backTo) {
            \Yii::$app->session->remove('socialAuthBackTo');
            return $this->redirect($backTo);
        } else {
            //Standard flow for login/register/etc
            return $this->runAction(
                $action,
                [
                    'status' => 'prepare',
                    'provider' => $provider,
                ]
            );
        }
    }

    /**
     * @param $provider
     * @param null $urlBase
     * @return bool|AbstractAdapter
     */
    public function authProcedure($provider, $callbackUrl = null)
    {
        //For history Rollback
        Url::remember();

        //Some Back-Compatibility things
        if(Yii::$app->controller->module instanceof Module) {
            /**
             * @var $callbackUrl string The full call back url to use in the provider
             */
            $callbackUrl = $callbackUrl ?: Url::to(
                [
                    '/' . $this->module->id . '/social-auth/endpoint'
                ],
                'https'
            );
        } else {
            //External Usage callback url
            $callbackUrl = $callbackUrl ?: Url::current(Yii::$app->request->get(), 'https');
        }

        /**
         * @var $config array with all configurations
         */
        $config = [
            'callback' => $callbackUrl,
            'providers' => $this->module->getProviders()
        ];

        try {
            $hybridauth = new \Hybridauth\Hybridauth($config);
        } catch (\Exception $e) {
            Yii::$app->session->addFlash('danger', Module::t('amossocialauth', 'Login Failed'));

            return false;
        }

        /**
         * @var $adapter AbstractAdapter
         */
        $adapter = $hybridauth->authenticate($provider);

        return $adapter;
    }

    /**
     * Login with social account
     * @param $provider
     * @return bool|\yii\web\Response
     */
    public function actionSignIn($provider, $redirects = true, $redirectTo = null, $status = null)
    {
        $urlToRedirect = Yii::$app->getUser()->getReturnUrl('');
        $community_id = \Yii::$app->request->get('community');

        if (strpos($urlToRedirect, 'community/join') > 0) {
            $urlToCommunity = \Yii::$app->getUrlManager()->createUrl($urlToRedirect);
        } else {
            $urlToCommunity = \Yii::$app->getUrlManager()->createUrl(
                ['/community/join', 'id' => $community_id, 'subscribe' => 1]
            );
        }

        /**
         * If the user is already logged in go to home
         */
        if (!\open20\amos\core\utilities\CurrentUser::isPlatformGuest() && $redirects) {
            Yii::$app->session->addFlash('danger', Module::t('amossocialauth', 'Already Logged In'));

            return $this->goHome(['id' => 'logged']);
        }

        /**
         * If login is not enabled
         */
        if (!$this->module->enableLogin) {
            Yii::$app->session->addFlash('danger', Module::t('amossocialauth', 'Social Login Disabled'));

            return $this->goHome(['id' => 'disabled']);
        }
        //Prepare procedure
        if ($status == null && $redirects) {
            return $this->redirect(
                [
                    'endpoint',
                    'action' => $this->action->id,
                    'provider' => $provider,
                    'redirectTo' => $redirectTo
                ]
            );
        }
//        pr($redirectTo, 'signin');


        //Return direct result
        if (!$redirects) {
            return $this->userProfile;
        }

        /**
         * @var $socialUser SocialAuthUsers
         */
        $socialUser = SocialAuthUsers::findOne(['identifier' => $this->userProfile->identifier, 'provider' => $provider]);

        //Override default timeout
        $loginTimeout = Yii::$app->params['loginTimeout'] ?: 3600;

        /**
         * If the social user exists
         */
        if ($socialUser) {
            /**
             * If the user exists
             */
            if ($socialUser->user && $socialUser->user->id) {
                //Check user deactivated
                if ($socialUser->user->status == User::STATUS_DELETED) {
                    Yii::$app->session->addFlash(
                        'danger',
                        Module::t(
                            'amosadmin',
                            'User deactivated. To log in again, request reactivation of the profile.'
                        )
                    );

                    return $this->goHome(['id' => 'deleted']);
                }


                $signIn = Yii::$app->user->login($socialUser->user, $loginTimeout);

                // if google contact service enabled reload in session some contact data by google account
                AmosAdmin::fetchGoogleContacts();
                if(\Yii::$app->session->get('redirectToSignIn')){
                    $redirectTo = \Yii::$app->session->get('redirectToSignIn');
                }


                if ($redirectTo) {
                    \Yii::$app->session->remove('redirectToSignIn');
                    return $this->redirect($redirectTo);
                } else {
                    if ($community_id) {
                        return $this->redirect($urlToCommunity);
                    }
                }

                return $this->goHome(['id' => 'found']);
            } else {
                Yii::$app->session->addFlash('danger', Module::t('amossocialauth', 'Unable to Login with this User'));
            }

            return $this->goHome(['id' => 'none']);
        } else {
            //Find for existing user with social email
            $q = User::find();
            $q->where(['email' => $this->userProfile->email]);
            $q->orWhere(['username' => $this->userProfile->email]);

            $userMatchMail = $q->one();

            if ($userMatchMail && $userMatchMail->id) {
                if (!$this->module->userOverload) {
                    Yii::$app->session->set('social-match', $provider);
                    Yii::$app->session->set('social-profile', $this->userProfile);

                    return $this->redirect('/' . AmosAdmin::getInstance()->id . '/security/login');
                } else {
                    //Link immediatelly to matched mail user
                    $this->linkSocialToUser($provider, $this->userProfile, $userMatchMail->id);

                    //Logijn to the platform
                    $signIn = Yii::$app->user->login($userMatchMail, $loginTimeout);
                    // if google contact service enabled reload in session some contact data by google account
                    AmosAdmin::fetchGoogleContacts();

                    //Back to home
                    if ($community_id) {
                        return $this->redirect($urlToCommunity);
                    }

                    return $this->goHome(['id' => 'match_mail']);
                }
            } else {
                Yii::$app->session->set('social-pending', $provider);
                Yii::$app->session->set('social-profile', $this->userProfile);

                return $this->redirect('/' . AmosAdmin::getInstance()->id . '/security/register');
            }
            //Yii::$app->session->addFlash('danger', Module::t('amossocialauth', 'User Not Found, Please try with Other User'));
        }
    }

    /**
     * @param $provider
     * @return \yii\web\Response
     */
    public function actionMobile($provider, $status = null)
    {
        //Prepare procedure
        if ($status == null) {
            return $this->redirect(
                [
                    'endpoint',
                    'action' => $this->action->id,
                    'provider' => $provider
                ]
            );
        }

        if (!($this->userProfile instanceof Profile)) {
            return $this->redirect(
                [
                    '/socialauth/social-auth/land',
                    'error' => true,
                    'errorMessage' => Yii::t('socialauth', 'Accesso Social Non Disponibile')
                ]
            );
        }

        /**
         * @var $socialUser SocialAuthUsers
         */
        $socialUser = SocialAuthUsers::findOne(['identifier' => $this->userProfile->identifier, 'provider' => $provider]);

        /**
         * @var $platformUser \open20\amos\mobile\bridge\modules\v1\models\User
         */
        $platformUser = \open20\amos\mobile\bridge\modules\v1\models\User::findOne(['id' => $socialUser->user_id]);

        if (!$platformUser || !$platformUser->id) {
            return $this->redirect(
                [
                    '/socialauth/social-auth/land',
                    'redirectToRegister' => true,
                    'error' => true,
                    'errorMessage' => Yii::t('socialauth', 'Non Sei Registrato Nella Piattaforma'),
                ]
            );
        }

        /**
         * @var $token AccessTokens
         */
        $token = $platformUser->refreshAccessToken('mobile', 'mobile');

        if ($token && !$token->hasErrors()) {
            return $this->redirect(['/socialauth/social-auth/land', 'token' => $token->access_token]);
        } else {
            return $this->redirect(
                [
                    '/socialauth/social-auth/land',
                    'error' => true,
                    'errorMessage' => Yii::t('socialauth', 'Errore Di Accesso, Riprovare Tra Qualche Minuto')
                ]
            );
        }
    }

    /**
     * @return bool
     */
    public function actionLand()
    {
        return true;
    }

    /**
     * @param $provider
     * @return bool|\yii\web\Response
     */
    public function actionSignUp($provider, $status = null)
    {
        $community_id = \Yii::$app->request->get('community');
        $urlToCommunity = \Yii::$app->getUrlManager()->createUrl(
            ['/community/join', 'id' => $community_id, 'subscribe' => 1]
        );

        if (!\open20\amos\core\utilities\CurrentUser::isPlatformGuest()) {
            Yii::$app->session->addFlash('danger', Module::t('amossocialauth', 'Already Logged In'));

            return $this->goHome();
        }

        /**
         * If signup is not enabled
         */
        if (!$this->module->enableRegister) {
            Yii::$app->session->addFlash('danger', Module::t('amossocialauth', 'Social Signup Disabled'));

            return $this->goHome();
        }

        //Prepare procedure
        if ($status == null) {
            return $this->redirect(
                [
                    'endpoint',
                    'action' => $this->action->id,
                    'provider' => $provider
                ]
            );
        }

        //Change login timeout
        $loginTimeout = Yii::$app->params['loginTimeout'] ?: 3600;

        /**
         * @var $socialProfile Profile
         */
        $socialProfile = $this->userProfile;

        /**
         * @var $socialUser SocialAuthUsers
         */
        $socialUser = SocialAuthUsers::findOne(['identifier' => $socialProfile->identifier, 'provider' => $provider]);

        /**
         * If the social user exists
         */
        if ($socialUser) {
            /**
             * If the user exists
             */
            if ($socialUser->user && $socialUser->user->id) {
                //Check user deactivated
                if ($socialUser->user->status == User::STATUS_DELETED) {
                    Yii::$app->session->addFlash(
                        'danger',
                        Module::t(
                            'amosadmin',
                            'User deactivated. To log in again, request reactivation of the profile.'
                        )
                    );
//                    return $this->goHome();
                    return $this->goBack();
                }

                $signIn = Yii::$app->user->login($socialUser->user, $loginTimeout);

                if ($community_id) {
                    return $this->redirect($urlToCommunity);
                }
                return $this->goBack();
            }
        }

        /**
         * If the mail is not set i can't create user
         */
        if (empty($socialProfile->email)) {
            Yii::$app->session->addFlash(
                'danger',
                Module::t('amossocialauth', 'Unable to register, missing mail permission')
            );

            return $this->goHome();
        }

        //Find for existing user with social email
        $q = User::find();
        $q->where(['email' => $socialProfile->email]);
        $q->orWhere(['username' => $socialProfile->email]);
        $userMatchMail = $q->one();

        if ($userMatchMail && $userMatchMail->id) {
            if (!$this->module->userOverload) {
                Yii::$app->session->set('social-match', $provider);
                Yii::$app->session->set('social-profile', $socialProfile);

                return $this->redirect('/' . AmosAdmin::getInstance()->id . '/security/login');
            } else {
                $this->linkSocialToUser($provider, $socialProfile, $userMatchMail->id);

                //Logijn to the platform
                $signIn = Yii::$app->user->login($userMatchMail, $loginTimeout);

                if ($community_id) {
                    return $this->redirect($urlToCommunity);
                }
                return $this->goHome();
            }
        }

        //Create user and user profile
        $userCreated = $this->createUser($socialProfile);

        /**
         * If user creation fails
         */
        if (!$userCreated || isset($userCreated['error'])) {
            return $this->goHome();
        }

        /**
         * @var $user User
         */
        $user = $userCreated['user'];

        /**
         * @var $userProfile UserProfile
         */
        $userProfile = UserProfile::findOne(['user_id' => $user->id]);

        /**
         * If $newUser is false the user is not created
         */
        if (!$userProfile || !$userProfile->id) {
            Yii::$app->session->addFlash(
                'danger',
                Module::t('amossocialauth', 'Error when loading profile data, try again')
            );

            //Rollback User on error
            $user->id->delete();

            return $this->goHome();
        }

        //Import user profile image
        $importProfile = $this->importUserImage($socialProfile, $userProfile);

        //Back to home on error
        if (!$importProfile) {
            //Rollback on error
            $this->rollBackUser($userProfile);

            return $this->goHome();
        }

        //Create social record
        $socialUser = $this->createSocialUser($userProfile, $socialProfile, $provider);

        //If social profile creation fails
        if (!$socialUser || isset($socialUser['error'])) {
            //Rollback on error
            $this->rollBackUser($userProfile);
            return $this->goHome();
        }

        //Logijn to the platform
        $signIn = Yii::$app->user->login($socialUser->user, $loginTimeout);

        if ($community_id) {
            return $this->redirect($urlToCommunity);
        }

        return $this->goHome();
    }

    /**
     * @param $userProfile UserProfile
     * @return bool - the operation result
     */
    protected function rollBackUser($userProfile)
    {
        /**
         * @var $user User
         */
        $user = $userProfile->user;

        //Delete user profile
        $userProfile->delete();

        //Delete User
        $user->delete();

        return true;
    }

    /**
     * @param \Hybridauth\User\Profile $socialProfile
     * @return bool|int
     */
    protected function createUser(\Hybridauth\User\Profile $socialProfile)
    {
        try {
            //Name Parts (maybe it contains last name
            $userNameParts = explode(' ', $socialProfile->firstName);

            //If the name is explodable generate last name
            if (count($userNameParts)) {
                if (!$socialProfile->lastName) {
                    //This contains only name parts
                    $copyParts = $userNameParts;

                    //Only name part
                    $userName = reset($userNameParts) ?: Yii::t('amossocialauth', 'User');

                    //Shift out name
                    array_shift($copyParts);

                    //Last name of user (or rollback to first name)
                    $userSurname = count($copyParts) ? implode(' ', $copyParts) : $userName;
                } else {
                    //Name of the user
                    $userName = $socialProfile->firstName;

                    //Last name of user
                    $userSurname = $socialProfile->lastName;
                }
            } else {
                $userName = 'User';
                $userSurname = $socialProfile->email;
            }

            /** @var AmosAdmin $adminModule */
            $adminModule = AmosAdmin::getInstance();

            /**
             * @var $newUser integer False or UserId
             */
            $newUser = $adminModule->createNewAccount(
                $userName,
                $userSurname,
                $socialProfile->email,
                true
            );

            //If $newUser is false the user is not created
            if (!$newUser || isset($newUser['error'])) {
                Yii::$app->session->addFlash(
                    'danger',
                    Module::t('amossocialauth', 'Unable to register, user creation error')
                );

                if ($newUser['messages']) {
                    foreach ($newUser['messages'] as $message) {
                        Yii::$app->session->addFlash('danger', Module::t('amossocialauth', reset($message)));
                    }
                }

                return false;
            }

            return $newUser;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param \Hybridauth\User\Profile $socialProfile
     * @param $userProfile
     * @return bool
     */
    protected function importUserImage(\Hybridauth\User\Profile $socialProfile, $userProfile)
    {
        //If profile image url is set
        if ($socialProfile->photoURL) {
            //Request file header
            $fileHeader = @get_headers($socialProfile->photoURL);

            //If the file exists (header 200)
            if (preg_match("|200|", $fileHeader[0]) || preg_match("|304|", $fileHeader[0]) || preg_match(
                    "|302|",
                    $fileHeader[0]
                )) {
                // Get Importer component
                $importTool = new FileImport();

                //The Image content
                $temporaryFile = $this->obtainImage($socialProfile->photoURL);

                if ($temporaryFile == false) {
                    Yii::$app->session->addFlash(
                        'danger',
                        Module::t('amossocialauth', 'Unable to store image file, try again')
                    );

                    return false;
                }

                //Import file as avatar
                $importResult = $importTool->importFileForModel($userProfile, 'userProfileImage', $temporaryFile);

                if (isset($importResult['error'])) {
                    Yii::$app->session->addFlash('danger', $importResult['error']);
                    return false;
                } elseif ($importResult == false) {
                    Yii::$app->session->addFlash(
                        'danger',
                        Module::t('amossocialauth', 'Unable to import the user avatar')
                    );
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param $fileUrl
     * @return string
     */
    protected function obtainImage($fileUrl)
    {
        try {
            //Temporary file path
            $filepath = '/tmp/' . md5($fileUrl);

            //Obtain File Data
            $fileData = file_get_contents($fileUrl);

            //Put content to temporary dir
            file_put_contents($filepath, $fileData);

            //Change permissions
            @chmod($filepath, 0777);

            return $filepath;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param UserProfile $userProfile
     * @param \Hybridauth\User\Profile $socialProfile
     * @param $provider
     * @return bool|SocialAuthUsers
     */
    protected function createSocialUser($userProfile, \Hybridauth\User\Profile $socialProfile, $provider)
    {
        $existsUser = SocialAuthUsers::findOne(['provider' => $provider, 'identifier' => $socialProfile->identifier]);

        if($existsUser && $existsUser->id) {
            return false;
        }

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
                    Yii::$app->session->addFlash(
                        'danger',
                        Module::t('amossocialauth', 'Unable to Link The Social Profile')
                    );
                    return false;
                }
            } else {
                Yii::$app->session->addFlash(
                    'danger',
                    Module::t('amossocialauth', 'Invalid Social Profile, Try again')
                );
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Link current logged user to social account
     * @param $provider
     * @return \yii\web\Response
     */
    public function actionLinkUser($provider, $status = null)
    {
        $this->setUpLayout('login');

        /**
         * If the user is already logged in go to home
         */
        if (\open20\amos\core\utilities\CurrentUser::isPlatformGuest()) {
            Yii::$app->session->addFlash('danger', Module::t('amossocialauth', 'Please LogIn to your account First'));

            return $this->goHome();
        }

        /**
         * If linking is not enabled
         */
        if (!$this->module->enableLink) {
            Yii::$app->session->addFlash('danger', Module::t('amossocialauth', 'Social Linking Disabled'));

            return $this->goBack();
        }

        //Prepare procedure
        if ($status == null) {
            return $this->redirect(
                [
                    'endpoint',
                    'action' => $this->action->id,
                    'provider' => $provider
                ]
            );
        }

        /**
         * Find for existing social profile with the same ID
         * @var $existingUserProfile SocialAuthUsers
         */
        $existingUserProfile = SocialAuthUsers::findOne(
            ['identifier' => $this->userProfile->identifier, 'provider' => $provider]
        );

        /**
         * If the social profile exists go back with notice
         */
        if ($existingUserProfile && $existingUserProfile->id) {
            if ($existingUserProfile->user_id == Yii::$app->user->id) {
                Yii::$app->session->addFlash('danger', Module::t('amossocialauth', 'Social Profile Already Connected'));
            } else {
                Yii::$app->session->addFlash(
                    'danger',
                    Module::t(
                        'amossocialauth',
                        'Social Profile Already Connected to Another User'
                    )
                );
            }

            return $this->goBack();
        }

        /**
         * @var $userProfileArray array User profile from provider
         */
        $userProfileArray = (array)$this->userProfile;
        $userProfileArray['provider'] = $provider;
        $userProfileArray['user_id'] = Yii::$app->user->id;

        /**
         * @var $socialUser SocialAuthUsers
         */
        $socialUser = new SocialAuthUsers();

        /**
         * If all data can be loaded to new record
         */
        if ($socialUser->load(['SocialAuthUsers' => $userProfileArray])) {
            /**
             * Is valid social user
             */
            if ($socialUser->validate()) {
                $socialUser->save();

                Yii::$app->session->addFlash('success', Module::t('amossocialauth', 'Social profile Linked'));

                return $this->goBack();
            } else {
                Yii::$app->session->addFlash(
                    'danger',
                    Module::t('amossocialauth', 'Unable to Link The Social Profile'). ': '. $socialUser->getFirstError()
                );

                return $this->goBack();
            }
        } else {
            Yii::$app->session->addFlash('danger', Module::t('amossocialauth', 'Invalid Social Profile, Try again'));

            return $this->goBack();
        }
    }

    /**
     * Link current logged user to social account
     * @param $provider
     * @return string
     */
    public function actionLinkSocialAccount($provider, $status = null)
    {
        $this->setUpLayout('empty');

        /**
         * If the user is already logged in go to home
         */
        if (Yii::$app->user->isGuest) {
            $message = Module::t('amossocialauth', 'Please LogIn to your account First');
            return $this->render('link-social-account', ['message' => $message]);
        }

        /**
         * If linking is not enabled
         */
        if (!$this->module->enableLink) {
            $message = Module::t('amossocialauth', 'Social Linking Disabled');
            return $this->render('link-social-account', ['message' => $message]);
        }

        //Prepare procedure
        if ($status == null) {
            return $this->redirect(
                [
                    'endpoint',
                    'action' => $this->action->id,
                    'provider' => $provider
                ]
            );
        }

        /**
         * Find for existing social profile with the same ID
         * @var $existingUserProfile SocialAuthUsers
         */
        $existingUserProfile = SocialAuthUsers::findOne(
            ['identifier' => $this->userProfile->identifier, 'provider' => $provider]
        );

        /**
         * If the social profile exists go back with notice
         */
        if ($existingUserProfile && $existingUserProfile->id) {
            if ($existingUserProfile->user_id == Yii::$app->user->id) {
                $message = Module::t('amossocialauth', 'Social Profile Already Connected');
            } else {
                $message = Module::t('amossocialauth', 'Social Profile Already Connected to Another User');
            }

            return $this->render('link-social-account', ['message' => $message]);
        }

        /**
         * @var $userProfileArray array User profile from provider
         */
        $userProfileArray = (array)$this->userProfile;
        $userProfileArray['provider'] = $provider;
        $userProfileArray['user_id'] = Yii::$app->user->id;

        /**
         * @var $socialUser SocialAuthUsers
         */
        $socialUser = new SocialAuthUsers();

        /**
         * If all data can be loaded to new record
         */
        if ($socialUser->load(['SocialAuthUsers' => $userProfileArray])) {
            /**
             * Is valid social user
             */
            if ($socialUser->validate()) {
                $socialUser->save();

                $message = Module::t('amossocialauth', 'Social profile Linked');
            } else {
                $message = Module::t('amossocialauth', 'Unable to Link The Social Profile');
            }
        } else {
            $message = Module::t('amossocialauth', 'Invalid Social Profile, Try again');
        }
        return $this->render('link-social-account', ['message' => $message]);
    }

    /**
     * UnLink current logged user to social account
     * @param $provider
     * @return \yii\web\Response
     */
    public function actionUnlinkUser($provider)
    {
        $this->setUpLayout('login');

        /**
         * If the user is already logged in go to home
         */
        if (\open20\amos\core\utilities\CurrentUser::isPlatformGuest()) {
            Yii::$app->session->addFlash('danger', Module::t('amossocialauth', 'Please LogIn to your account First'));

            return $this->goHome();
        }

        /**
         * If linking is not enabled
         */
        if (!$this->module->enableLink) {
            Yii::$app->session->addFlash('danger', Module::t('amossocialauth', 'Social Linking Disabled'));

            return $this->goBack();
        }

        /**
         * @var $socialUser SocialAuthUsers
         */
        $socialUser = SocialAuthUsers::findOne(
            [
                'user_id' => Yii::$app->user->id,
                'provider' => $provider
            ]
        );

        /**
         * If linking is not enabled
         */
        if (!$socialUser || !$socialUser->id) {
            Yii::$app->session->addFlash('danger', Module::t('amossocialauth', 'Social User Not Found'));

            return $this->goBack();
        }

        //If found delete and go back
        $socialUser->delete();

        //Reponse good state
        Yii::$app->session->addFlash('success', Module::t('amossocialauth', 'Social Account Unlinked'));

        //Go back
        return $this->goBack();
    }

    /**
     * Create new social profile on db a link to selected user_id
     * @param $provider
     * @param Profile $socialProfile
     * @param $user_id
     * @return \yii\web\Response
     */
    protected function linkSocialToUser($provider, Profile $socialProfile, $user_id)
    {
        /**
         * @var $userProfileArray array User profile from provider
         */
        $userProfileArray = (array)$socialProfile;
        $userProfileArray['provider'] = $provider;
        $userProfileArray['user_id'] = $user_id;

        /**
         * @var $socialUser SocialAuthUsers
         */
        $socialUser = new SocialAuthUsers();

        /**
         * If all data can be loaded to new record
         */
        if ($socialUser->load(['SocialAuthUsers' => $userProfileArray])) {
            /**
             * Is valid social user
             */
            if ($socialUser->validate()) {
                $socialUser->save();

                Yii::$app->session->addFlash('success', Module::t('amossocialauth', 'Social profile Linked'));

                return true;
            } else {
                Yii::$app->session->addFlash(
                    'danger',
                    Module::t('amossocialauth', 'Unable to Link The Social Profile')
                );

                return false;
            }
        } else {
            Yii::$app->session->addFlash('danger', Module::t('amossocialauth', 'Invalid Social Profile, Try again'));

            return false;
        }
    }

    /**
     * UnLink current logged user to social account
     * @param $provider
     * @return string
     */
    public function actionUnlinkSocialAccount($provider)
    {
        $this->setUpLayout('empty');

        /**
         * If the user is already logged in go to home
         */
        if(isset(Yii::$app->params['platformConfigurations']['guestUserId'])){
            $isGuest = \open20\amos\core\utilities\CurrentUser::isPlatformGuest();
        }else {
            $isGuest = \Yii::$app->user->isGuest;
        }
        if ($isGuest) {
            $message = Module::t('amossocialauth', 'Please LogIn to your account First');
            if (Yii::$app->request->isPost) {
                $result['result'] = false;
                $result['message'] = $message;
                return json_encode($result);
            }
            return $this->render('link-social-account', ['message' => $message]);
        }

        /**
         * If linking is not enabled
         */
        if (!$this->module->enableLink) {
            $message = Module::t('amossocialauth', 'Social Linking Disabled');
            if (Yii::$app->request->isPost) {
                $result['result'] = false;
                $result['message'] = $message;
                return json_encode($result);
            }
            return $this->render('link-social-account', ['message' => $message]);
        }

        /**
         * @var $socialUser SocialAuthUsers
         */
        $socialUser = SocialAuthUsers::findOne(
            [
                'user_id' => Yii::$app->user->id,
                'provider' => $provider
            ]
        );

        /**
         * If linking is not enabled
         */
        if (!$socialUser || !$socialUser->id) {
            $message = Module::t('amossocialauth', 'Social User Not Found');

            if (Yii::$app->request->isPost) {
                $result['result'] = false;
                $result['message'] = $message;
                return json_encode($result);
            }
            return $this->render('link-social-account', ['message' => $message]);
        }


        //If found delete and go back
        $socialUser->delete();

        $message = Module::t('amossocialauth', 'Social Account Unlinked');

        if (Yii::$app->request->isPost) {
            $result['result'] = true;
            $result['message'] = $message;
            return json_encode($result);
        }
        return $this->render('link-social-account', ['message' => $message]);
    }

    /**
     * @param string $provider
     * @return \Hybrid_User_Profile|\yii\web\Response
     */
    public function actionGetUserSocial($provider = 'facebook', $urlToRedirect = null){

        /**
             * @var $adapter \Hybrid_Provider_Adapter
             */
            $adapter = $this->authProcedure($provider, Yii::$app->params['platform']['backendUrl']);
            /**
             * If the adapter is not set go back to home
             */
            if (!$adapter) {
//            return $this->goHome();
                return $this->goBack();
            }

            /**
             * @var $userProfile \Hybrid_User_Profile
             */
            $userProfile = $adapter->getUserProfile();

            /**
             * Kick off social user
             */
            $adapter->logout();

            /**
             * @var $socialUser SocialAuthUsers
             */
            $socialUser = SocialAuthUsers::findOne(['identifier' => $userProfile->identifier,
                'provider' => $provider]);

            /**
             * If the social user exists
             */
            if ($socialUser) {
                $userProfile = new \Hybrid_User_Profile();
                $profile = $socialUser->user->userProfile;
                $userProfile->firstName = $profile->nome;
                $userProfile->lastName = $profile->cognome;
                $userProfile->email = $socialUser->user->email;
            }
//        pr(\Yii::$app->getUser()->getReturnUrl());die;
        if(strpos('?', $urlToRedirect) > 0){
                $separator = '&';
        }else{
            $separator = '?';
        }
        return $this->redirect($urlToRedirect.$separator.'userSocial='. urlencode(json_encode($userProfile)));
    }


}
