<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

namespace app\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\LoginForm;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\user\User;
use open20\amos\cwh\models\CwhTagOwnerInterestMm;
use open20\amos\cwh\utility\CwhUtil;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\models\NotificationConf;
use open20\amos\notificationmanager\models\NotificationsConfOpt;
use frontend\models\PrivacyHistory;
use preference\userprofile\exceptions\CreationRegisterdUserException;
use app\models\UserRegistrationForm;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class SiteController
 * @package frontend\controllers
 */
class RegistrationController extends Controller
{
    /**
     * @var mixed|object|\yii\web\Session|null
     */
    private $session;
    /**
     * @var mixed
     */
    private $idmData;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['user'],
                'rules' => [
                    [
                        'actions' => ['user'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                        'matchCallback' => function ($rule, $action) {
                            // BLOCCO l'accesso alle azioni del wizard se non esistono i dati in SPID.
                            // l'ultima azione della registrazione è l'eliminazione di questo dato in sessione.
                            $val = true;
                            if (empty(Yii::$app->session->get('IDM'))) {
                                $val = false;
                            }
                            return $val;
                        }
                    ],
                    [
                        'actions' => [
                            'user-change',
                            'unsubscribe'
                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'user' => ['get', 'post'],
                ],
            ],
        ];
    }


    public function init()
    {
        $this->session = \Yii::$app->session;

        if (!$this->session->getIsActive()) {
            $this->session->open();
        }

        $this->idmData = Yii::$app->session->get('IDM');
        $this->layout = '@vendor/open20/amos-layout/src/views/layouts/main';
//        $this->setUpLayout('@vendor/open20/amos-layout/src/views/layouts/main');
//        $this->view->params['hideUserMenuHeader'] = true;

        parent::init();
    }

//    /**
//     * @inheritdoc
//     */
//    public function actions()
//    {
//        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ]
//        ];
//    }

    /**
     * Logs in a user.
     * @return string|Response
     */
    public function actionUser()
    {
        // $this->session->remove('IDM');

        /** @var UserRegistrationForm $model **/
        $model = new UserRegistrationForm();
        $model->scenario = $model::WIZARD_SCENARIO;
        $upVuoto = AmosAdmin::instance()->createModel('UserProfile');


        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

//            VarDumper::dump($model->getAttributes(), 5,true);
//
//
//
//            die('validate OK');

            // salvo i dati
            $transaction = Yii::$app->db->beginTransaction();

            try {

                $module = \Yii::$app->getModule('amosadmin');
                $userCreatedArray = $module->createNewAccount(
                    empty($model->name) ? '-' : $model->name,
                    empty($model->surname) ? '-' : $model->surname,
                    $model->email,
                    ($model->privacy == '1') ? true : false
                );

                if (isset($userCreatedArray['error']) && ($userCreatedArray['error'] >= 1)) {
                    $str = VarDumper::dumpAsString($userCreatedArray['messages'], 5, false);
                    throw new Exception('Impossibile creare l\'anagrafica: ' . $str);
                }

                $user = $userCreatedArray['user'];
                $user->status = 10;
                $user->save(false);

                /** @var UserProfile $userProfile */
                $userProfile = $user->userProfile;
                $userProfile->codice_fiscale = $this->idmData['codiceFiscale'];
                $userProfile->nome = empty($model->name) ? '' : $model->name;
                $userProfile->cognome = empty($model->surname) ? '' : $model->surname;
                $userProfile->sesso = $model->gender;
                $userProfile->status = UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED;
                $userProfile->user_profile_age_group_id = $model->user_profile_age_group_id;
                $userProfile->provincia_residenza_id = $model->provincia_residenza_id;
                $userProfile->comune_residenza_id = $model->comune_residenza_id;
                $userProfile->user_profile_stati_civili_id = $model->user_profile_stati_civili_id;
                $userProfile->user_profile_titoli_studio_id = $model->user_profile_titoli_studio_id;
                $userProfile->save(false);

                foreach ($model->interestTagValues as $interestTagValue) {
                    if (!empty($interestTagValue)) {
                        //VarDumper::dump($interestTagValue, 5,true);
                        foreach ($interestTagValue as $keyRoot => $tagIds) {
                            foreach ($tagIds as $tagId) {
                                $t = \open20\amos\tag\models\Tag::findOne($tagId);
                                if (!empty($t)) {
                                    CwhUtil::addNewUserInterest(
                                        $t,
                                        intval($userProfile->id)
                                    );
                                }
                            }
                        }
                    }
                }

                /** @var AmosNotify $notifyModule */
                $notifyModule = Yii::$app->getModule('notify');

                $emailFrequency = $model->email_frequency_id;
                $smsFrequency   = 0;
                $ok = $notifyModule->saveNotificationConf($user->id, $emailFrequency, $smsFrequency);

                /*
                VarDumper::dump($ok, 5, true);
                echo ('<hr>');
                VarDumper::dump($model->getAttributes(), 5, true);
                echo ('<hr>');
                VarDumper::dump($userProfile->getAttributes(), 5,true);

                $transaction->rollBack();
                throw new Exception('TEST');
                */

                /** @var $modelPrivacy */
                PrivacyHistory::newAcceptedPrivacy($user->id);

                $transaction->commit();

            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('danger', AmosAdmin::t('amosadmin', 'Errore durante la creazione dell\'utente: ') . $e->getMessage());

                Yii::error($e->getMessage());

                return $this->render('user', [
                    'model' => $model,
                    'upVuoto' => $upVuoto,
                    'idmData' => $this->idmData,
                ]);
            }

            $loginTimeout = \Yii::$app->params['loginTimeout'] ?: 3600;
            \Yii::$app->user->login($user, $loginTimeout);
            $this->session->remove('IDM');

            $url = \yii\helpers\Url::to(['/site/logout', 'redir' =>
                '/Shibboleth.sso/Logout?return=https://idpcwrapper.crs.lombardia.it/PublisherMetadata/Logout?dest=' . urlencode(\yii\helpers\Url::to('/thank-you-page-iscrizione', true))
            ], true);
            return $this->redirect($url);
        }


