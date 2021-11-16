<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\mobile\bridge
 * @category   CategoryName
 */
namespace open20\amos\mobile\bridge\modules\v1\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\ForgotPasswordForm;
use open20\amos\admin\models\LoginForm;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\mobile\bridge\modules\v1\actions\security\ActionLogin;
use open20\amos\mobile\bridge\modules\v1\actions\security\ActionLogout;
use open20\amos\mobile\bridge\modules\v1\actions\security\ActionVerifyAuth;
use open20\amos\mobile\bridge\modules\v1\models\AccessTokens;
use Exception;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\swiftmailer\Logger;

class SecurityController extends DefaultController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviours = parent::behaviors();
        unset($behaviours['authenticator']);

        return ArrayHelper::merge($behaviours, [
                'authenticator' => [
                    'class' => CompositeAuth::className(),
                    'optional' => [
                        'login',
                        'verify-auth',
                        'forgot-password'
                    ],
                    'authMethods' => [
                        'bearerAuth' => [
                            'class' => HttpBearerAuth::className(),
                        ]
                    ],
                ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function verbs()
    {
        return [
            'login' => ['post'],
            'logout' => ['post'],
            'verify-auth' => ['post'],
            'forgot-password' => ['post']
        ];
    }

    public function actions()
    {
        return [
            'login' => [
                'class' => ActionLogin::className(),
                'modelClass' => LoginForm::className(),
            ],
            'logout' => [
                'class' => ActionLogout::className(),
                'modelClass' => AccessTokens::className(),
            ],
            'verify-auth' => [
                'class' => ActionVerifyAuth::className(),
                'modelClass' => AccessTokens::className(),
            ],
        ];
    }

    /**
     * 
     * @return type
     */
    public function actionForgotPassword()
    {
        $ret = [];
        try {
            //Request params
            $bodyParams = Yii::$app->getRequest()->getBodyParams();

            $model = new ForgotPasswordForm();
            $model->email = $bodyParams['email'];
            if ($model->validate()) {
                if ($model->email != NULL) {
                    $dati_utente = $model->verifyEmail($model->email);
                    if ($dati_utente) {

                        $ret = $this->spedisciCredenziali($dati_utente->userProfile->id);
                    }
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $ret;
    }

    /**
     * 
     * @param type $id
     * @param type $isForgotPasswordView
     * @param type $isForgotPasswordRequest
     * @param type $urlCurrent
     * @return type
     */
    public function spedisciCredenziali($id)
    {
        $ret = [];
        try {
            $model = UserProfile::findOne($id);
            if ($model && $model->user && $model->user->email) {
                $model->user->generatePasswordResetToken();
                $model->user->save(false);
                $sent = UserProfileUtility::sendCredentialsMail($model);

                if ($sent) {

                    $ret = [
                        'msg' => AmosAdmin::t('amosadmin', 'Credenziali spedite correttamente alla email {email}',
                            ['email' => $model->user->email])];
                } else {

                    $ret = [
                        'msg' =>
                        AmosAdmin::t('amosadmin', 'Si è verificato un errore durante la spedizione delle credenziali')];
                }
            } else {

                $ret = [
                    'msg' => AmosAdmin::t('amosadmin', 'Si è verificato un errore durante la spedizione delle credenziali')];
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $ret;
    }
}
