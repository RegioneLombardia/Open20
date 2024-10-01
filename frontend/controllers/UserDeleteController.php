<?php

namespace app\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\controllers\UserDropController;
use open20\amos\admin\exceptions\AdminException;
use open20\amos\admin\models\DropAccountForm;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileMailUtility;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use Yii;

class UserDeleteController extends \open20\amos\admin\controllers\UserProfileController {

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $result = ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'ruleConfig' => [
                        'class' => AccessRule::class,
                    ],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'drop-account-by-email',
                                'drop-account'
                            ],
                            'roles' => ['STANDARD_USER'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'remove-prevalent-partnership' => ['post'],
                        'delete' => ['post', 'get']
                    ]
                ]
            ]
        );
//var_dump($result['access']['rules']);die;
        return $result;
    }

    /**
     * @param int $id This is an user profile id! Not a user id!!!!!!
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDropAccountByEmail($id)
    {
        $this->setUpLayout('@vendor/open20/amos-layout/src/views/layouts/fullsize/main');
        $this->model = $this->findModel($id);

        //Avoid admin self-dropping
        $userIsAdmin = \Yii::$app->user->can('ADMIN');
        if ($userIsAdmin && ($id == \Yii::$app->user->identity->userProfile->id)) {
            throw new AdminException('Hey! Can\'t Drop ADMIN User');
        }
        if (!$userIsAdmin && ($this->model->user_id != Yii::$app->user->id)) {
            throw new AdminException('Not allowed to drop other users');
        }

        if (\Yii::$app->request->isPost) {
            $ok = self::sendEmailDropAccountRequest($this->model);
            if ($ok) {
                \Yii::$app->session->addFlash(
                    'success',
                    AmosAdmin::t(
                        'amosadmin',
                        "Ti abbiamo inviato una email per completare la cancellazione dalla piattaforma, hai a disposizione 24 ore per completare l'operazione"
                    )
                );
            }
            return $this->redirect(['/preferences/change']);
        }
        return $this->render('drop-account-by-email', ['model' => $this->model]);
    }

    /**
     * @param null $id
     * @param null $token
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDropAccount($id = null, $token = null)
    {

        $authorized = false;
        $confirm    = \Yii::$app->request->post('confirm');


        //check if the token is valid
        if (!empty($token)) {
            $id = UserProfile::checkDeleteToken($token);
            if (!empty($id)) {
                $authorized = true;
                if (!$confirm) {
                    $model = $this->findModel($id);
                    $this->setUpLayout('@vendor/open20/amos-layout/src/views/layouts/fullsize/main');
                    return $this->render('confirm-drop-account-by-email', ['model' => $model]);
                }
            }
        }

        if (empty($id)) {
            throw new ForbiddenHttpException(AmosAdmin::t('amosadmin', "Access denied"));
        }
        //Avoid admin self-dropping
        if (\Yii::$app->user->can('ADMIN') && $id == \Yii::$app->user->id) {
            throw new \Exception('Hey! Can\'t Drop ADMIN User');
        }

        $this->setUpLayout('form');

        $user  = $this->findModel($id);
        $model = new DropAccountForm();
        $redirectUrl = Url::home();

        if ($authorized || Yii::$app->request->isPost) {
            if (($authorized && $confirm) || ($model->load(Yii::$app->request->post()) && $model->validate())) {
                //New drop instance
                $dropController = new UserDropController('user_drop', $this->module);

                $moduleAdmin = \Yii::$app->getModule(AmosAdmin::getModuleName());

                // Send a report of user's assignments via mail if he/she has many of them
                if (!empty($moduleAdmin) && $moduleAdmin->sendUserAssignmentsReportOnDelete) {
                    // Security Policy copied from dropEverything() function below, so the email won't be sent
                    // if the function will throw an exception.
                    $this->sendUserAssignmentsReport($user->user_id, \Yii::$app->user->id);
                }

                if (!\Yii::$app->user->can('ADMIN') && $user->user_id != Yii::$app->user->id) {
                    throw new \Exception('Not allowed to drop other users');
                }

                //Irreversible action of user drop
                if (!empty($moduleAdmin) && $moduleAdmin->hardDelete) {
                    $dropController->dropEverything($user->user_id);
                } else {
                    $dropController->softDropEverything($user->user_id);
                }

                Yii::$app->getSession()->addFlash('success', AmosAdmin::t('amosadmin', 'Account Deleted.'));

                $redirectUrl = Url::to('/', true);
                Yii::$app->getSession()->remove('IDM');

                $url = \yii\helpers\Url::to(['/site/logout', 'redir' =>
                    '/Shibboleth.sso/Logout?return=https://idpcwrapper.crs.lombardia.it/PublisherMetadata/Logout?dest=' . urlencode($redirectUrl)
                ], true);
                return $this->redirect($url);
            } else {
                return $this->redirect($url);
            }
        } else {
            return $this->redirect($redirectUrl);
        }
    }

    /**
     * @param $userProfile UserProfile
     */
    public static function sendEmailDropAccountRequest($userProfile)
    {

        $appName = \Yii::$app->name;
        $token = $userProfile->generateDeleteToken();
        $subject = AmosAdmin::t('amosadmin', "{appName} - Richiesta cancellazione utente {nome} {cognome}", [
            'nome' => $userProfile->nome,
            'cognome' => $userProfile->cognome,
            'token' => $token,
            'appName' => $appName
        ]);

        $text = AmosAdmin::t('amosadmin', "È stata richiesta la cancellazione dalla piattaforma <strong>{appName}</strong> del seguente utente:
                <ul>
                    <li><strong>Nome:</strong> {nome}</li>
                    <li><strong>Cognome:</strong> {cognome}</li>
                </ul>
                <br>Clicca <a href='{linkDelete}'>qui</a> ed effettua il login per cancellare l'utente, hai a disposizione 1 giorno/i per completare l'operazione.
                <br><br>L'operazione è irreversibile; se confermi, il tuo account e tutti i dati a te associati verranno eliminati.", [
            'nome' => $userProfile->nome,
            'cognome' => $userProfile->cognome,
            'linkDelete' => \Yii::$app->urlManager->createAbsoluteUrl(['/user/confirm-delete', 'token' => $token]),
            'appName' => $appName

        ]);

        return UserProfileMailUtility::sendEmailGeneral($userProfile->user->email, $userProfile, $subject, $text);
    }
}