        /*if ($model->errors) {
            VarDumper::dump($model->errors,3,1); die;
        }*/

        return $this->render('user', [
            'model' => $model,
            'idmData' => $this->idmData,
            'tags' => [],
            'subtitle' => AmosAdmin::t('amosadmin', 'Registrati per ricevere informazioni personalizzate e sempre aggiornate sulle nostre iniziative'),
        ]);
    }

    public function actionUserChange() {
        if(Yii::$app->user->isGuest) {
            $this->redirect('/login');
        }
        else if(!Yii::$app->user->can('STANDARD_USER')) {
            $this->redirect('/');
        }
        else {
            /** @var User $user */
            $user = User::findOne(Yii::$app->user->id);

            // Privacy
            $lastRecord = PrivacyHistory::getLastUserRecord($user->id);
            $privacyAccepted = ($lastRecord->isAccepted);

            /** @var UserRegistrationForm $model */
            $model = new UserRegistrationForm();

            if($privacyAccepted) {
                $model->scenario = UserRegistrationForm::UPDATE_SCENARIO;
            }
            else {
                $model->scenario = UserRegistrationForm::RETENTION_SCENARIO;
            }
            $tags = CwhTagOwnerInterestMm::find()
                ->andWhere(['interest_classname' => 'simple-choice'])
                ->andWhere(['classname' => UserProfile::class])
                ->andWhere(['record_id' => $user->profile->id])
                ->asArray()
                ->all();

            $tagsArray = ArrayHelper::map($tags, function ($el) {
                return $el['id'];
            }, 'tag_id', 'root_id');
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // salvo i dati
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $user->email = $model->email;
                    $user->save(false);

                    if($privacyAccepted) {
                        // Rimozione tag
                        foreach($tags as $tag):
                            CwhTagOwnerInterestMm::findOne($tag['id'])->delete();
                        endforeach;

                        // Aggiunta tag
                        foreach ($model->interestTagValues as $interestTagValue) {
                            if (!empty($interestTagValue)) {
                                //VarDumper::dump($interestTagValue, 5,true);
                                foreach ($interestTagValue as $keyRoot => $tagIds) {
                                    foreach ($tagIds as $tagId) {
                                        $t = \open20\amos\tag\models\Tag::findOne($tagId);
                                        if (!empty($t)) {
                                            CwhUtil::addNewUserInterest(
                                                $t,
                                                intval($user->profile->id)
                                            );
                                        }
                                    }
                                }
                            }
                        }
                        // Nuovi tag
                        $tagsArray = $model->interestTagValues['simple-choice'];
                    }
                    /** @var AmosNotify $notifyModule */
                    $notifyModule = Yii::$app->getModule('notify');
                    $notifyModule->saveNotificationConf($user->id, $model->email_frequency_id);

                    // Aggiornamento privacy
                    if(!$privacyAccepted) {
                        PrivacyHistory::destroyUserToken($user->id);
                        PrivacyHistory::newAcceptedPrivacy($user->id);
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', AmosAdmin::t('amosadmin', 'Modifiche salvate con successo.'));
                    $model->scenario = UserRegistrationForm::UPDATE_SCENARIO;
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('danger', AmosAdmin::t('amosadmin', 'Errore durante l\'aggiornamento delle preferenze: ') . $e->getMessage());

                    Yii::error($e->getMessage());

                    return $this->render('user', [
                        'model' => $model,
                        'tags' => $tagsArray,
                        'subtitle' => AmosAdmin::t('amosadmin', 'Modifica i tuoi dati per rimanere aggiornato sulle novità.'),
                    ]);
                }
            }
            else {
                $model->name = $user->profile->nome;
                $model->surname = $user->profile->cognome;
                $model->email = $user->email;
                $model->email_frequency_id = NotificationConf::find()->andWhere(['user_id' => $user->id])->one()->email;
            }
            return $this->render('user', [
                'model' => $model,
                'idmData' => null,
                'tags' => $tagsArray,
                'subtitle' => AmosAdmin::t('amosadmin', 'Modifica i tuoi dati per rimanere aggiornato sulle novità.'),
            ]);
        }
    }

    public function actionUserUnsubscribe(){
        // Popolamento dati base del form
        /** @var UserRegistrationForm $model */
        $model = new UserRegistrationForm();
        $model->scenario = $model::UNSUBSCRIBE_SCENARIO;

        if(!$model->load(Yii::$app->request->post())) {
            // Logout forzato
            Yii::$app->user->logout();

            // Login con token
            $authTimeout = 3600 * 24 * 30;
            if (!empty(\Yii::$app->user->authTimeout)) {
                $authTimeout = \Yii::$app->user->authTimeout;
            }
            /** @var LoginForm $modelLogin */
            $adminModule = Yii::$app->getModule('amosadmin');
            $modelLogin = $adminModule->createModel('LoginForm');
            $token = \Yii::$app->request->get('token');

            $user = PrivacyHistory::getUserByToken($token);
            if (!$user) {
                return $this->redirect('/login');
            }
            Yii::$app->user->login($user, $modelLogin->rememberMe ? $authTimeout : 0);

            /** @var User $user */
            $user = User::findOne(Yii::$app->user->id);

            $model->name = $user->profile->nome;
            $model->surname = $user->profile->cognome;
            $model->email = $user->email;

            return $this->render('user', [
                'model' => $model,
                'subtitle' => Yii::t('amosapp', '#unsubscribeConfirmMessage'),
                'hideTitle' => true
            ]);
        }
        else {
            /** @var User $user */
            $user = User::findOne(Yii::$app->user->id);

            $model->name = $user->profile->nome;
            $model->surname = $user->profile->cognome;
            $model->email = $user->email;

            $transaction = Yii::$app->db->beginTransaction();
            try {
                /*
                // Tag
                $tags = CwhTagOwnerInterestMm::find()
                    ->andWhere(['interest_classname' => 'simple-choice'])
                    ->andWhere(['classname' => UserProfile::class])
                    ->andWhere(['record_id' => $user->profile->id])
                    ->asArray()
                    ->all();

                // Rimozione tag
                foreach ($tags as $tag):
                    CwhTagOwnerInterestMm::findOne($tag['id'])->delete();
                endforeach;*/
                /** @var AmosNotify $notifyModule */
                $notifyModule = Yii::$app->getModule('notify');
                $notifyModule->saveNotificationConf($user->id, NotificationsConfOpt::EMAIL_OFF);

                // Reset vecchio token
                PrivacyHistory::destroyUserToken($user->id);

                /** @var $modelPrivacy */
                PrivacyHistory::newRejectedPrivacy($user->id);

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('danger', AmosAdmin::t('amosadmin', 'Errore durante la didiscrizione: ') . $e->getMessage());

                Yii::error($e->getMessage());

                return $this->render('user', [
                    'model' => $model,
                    'subtitle' => Yii::t('amosapp', '#unsubscribeConfirmMessage'),
                    'hideTitle' => true
                ]);
            }
            Yii::$app->user->logout();
            return $this->redirect(['/thank-you-page-disiscrizione']);
        }
    }

    public function actionAbortRegistration($redir=null) {
        $this->session->remove('IDM');
        $referrer = \Yii::$app->request->referrer;
        if ($redir) {
            return $this->redirect($redir);
        }
        return $this->redirect($referrer ? $referrer : \Yii::$app->params['platform']['frontendUrl']);
    }
}