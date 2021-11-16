<?php

namespace preference\userprofile\controllers;

use DateTime;
use open20\amos\admin\models\UserProfile;
use open20\amos\comuni\models\IstatProvince;
use preference\userprofile\models\IstatComuni;
use open20\amos\core\controllers\BackendController;
use open20\amos\core\utilities\CurrentUser;
use open20\amos\core\models\AccessTokens;
use preference\userprofile\exceptions\CreationRegisterdUserException;
use preference\userprofile\exceptions\LoadWizardDataException;
use preference\userprofile\models\base\PreferenceUsernameValidationToken;
use preference\userprofile\models\PreferenceTopicPrlToPc;
use preference\userprofile\models\PreferenceUserTargetAttribute;
use preference\userprofile\models\StepAppContacts;
use preference\userprofile\models\StepPersonalData;
use preference\userprofile\models\StepPreferences;
use preference\userprofile\models\StepPrivacy;
use preference\userprofile\utility\EmailUtility;
use preference\userprofile\utility\TargetAttributeUtility;
use preference\userprofile\utility\TargetTagUtility;
use preference\userprofile\utility\TopicTagUtility;
use preference\userprofile\utility\UserInterestTagUtility;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\ForbiddenHttpException;

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
class LoginAppController extends BackendController
{
    private $session = null;

    public function init()
    {
        $this->session = \Yii::$app->session;

        if (!$this->session->getIsActive()) {
            $this->session->open();
        }

        if (!empty(Yii::$app->user->id)) {
            Yii::$app->user->logout();
        } 
        
        parent::init();
    }

    public function behaviors()
    {
        $behaviours = parent::behaviors();
        unset($behaviours['authenticator']);
        $config = ArrayHelper::merge($behaviours,
            [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'redirect-to-url',
                            // 'login',
                        ],
                        'allow' => true,
                    ],
                ],
            ],
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    'bearerAuth' => [
                        'class' => HttpBearerAuth::className(),
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'login' => ['get', 'post'],
                ],
            ],
        ]);
        return $config;
    }

    /**
     * @return void
     */
    public function actionRedirectToUrl($url)
    {
        // var_dump(Yii::$app->user->id); die;
        // return $this->redirect('/preferenceuser/registration-app/preferences');
        return $this->redirect($url);
    }

}
