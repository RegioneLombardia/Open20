<?php

namespace open20\amos\moodle\controllers;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\user\User;
use open20\amos\core\user\AmosUser;

use open20\amos\moodle\models\MoodleCourse;
use open20\amos\moodle\models\MoodleUser;
use open20\amos\moodle\models\search\MoodleCourseSearch;
use open20\amos\moodle\utility\MoodleUtility;
use open20\amos\moodle\models\ServiceCall;
use open20\amos\moodle\assets\MoodleAsset;
use open20\amos\moodle\utility\EmailUtil;
use open20\amos\moodle\AmosMoodle;
use open20\amos\moodle\models\MoodleCategory;
use open20\amos\moodle\models\PayPalTransactions;

use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * This is the class for controller "CourseController".
 */
class CourseController extends CrudController
{

    /**
     *
     * @var type method to ask something to Moodle
     */
    protected $serviceCall;

    /**
     *
     * @var type 
     */
    protected $returnUrl;

    /**
     *
     * @var type 
     */
    protected $cancelUrl;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'index',
                                'not-enrolled-course',
                                'ask-enrolment-in-closed-course',
                                'paypal-course',
                                'paypal-payment',
                                'paypal-transaction-ok',
                                'paypal-transaction-ko',
                            ],
                            'roles' => [
                                AmosMoodle::MOODLE_STUDENT,
                                AmosMoodle::MOODLE_ADMIN
                            ]
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'enrol-in-course',
                            ],
                            'roles' => [
                                AmosMoodle::MOODLE_RESPONSABILE,
                                AmosMoodle::MOODLE_ADMIN,
                                AmosMoodle::MOODLE_STUDENT
                            ]
                        ],
                    ]
                ],
        ]);

        return $behaviors;
    }

    /**
     * 
     */
    public function init()
    {
        MoodleAsset::register(Yii::$app->view);

        $this->setModelObj(new MoodleCourse());
        $this->setModelSearch(new MoodleCourseSearch());
        $this->serviceCall = new ServiceCall();

        $this->setAvailableViews([
            'icon' => [
                'name' => 'icon',
                'label' => AmosIcons::show('view-list') . Html::tag('p', Yii::t('amoscore', 'Card')),
                'url' => '?currentView=icon'
            ],
            /*            'list' => [
              'name' => 'list',
              'label' => Yii::t('amoscore', '{iconaLista}' . Html::tag('p', Yii::t('amoscore', 'Aaa')), [
              'iconaLista' => AmosIcons::show('view-list')
              ]),
              'url' => '?currentView=list'
              ], */
            'grid' => [
                'name' => 'grid',
                'label' => Yii::t('amoscore', '{iconaTabella}' . Html::tag('p', Yii::t('amoscore', 'Table')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
        ]);

        parent::init();
    }

    /**
     * 
     * @param type $layout
     * @param type $uid -> user select for course subscription
     * @param type $org -> organizzazione id
     * @return mixed
     */
    public function actionIndex($layout = null, $uid = null, $org = null)
    {
        Url::remember();

        $withImages = true;

        $category = MoodleUtility::getCommunityCategory();
        $categoryId = (is_null($category))
            ? MoodleCategory::GENERAL_CATEGORY_MOODLE_ID
            : $category->moodle_categoryid;

        $allCoursesArray = $this->serviceCall->getCoursesList($withImages, null, $categoryId);

        if ($uid != null) {
            $this->serviceCall->setUserMoodle($uid);
        }

        $coursesUserEnrolledArray = $this->serviceCall->getCoursesUserEnrolled();

        $coursesList = $this->getModelObj()->getCourseList($allCoursesArray, $coursesUserEnrolledArray);

        $this->view->params['dataProvider'] = new ArrayDataProvider(['allModels' => $coursesList]);
        $this->view->params['uid'] = $uid;
        $this->view->params['org'] = $org;

        if ($uid != null) {
            $uidNomeCognome = UserProfile::findOne($uid);
        }

        $this->view->params['uidNameSurname'] = !empty($uidNomeCognome)
            ? $uidNomeCognome->getNomeCognome()
            : null;

        return parent::actionIndex();
    }

    /**
     * actionNotEnrolledCourse
     * @return mixed
     */
    public function actionNotEnrolledCourse($id, $uid = null, $org = null)
    {

        $this->layout = '@vendor/open20/amos-core/views/layouts/main';

        $course = MoodleCourse::findOne([
            'id' => $id,
        ]);

        if (!empty($course)) {
            $moodleCourseId = $course->moodle_courseid;
            $selfEnrollment = false; //se l'utente può iscriversi al corso da solo

            if ($uid != null) {
                $this->serviceCall->setUserMoodle($uid);
            }

            //se l'utente è iscritto al corso
            $courseEnrolled = $this->serviceCall->isUserEnrolledInCourse($moodleCourseId);

            if (!$courseEnrolled) {
                $selfEnrollment = $this->serviceCall->selfEnrollmentActive($moodleCourseId);
            }

            return $this->render(
                'notEnrolledCourse',
                [
                    'course' => $course,
                    'selfEnrollment' => $selfEnrollment,
                    'courseEnrolled' => $courseEnrolled,
                    'uid' => $uid,
                    'org' => $org,
                ]
            );
        }
    }

    /**
     * 
     * @param type $id      the moodle course id
     * @param type $userId  Id utente da iscrivere. Se vuoto si iscrive l'utente loggato
     *                      Iscrive l'utente ad un corso aperto su Moodle
     * @param type $paypal  iscrizione ad un corso PP
     * @param type $uid     Utente corrente iscrive altro user
     * @param type $org
     * @return type
     * @throws MoodleException
     */
    public function actionEnrolInCourse($id, $userId = null, $paypal = false, $uid = null, $org = null)
    {
        $course = MoodleCourse::findOne([
            'id' => $id,
        ]);
        
        if (($userId == null) && ($uid != null)) {
            $userId = $uid;
        }

        if (!empty($course)) {
            $moodleCourseId = $course->moodle_courseid;

            if ($userId) {
                $userNotValid = true;
                $userToEnrol = User::findOne([
                    'id' => $userId,
                ]);

                if ($userToEnrol) {
                    $amosUser = new AmosUser(['identityClass' => User::className()]);
                    $amosUser->setIdentity($userToEnrol);


                    if ($amosUser->can(AmosMoodle::MOODLE_STUDENT)) {
                        $userNotValid = false;
                        $this->serviceCall->setUserMoodle($userId);
                    }
                }

                if ($userNotValid) {
                    Yii::$app->getSession()->addFlash(
                        'danger',
                        AmosMoodle::t('amosmoodle', '#no_subscription_invalid_user')
                    );

                    if ($amosUser->can(AmosMoodle::MOODLE_RESPONSABILE) || $amosUser->can(AmosMoodle::MOODLE_ADMIN)) {
                        return $this->redirect(['/moodle/course-enrolment/search-courses', 'userId' => $userId]);
                    }
                }
            }

            // L'utente è iscritto al corso?
            $courseEnrolled = $this->serviceCall->isUserEnrolledInCourse($moodleCourseId);

            if (!$courseEnrolled) {
                if ($paypal == false) {
                    // L'utente può iscriversi al corso da solo?
                    $selfEnrollment = $this->serviceCall->selfEnrollmentActive($moodleCourseId);
                    if ($selfEnrollment) {
                        $answer = $this->serviceCall->selfEnrolInCourse($moodleCourseId);

                        if ($answer['status'] != 1) {
                            throw new MoodleException($data['errorcode']);
                        }

                        if ($userId) {
                            EmailUtil::sendEmailEnrolledInCourse($course, $userId);
                        }
                    }
                } else {
                    $enrolPayPal = $this->serviceCall->getEnrolInfo($moodleCourseId);
                    
                    if (!empty($enrolPayPal)) {
                        $this->serviceCall->setEnrolUserViaPayPal(
                            $enrolPayPal[0]['id'], 
                            $this->serviceCall->getMoodleUserId()
                        );
                    }
                }
            }

            // Se un Moodle responsabile ha iscritto un altro utente al corso , rimane nella stessa pagina
            if ($userId) {
                // prima di ricaricare la pagina devo aspettare che venga eseguita la callback dell'iscrizione al corso
                sleep(4);
                Yii::$app->getSession()->addFlash('success', AmosMoodle::t('amosmoodle', '#subscription_success'));
                if ($amosUser->can(AmosMoodle::MOODLE_RESPONSABILE) || $amosUser->can(AmosMoodle::MOODLE_ADMIN)) {
                    return $this->redirect(['/moodle/course-enrolment/search-courses', 'userId' => $userId]);
                }
            } else {
                //Se un utente si è iscritto da solo al corso , accede subito alla community del corso
                
                if (!empty($course->community_id)) {
                    return $this->redirect(['/community/join', 'id' => $course->community_id]);
                }
            }
        }
        // something was wrong go on the course page
        return $this->redirect(['/moodle/course']);
    }

    /**
     * L'utente richiede l'iscrizione ad un corso chiuso. Viene inviata un'email agli amministratori MOODLE.
     * 
     * @param type $id  the moodle course id 
     * @param type $userId to enrol
     * @return type
     */
    public function actionAskEnrolmentInClosedCourse($id = null, $uid = null)
    {        
        $course = MoodleCourse::findOne([
            'id' => $id,
        ]);

        if (!empty($course)) {
            // Moodle Admins emails
            EmailUtil::sendEmailEnrolInClosedCourse($course, $uid);

            Yii::$app->getSession()->addFlash(
                'success',
                AmosMoodle::t(
                    'amosmoodle',
                    '#subscribe_request_sent'
                )
            );
        }

        return $this->redirect(['/moodle']);
    }

    /**
     * 
     * @param type $id  -> course_id
     * @param type $uid -> student_id
     * @return type
     */
    public function actionPaypalCourse($id = null, $uid = null)
    {
        $course = MoodleCourse::findOne(['id' => $id,]);

        if (!empty($course)) {
            $course->enrollment_methods = unserialize($course->enrollment_methods);

            // Current user enrol him/her self?
            if (empty($uid)) {
                $uid = \Yii::$app->getUser()->identity->id;
            } else {
                $moodleUser = MoodleUser::findOne(['user_id' => $uid]);
                if (!empty($moodleUser)) {
                    $this->serviceCall->moodleUser->moodle_userid = $moodleUser->moodle_userid;
                    $this->serviceCall->moodleUserToken = $moodleUser->moodle_token;
                }
            }

            $moodleCourseId = $course->moodle_courseid;
            $selfEnrollment = false; //se l'utente può iscriversi al corso da solo
            $courseEnrolled = $this->serviceCall->isUserEnrolledInCourse($moodleCourseId); //se l'utente è iscritto al corso
            if (!$courseEnrolled) {
                $selfEnrollment = $this->serviceCall->selfEnrollmentActive($moodleCourseId);
            }

            // transaction aborted or in progress?
            $payPalTransactionAborted = PayPalTransactions::find()
                ->andWhere(['user_id' => \Yii::$app->getUser()->identity->id])
                ->andWhere(['<>', 'status', PayPalTransactions::TRANSACTIONS_WORKFLOW_STATUS_EFFETTUATO])
                ->all();
            if (!(empty($payPalTransactionAborted))) {
                foreach($payPalTransactionAborted as $ppa) {
                    $ppa->delete();
                }
            }

            $payPalTransaction = PayPalTransactions::find()
                ->andWhere(['course_id' => $id])
                ->andWhere(['student_id' => $uid])
                ->one();

            if (empty($payPalTransaction)) {
                $payPalTransaction = new PayPalTransactions();
                $payPalTransaction->status = PayPalTransactions::TRANSACTIONS_WORKFLOW_STATUS_DA_EFFETTUARE;

                // TBD - update references
                $courseCost = $course->enrollment_methods['cost'];

                $courseCost = str_replace(['.', ','], ['', ''], $courseCost, $commas);
                // add 00 to convert it for PayPal
                if ($commas == 0) {
                    $courseCost += '.00';
                }

                $payPalTransaction->user_id = \Yii::$app->getUser()->identity->id;
                $payPalTransaction->total = $courseCost;
                $payPalTransaction->course_id = $id;
                $payPalTransaction->student_id = $uid;

                $payPalTransaction->type = 'paypal';
                if ($payPalTransaction->save()) {
                    ;
                }
            }

            $paypalUrl = Yii::$app->getUrlManager()->createUrl([
                '/moodle/course/paypal-payment',
                'uid' => $uid,
                'id' => $payPalTransaction->id,
            ]);

            return $this->render(
                'paypalCourse',
                [
                    'course' => $course,
                    'pp_cost' => $courseCost,
                    'pp_currency' => $course->enrollment_methods['currency'],
                    'uid' => $uid,
                    'selfEnrollment' => $selfEnrollment,
                    'courseEnrolled' => $courseEnrolled,
                    'paypalUrl' => $paypalUrl
                ]
            );
        }
    }

    /**
     * @return \yii\web\Response
     */

    /**
     * 
     * @param type $uid
     * @param type $id
     * @return type
     */
    public function actionPaypalPayment($uid = null, $id = null)
    {
        if ($id) {
            $payPalTransaction = PayPalTransactions::findOne($id);
        }

        $backUrl = \Yii::$app->params['platform']['backendUrl'];
        $this->returnUrl = $backUrl . Yii::$app->getUrlManager()->createUrl([
            '/moodle/course/paypal-transaction-ok',
            'id' => $payPalTransaction->id,
        ]);
        $this->cancelUrl = $backUrl . Yii::$app->getUrlManager()->createUrl([
            '/moodle/course/paypal-transaction-ko',
            'id' => $payPalTransaction->id,
        ]);

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                \Yii::$app->params['paypalClientID'],
                \Yii::$app->params['paypalClientSecret']
            )
        );

        $newConfig = ArrayHelper::merge(
            $apiContext->getConfig(), [
            'mode' => \Yii::$app->params['paypalMode']
        ]);

        $amountTot = $this->getDecimalNumber($payPalTransaction->total);

        $apiContext->setConfig($newConfig);
        // 3. Lets try to create a Payment
        // https://developer.paypal.com/docs/api/payments/#payment_create
        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new \PayPal\Api\Amount();
        $amount->setTotal($amountTot);
        $amount->setCurrency('EUR');

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);

        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls
            ->setReturnUrl($this->returnUrl)
            ->setCancelUrl($this->cancelUrl);

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);
        // 4. Make a Create Call and print the values
        try {
            $payment->create($apiContext);
            $pay = json_decode($payment, true);

            $payPalTransaction->transaction_code = $pay['id'];
            $payPalTransaction->save();

            return $this->redirect($payment->getApprovalLink());
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            // This will print the detailed information on the exception.
            //REALLY HELPFUL FOR DEBUGGING
            echo $ex->getData();
        }
    }

    /**
     * 
     * @return type
     */
    public function actionPaypalTransactionKo($id = null, $token = null)
    {
        $payPalTransaction = PayPalTransactions::find()
            ->andWhere(['id' => $id])
            ->one();

        if (!(empty($payPalTransaction))) {
//            $payPalTransaction->status = PayPalTransactions::TRANSACTIONS_WORKFLOW_STATUS_CANCELLATO;
//            $payPalTransaction->token = $token;
//            $payPalTransaction->save();
            $payPalTransaction->delete();
        }

        $this->addFlash('danger', AmosMoodle::t('amosmoodle', '#payment_ko'));

        return $this->redirect(
                \Yii::$app->params['platform']['backendUrl'] . '/moodle/course'
        );
    }

    /**
     * 
     * @param type $id
     * @param type $paymentId
     * @param type $token
     * @return type
     */
    public function actionPaypalTransactionOk($id = null, $paymentId = null, $token = null)
    {
        $payPalTransaction = PayPalTransactions::find()
            ->andWhere(['transaction_code' => $paymentId])
            ->one();

        $redirectUrl = '/moodle/course';

        if (!(empty($payPalTransaction))) {
            
            $amountTot = $this->getDecimalNumber($payPalTransaction->total);
            $isOk = $this->payByPaypal($amountTot);
            
            if ($isOk == false || empty($isOk) || empty($isOk->state) || $isOk->state != 'approved') {
                return $this->actionPaypalTransactionKo();
            }
            
            $payPalTransaction->status = PayPalTransactions::TRANSACTIONS_WORKFLOW_STATUS_EFFETTUATO;
            $payPalTransaction->token = $token;
            $payPalTransaction->save();

            $course = MoodleCourse::find()
                ->andWhere(['id' => $payPalTransaction->course_id])
                ->one();

            $moodle_user = MoodleUser::findOne([
                'user_id' => $payPalTransaction->student_id,
            ]);

            // Lo iscrivo al corso e creo la relativa community, dovrebbe inviare anche email
            CallbackController::userEnrolmentCreated(
                $course->moodle_courseid, 
                $moodle_user->moodle_userid,
                true,
                $payPalTransaction->total
            );
            
            $this->actionEnrolInCourse(
                $payPalTransaction->course_id, 
                $payPalTransaction->student_id, 
                true
            );
            
            // Redirect to page course, only if the current user is the same to pay
            if ($payPalTransaction->student_id == \Yii::$app->getUser()->identity->id) {
                $redirectUrl = Yii::$app->urlManager->createUrl([
                    '/community/join',
                    'id' => $course->community_id,
                ]);
            } else {
                $course->getMoodleCourseData();

                // Invio email all'utente che ha effettuato l'iscrizione anche
                EmailUtil::sendEmailEnrolledInCourse(
                    $course, 
                    $payPalTransaction->student_id, 
                    $paypal,
                    $payPalTransaction->total,
                    $payPalTransaction->user_id
                );
            }
        }

        $this->addFlash('success', AmosMoodle::t('amosmoodle', '#payment_ok'));

        return $this->redirect(
            \Yii::$app->params['platform']['backendUrl'] . $redirectUrl
        );
    }

    /**
     * 
     * @param type $total
     * @return boolean
     */
    public function payByPaypal($total)
    {
        if (isset($_GET['paymentId']) && isset($_GET['PayerID']) && isset($_GET['token'])) {
            $paymentId = $_GET['paymentId'];
            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    \Yii::$app->params['paypalClientID'],
                    \Yii::$app->params['paypalClientSecret']
                )
            );

            $newConfig = ArrayHelper::merge(
                $apiContext->getConfig(), [
                    'mode' => \Yii::$app->params['paypalMode']
                ]
            );
            $apiContext->setConfig($newConfig);

            $payment = \PayPal\Api\Payment::get($paymentId, $apiContext);
            $execution = new \PayPal\Api\PaymentExecution();
            $execution->setPayerId($_GET['PayerID']);
            $transaction = new \PayPal\Api\Transaction();
            $amount = new \PayPal\Api\Amount();
            $details = new \PayPal\Api\Details();

            $amount->setCurrency('EUR');
            $amount->setTotal($total);
            //$amount->setDetails($details);
            $transaction->setAmount($amount);
            $execution->addTransaction($transaction);

            try {
                $result = $payment->execute($execution, $apiContext);
                try {
                    $payment = \PayPal\Api\Payment::get($paymentId, $apiContext);
                } catch (Exception $ex) {
                    return false;
                }
            } catch (Exception $ex) {
                return false;
            }
            return $payment;
        } else {
            return true;
        }
    }

    /**
     * 
     * @param type $val
     * @param type $scale
     * @return type
     */
    private function getDecimalNumber($val, $scale = 2)
    {
        $number = number_format(round($val, $scale), $scale, '.', '');
        return $number;
    }

}
