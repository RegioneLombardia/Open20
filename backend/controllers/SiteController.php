<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

namespace backend\controllers;

use common\models\FirstAccessForm;
use common\models\ForgotPasswordForm;
use common\models\LoginForm;
use common\models\User;
use open20\amos\admin\models\UserProfile;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use \yii\helpers\Url;
use yii\web\Controller;
use yii\web\Cookie;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'login',
                            'error',
                            'login_error',
                            'insert-auth-data',
                            'inserisci-dati-autenticazione',
                            'forgot-password',
                            'expired-login',
                            'language',
                            'privacy',
                            'cookies',
                        ],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'logout',
                            'index',
                            'privacy',
                            'cookies',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        $this->layout = '@vendor/open20/amos-layout/src/views/layouts/main';
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {
        return $this->redirect(\Yii::$app->params['platform']['backendUrl'] . "/admin/security/login");
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        $userId = Yii::$app->getUser()->getId();

        Yii::$app->user->logout();

        if (\Yii::$app->params['template-amos']) {
            $ids = \open20\amos\dashboard\models\AmosUserDashboards::find()->andWhere(['user_id' => $userId])->select('id');
            \open20\amos\dashboard\models\AmosUserDashboardsWidgetMm::deleteAll(['IN', 'amos_user_dashboards_id', $ids]);
            \open20\amos\dashboard\models\AmosUserDashboards::deleteAll(['user_id' => $userId]);
        }

        return $this->goHome();
    }

    /**
     * Send Login-infos to user
     * @param $id User ID
     * @return mixed
     */
    public function actionSpedisciCredenziali($id) {
        $model = UserProfile::findOne($id);
        if ($model && $model->user && $model->user->email) {

            $model->user->generatePasswordResetToken();
            $model->user->save(false);

            $soggetto = 'Benvenuto nella piattaforma ' . Yii::$app->name;
            if ($model->sesso == 'Femmina') {
                $soggetto = 'Benvenuta nella piattaforma ' . Yii::$app->name;
            }
            $mail = Yii::$app->mailer
                    ->compose(
                            [
                        'html' => '@vendor/open20/amos-admin/src/mail/user/credenziali-html',
                        'text' => '@vendor/open20/amos-admin/src/mail/user/credenziali-text'
                            ], [
                        'profile' => $model,
                    ])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setReplyTo([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo($model->user->email)
                    ->setSubject($soggetto);

            /* @var $message \yii\swiftmailer\Message */

            if ($mail instanceof \yii\swiftmailer\Message && isset($_GET['preview'])) {

                $swiftMessage = $mail->getSwiftMessage();
                $r = new \ReflectionObject($swiftMessage);
                $parentClassThatHasBody = $r->getParentClass()
                        ->getParentClass()
                        ->getParentClass(); //\Swift_Mime_SimpleMimeEntity
                $body = $parentClassThatHasBody->getProperty('_immediateChildren');
                $body->setAccessible(true);
                $children = $body->getValue($swiftMessage);
                foreach ($children as $child) {
                    if ($child instanceof \Swift_MimePart &&
                            $child->getContentType() == 'text/html'
                    ) {
                        $html = $child->getBody();
                        break;
                    }
                }

                echo $html;
                die;
            }
            $sent = $mail->send();

            if ($sent) {
                Yii::$app->session->addFlash('success', Yii::t('amosplatform', 'Credenziali spedite correttamente alla email {email}', ['email' => $model->user->email]));
            } else {
                Yii::$app->session->addFlash('danger', Yii::t('amosplatform', 'Si è verificato un errore durante la spedizione delle credenziali'));
            }
        } else {
            Yii::$app->session->addFlash('danger', Yii::t('amosplatform', 'L\'utente non esiste o è sprovvisto di email, impossibile spedire le credenziali'));
        }
        return $this->redirect(Url::previous());
    }

    /**
     * @deprecated
     * Login-info choice at register step
     * @return string
     */
    public function actionInserisciDatiAutenticazione() {
        $this->actionInsertAuthData();
    }

    /**
     * Login-info choice at register step
     * @return string
     */
    public function actionInsertAuthData() {

        $this->layout = '@vendor/open20/amos-core/views/layouts/login';
        $password_reset_token = null;
        $user = null;
        $username = null;
        if (NULL !== (Yii::$app->getRequest()->getQueryParam('token'))) {
            $password_reset_token = Yii::$app->getRequest()->getQueryParam('token');            
            $user = User::findByPasswordResetToken($password_reset_token);            
            if ($user) {
                $username = $user->username;
            }
        }
        if ($user && !$username) {
            if (Yii::$app->request->isPost) {
                $model = new FirstAccessForm();
                if ($model->load(Yii::$app->request->post())) {
                    if ($model->verifyUsername($model->username)) {
                        Yii::$app->getSession()->addFlash('danger', Yii::t('amosplatform', 'Attenzione! La username inserita &egrave; gi&agrave; in uso. Sceglierne un&#39;altra.'));
                        return $this->render('first_access', [
                                    'model' => $model,
                        ]);
                    } else {
                        $user->setPassword($model->password);
                        $user->username = $model->username;
                        if ($user->validate() && $user->save()) {
                            Yii::$app->getSession()->addFlash('success', Yii::t('amosplatform', 'Perfetto! Hai scelto correttamente le tue credenziali, ora puoi effettuare l&#39;accesso.'));
                            $user->removePasswordResetToken();
                            $user->save();
                            return $this->redirect(\Yii::$app->getUser()->loginUrl);
                        } else {
                            return $this->render('login_error', ['message' => Yii::t('amosplatform', " Errore! Il sito non ha risposto, probabilmente erano in corso operazioni di manutenzione. Riprova più tardi.")]);
                        }
                    }
                } else {
                    $model->token = $password_reset_token;
                    return $this->render('first_access', [
                                'model' => $model,
                    ]);
                }
            } else {
                $model = new FirstAccessForm();
                $model->token = $password_reset_token;
                return $this->render('first_access', [
                            'model' => $model,
                ]);
            }
        } else if ($user && $username) {
            if (Yii::$app->request->isPost) {
                $model = new FirstAccessForm();
                if ($model->load(Yii::$app->request->post())) {

                    $user->setPassword($model->password);

                    if ($user->validate() && $user->save()) {
                        Yii::$app->getSession()->addFlash('success', Yii::t('amosplatform', 'Perfetto! Hai scelto correttamente la tua password, ora puoi effettuare l&#39;accesso.'));
                        $user->removePasswordResetToken();
                        $user->save();
                        return $this->redirect(\Yii::$app->getUser()->loginUrl);
                    } else {
                        return $this->render('login_error', ['message' => Yii::t('amosplatform', " Errore! Il sito non ha risposto, probabilmente erano in corso operazioni di manutenzione. Riprova più tardi.")]);
                    }
                } else {
                    $model->token = $password_reset_token;
                    $model->username = $username;
                    return $this->render('reset_password', [
                                'model' => $model,
                    ]);
                }
            } else {
                $model = new FirstAccessForm();
                $model->token = $password_reset_token;
                $model->username = $username;
                return $this->render('reset_password', [
                            'model' => $model,
                ]);
            }
        } else {
            return $this->render('login_error', ['message' => Yii::t('amosplatform', " Errore! Il tempo per poter accedere è scaduto. Contatti l'amministratore e si faccia reinviare la mail di accesso.")]);
        }
    }

    /**
     * Forgotten password form
     * @return string|\yii\web\Response
     */
    public function actionForgotPassword() {
        $this->layout = '@vendor/open20/amos-core/views/layouts/login';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new ForgotPasswordForm();
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getSession()->addFlash('success', Yii::t('amosplatform', 'Verrà inviata una mail con i dati all\'indirizzo fornito, se presente nel sistema'));

            if ($model->username != NULL) {
                $dati_utente = $model->verifyUsername($model->username);

                if ($dati_utente) {
                    $this->actionSpedisciCredenziali($dati_utente->id);
                }
            } else if ($model->codice_fiscale != NULL) {
                $dati_utente = $model->verifySocialSecurityNumber($model->codice_fiscale);

                if ($dati_utente) {
                    $this->actionSpedisciCredenziali($dati_utente->id);
                }
            }
        }

        return $this->render('forgot_password', [
            'model' => $model,
        ]);
    }
    
    public function actionLanguage() {

        $data = Yii::$app->request->post();

        if (!empty($data['language'])) {
            \Yii::$app->language = $data['language'];

            $languageCookie = new Cookie([
                'name' => 'language',
                'value' => $data['language'],
                'expire' => time() + 60 * 60 * 24 * 30, // 30 days
            ]);
            Yii::$app->response->cookies->add($languageCookie);
        }
        if (!empty($data['url'])) {
            return $this->redirect([$data['url']]);
        }
        return $this->redirect(Url::previous());
    }

    /*  public function beforeAction($action) {
      if ($action->id == 'language') {
      $this->enableCsrfValidation = false;
      Yii::$app->controller->enableCsrfValidation = FALSE;
      }

      return parent::beforeAction($action);
      }
     */

    /**
     * This action render the privacy notice page
     * @return string
     */
    public function actionPrivacy()
    {
        return $this->render('privacy');
    }
    
    /**
     * This action render the cookies page
     * @return string
     */
    public function actionCookies()
    {
        return $this->render('cookies');
    }
}
